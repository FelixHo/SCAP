<?php
namespace Home\Controller;

use Home\Controller\BaseController;
use Home\Model\ActivityMemberModel;
use Home\Model\MemberModel;
use Home\Model\PostModel;

class ActivityController extends BaseController{

    const CREATE_ACTIVITY_SUCCESS_CODE =  1;
    const CREATE_ACTIVITY_FAILED_CODE  = -1;
    const BAD_REQUEST_CODE             = -600;
    const JOIN_SUCCESS_CODE            =  11;
    const JOIN_FAILED_CODE             = -11;
    const EXIT_ACTIVITY_SUCCESS_CODE   =  21;
    const EXIT_ACTIVITY_FAILED_CODE    = -21;
    const CANCEL_ACTIVITY_SUCCESS_CODE =  31;
    const CANCEL_ACTIVITY_FAILED_CODE  = -31;
    const STOP_JOIN_SUCCESS_CODE 	   =  41;
    const STOP_JOIN_FAILED_CODE        = -41;
    const DELETE_ACTIVITY_FAILED_CODE  =  -51;
    const DELETE_ACTIVITY_SUCCESS_CODE =  51;

    /**
     * 即将进行的活动
     */
	public function activities(){
        $keyword = I('keyword','');
        if($keyword){
            $title      = "搜索结果";
            $activities = D("Activity")->searchActivities($keyword,200);
        }else{
            $title      = "即将进行";
            $activities = D("Activity")->getActivitiesNotStart();
        }

        $memberD = new MemberModel();
        $activityMemberD = new ActivityMemberModel();

        foreach($activities as &$activity){
            $avatar = $memberD->getAvatarByUid($activity['uid']);
            $activity['avatar']         = $avatar;
            $activity['friendly_date']  = friendlyDate($activity['dateline'],'mohu');
            $activity['category_name']  = $this->getCategoryName($activity['category']);
            $activity['starttime_r']    = date("m月d日 H:i",$activity['starttime']);
            $activity['endtime_r']      = date("m月d日 H:i",$activity['endtime']);
            $activity['daydiff']        = friendlyDayDiff($activity['starttime']);
            $activity['info']           = cutstr($activity['info'],1000);
            $activity['members_num']    = $activityMemberD->getMemberNumByAid($activity['aid']);
            $activity['has_join']       = $activityMemberD->checkActivityMemberWithAidNUid($activity['aid'], session('uid'));
            
            if(time()>$activity['starttime'] && time()<$activity['endtime'] && $activity['status']>=0){//活动进行中
                $activity['status'] = 10;
            }elseif(time()>$activity['endtime'] && $activity['status']>=0){//已经结束
                $activity['status'] = -2;
            }
            $activity['status_info']    = $this->getStatusInfo($activity['status']);
        }
        $this->assign('title',$title);
        $this->assign("activities",$activities);
		$this->display();
	}

    /**
     * 查看活动详情介绍
     */
    public function activityInfo(){
        $aid = I("aid",null);
        $activity = D('Activity')->getActivityByAid($aid);
        $activity['friendly_date']  = friendlyDate($activity['dateline']);
        $activity['category_name']  = $this->getCategoryName($activity['category']);
        $activity['starttime_r']    = date("m月d日 H:i",$activity['starttime']);
        $activity['endtime_r']      = date("m月d日 H:i",$activity['endtime']);
        $location                   = explode(',',$activity['location']);
        $activity['lng']            = $location[0];
        $activity['lat']            = $location[1];
        $activity['founder_avatar'] = D('Member')->getAvatarByUid($activity['uid']);

        if(time()>$activity['starttime'] && time()<$activity['endtime'] && $activity['status']>=0){//活动进行中
            $activity['status'] = 10;
        }elseif(time()>$activity['endtime'] && $activity['status']>=0){//已经结束
            $activity['status'] = -2;
        }
        $activity['status_info']    = $this->getStatusInfo($activity['status']);

        $members = D('ActivityMember')->getActivityMembersByAid($aid);
        foreach($members as $member){
            $member_uids[]=$member['uid'];
        }
        $member_avatars = D('Member')->getAvatarsByUids($member_uids);
        foreach($members as &$member){
            $member['avatar'] = $member_avatars[$member['uid']];
        }

        $activity['has_join'] = D('ActivityMember')->checkActivityMemberWithAidNUid($aid,session('uid'));
        $left                 = $activity['limit'] - count($members);
        $activity['left']     = $activity['limit'] == 0 ? '不限名额' : ($left == 0 ? '已满员' : "还有".$left."个名额");//剩下多少个名额

        $this->assign("activity",$activity);
        $this->assign("members",$members);
        $this->display();
    }

    /**
     * 正在进行的活动
     * @author JunhaoHo
     */
    public function activityUnderway(){
        $activities      = D('Activity')->getActivitiesUnderway();
        $activityMemberD = new ActivityMemberModel();
        $memberD         = new MemberModel();
        foreach ($activities as &$activity) {
            $activity['category_name'] = $this->getCategoryName($activity['category']);
            $activity['friendly_date'] = friendlyDate($activity['starttime']);
            $members                   = $activityMemberD->getActivityMembersByAid($activity['aid']);
            $member_uids               = array();
            foreach($members as $member){
                $member_uids[]=$member['uid'];
            }
            $member_avatars = $memberD->getAvatarsByUids($member_uids);

            foreach($members as &$_member){
                $_member['avatar'] = $member_avatars[$_member['uid']];
            }
            $activity['members']     = $members;
            $activity['members_num'] = count($members);
        }
        $this->assign("activities",$activities);
        $this->display();
    }
    
    /**
     * 过期的活动
     * @author JunhaoHo
     */
    public function activityExpired(){
    	$activities      = D('Activity')->getActivitesExpired();
        $postD           = new PostModel();
        $activityMemberD = new ActivityMemberModel();

        foreach($activities as &$activity){
            $cover = $postD->getRandomPicByAid($activity['aid']);
            $activity['cover']     = $cover ? : '';
            $activity['Y']         = date('Y',$activity['starttime']);
            $activity['M']         = date('M',$activity['starttime']);
            $activity['j']         = date('j',$activity['starttime']);
            $activity['Y_display'] = date('Y') == $activity['Y'] ? 'none' : 'block';
            $activity['j_display'] = $activity['Y_display'] == 'none' ? 'block' : 'none';
            $activity['member_num']= $activityMemberD->getMemberNumByAid($activity['aid']);
        }
        $this->assign('activities',$activities);
    	$this->display();
    }
    
    /**
     * 创建活动
     */
	public function createActivity(){
        if(IS_POST){
            $subject    = I("subject");
            $info       = I("info");
            $starttime  = I("starttime");
            $endtime    = I("endtime");
            $limit      = I("limit",0);
            $category   = I("category");
            $address    = I("address");
            $location   = I("location");

            if(empty($subject)||empty($info)||empty($starttime)||empty($endtime)||empty($category)||empty($address)||empty($location)){
                $result['code']  = self::BAD_REQUEST_CODE;
                $result['error'] = "非法请求:参数不完整";
                $this->ajaxReturn($result);
            }
            $starttime  = strtotime($starttime);
            $endtime    = strtotime($endtime);

            if($starttime>=$endtime){
                $result['code']  = self::BAD_REQUEST_CODE;
                $result['error'] = "时间段设置不合理,结束时间不得早于开始时间!";
                $this->ajaxReturn($result);
            }elseif ($limit==1){
            	$result['code']  = self::BAD_REQUEST_CODE;
            	$result['error'] = "活动限制人数不得低于2人!";
            	$this->ajaxReturn($result);
            }

            $data = array(
                'subject'   => $subject,
                'info'      => $info,
                'starttime' => $starttime,
                'endtime'   => $endtime,
                'limit'     => $limit,
                'category'  => $category,
                'address'   => $address,
                'location'  => $location,
                'username'  => session("username"),
                'uid'       => session("uid"),
                'dateline'  => time(),
            );
            $rs = D("Activity")->insertActivity($data);
            if($rs){
                $member['uid']       = $data['uid'];
                $member['username']  = $data['username'];
                $member['dateline']  = $data['dateline'];
                $member['isfounder'] = 1;
                $member['message']   = '';
                $member['aid']       = $rs;
                D('ActivityMember')->insertMember($member);

                $result['code'] = self::CREATE_ACTIVITY_SUCCESS_CODE;
            }else{
                $result['code'] = self::CREATE_ACTIVITY_FAILED_CODE;
                $error = D('Activity')->getDbError();
                $error = substr($error,0,strpos($error,"[ SQL语句 ]"));
                $result['error'] = $error;
            }
            $this->ajaxReturn($result);
        }
		$this->display();
	}

	
	/**
	 * 活动报名
	 * @author JunhaoHo
	 */
	public function join(){
		$aid 		= I("aid",null);
		$uid 		= I("uid",null);
		$username 	= I("username",null);
		$isfounder 	= I("isfounder",0);
		$message 	= I("message",'');
		$dateline 	= time();
		if(empty($aid)||empty($uid)||empty($username)){
			$result['code']  = self::BAD_REQUEST_CODE;
			$result['error'] = "非法请求:参数不完整";
            $this->ajaxReturn($result); 
		}else{
			$rs = D('Activity')->checkCanJoinByAid($aid);
			if($rs==false){
				$result['code'] = self::JOIN_FAILED_CODE;
				$result['error'] = "活动已经满员或已不能再报名啦!";
				$this->ajaxReturn($result);
			}
			$member = array(
				'aid'=>$aid,
				'uid'=>$uid,
				'username'=>$username,
				'isfounder'=>$isfounder,
				'message'=>$message,
				'dateline'=>$dateline
			);
			$rs = D('ActivityMember')->insertMember($member);
			if($rs){
				$result['code'] = self::JOIN_SUCCESS_CODE;
				D('Activity')->updateStatusWhenMemberEnough($aid);
			}else{
				$result['code'] = self::JOIN_FAILED_CODE;
				$error = D('ActivityMember')->getDbError();
				$error = substr($error,0,strpos($error,"[ SQL语句 ]"));
				$result['error'] = $error;
			}
			$this->ajaxReturn($result);
		}
	}
	
	/**
	 * 退出活动
	 * @author JunhaoHo
	 */
	public function exitActivity(){
		$aid = I('aid',null);
		if(empty($aid)){
			$result['code'] = self::BAD_REQUEST_CODE;
			$result['error'] = '非法请求!';
		}else{
			$rs = D('ActivityMember')->deleteMemerByAidNUid($aid,session('uid'));
			if($rs){
				$result['code'] = self::EXIT_ACTIVITY_SUCCESS_CODE;
				D('Activity')->updateStatusWhenMemberExit($aid);
			}else{
				$result['code'] = self::EXIT_ACTIVITY_FAILED_CODE;
				$error = D('ActivityMember')->getDbError();
				$error = substr($error,0,strpos($error,"[ SQL语句 ]"));
				$result['error'] = $error;
			}
		}
		$this->ajaxReturn($result);
	}
	
	/**
	 * 取消活动
	 * @author JunhaoHo
	 */
	public function cancelActivity(){
		$aid = I('aid',null);
		if(empty($aid)){
			$result['code'] = self::BAD_REQUEST_CODE;
			$result['error'] = '非法请求!';
		}else{
			$rs = D('Activity')->updateActivityByAid($aid,array('status'=>-1));
			if($rs){
				$result['code'] = self::CANCEL_ACTIVITY_SUCCESS_CODE;
			}else{
				$result['code'] = self::CANCEL_ACTIVITY_FAILED_CODE;
				$error = D('Activity')->getDbError();
				$error = substr($error,0,strpos($error,"[ SQL语句 ]"));
				$result['error'] = $error;
			}
		}
		$this->ajaxReturn($result);
	}
	
	/**
	 * 停止报名
	 * @author JunhaoHo
	 */
	public function stopJoin(){
		$aid = I('aid',null);
		if(empty($aid)){
			$result['code'] = self::BAD_REQUEST_CODE;
			$result['error'] = '非法请求!';
		}else{
			$rs = D('Activity')->updateActivityByAid($aid,array('status'=>2));
			if($rs){
				$result['code'] = self::STOP_JOIN_SUCCESS_CODE;
			}else{
				$result['code'] = self::STOP_JOIN_FAILED_CODE;
				$error = D('Activity')->getDbError();
				$error = substr($error,0,strpos($error,"[ SQL语句 ]"));
				$result['error'] = $error;
			}
		}
		$this->ajaxReturn($result);
	}
	
	/**
	 * 删除活动
	 * @author JunhaoHo
	 */
	public function delActivity(){
		$aid = I('aid',null);
		$flag = D('Activity')->checkActivityFounder($aid,session('uid'));
		if(!$flag){
			$result['code'] = self::DELETE_ACTIVITY_FAILED_CODE;
			$result['error']= '不能删除不属于您组织的活动!';
		}else{
			$r1 = D('Activity')->delete($aid);//删除活动
			$r2 = D('ActivityMember')->delAllMembersByAid($aid);//取消所有报名了的用户
			if( $r1 && $r2 ){
				$result['code'] = self::DELETE_ACTIVITY_SUCCESS_CODE;
			}else{
				$result['code'] = self::DELETE_ACTIVITY_FAILED_CODE;
				$error1 = D('Activity')->getDbError();
				$error1 = substr($error1,0,strpos($error1,"[ SQL语句 ]"));
				$error2 = D('ActivityMember')->getDbError();
				$error2 = substr($error2,0,strpos($error2,"[ SQL语句 ]"));
				$result['error'] = $error1.PHP_EOL.$error2;
			}
		}
		$this->ajaxReturn($result);
	}
	
    /**
     * 获取活动类型可读信息
     * @param $val
     * @return mixed
     */
    public function getCategoryName($val){

        $list = array(
            '1'   => "运动",
            '2'   => "出游",
            '3'   => "聚会",
            '4'   => "联谊",
            '5'   => "娱乐",
            '999' => "其他",
        );
        return $list[$val];
    }

    /**
     * 获取类型id
     * @param $val
     * @return mixed
     */
    public function getCategoryId($val){

        $list = array(
            '运动'   => "1",
            '出游'   => "2",
            '聚会'   => "3",
            '联谊'   => "4",
            '娱乐'   => "5",
            '其他'   => "999",
        );
        return $list[$val];
    }

    /**
     * 获取状态可读信息
     * @param $status
     * @return mixed
     */
    public function getStatusInfo($status){
    	
        $list = array(
            '-2'  => '已结束',//伪状态
            '-1'  => '已经取消',
            '0'   => '接受报名中',
            '1'   => '名额已满',//逻辑行为
            '2'   => '停止报名',//人为决定
            '10'  => '正在进行中'//伪状态
        );
        return $list[$status];
    }
}