<?php
namespace Home\Model;

use Think\Model;

/**
 * 活动模型
 */
class ActivityModel extends Model{

    /**
     * 插入新活动
     * @param $date
     * @return mixed
     */
    public function insertActivity($data){
        return $this->add($data);
    }

    /**
     * 获取所有活动
     * @param string $order
     * @return mixed
     */
    public function getAllActivities($order='starttime asc'){
        return $this->order($order)->select();
    }

    /**
     * 获取未开始且未取消的活动
     * @param string $order
     * @return mixed
     */
    public function getActivitiesNotStart($order = 'starttime asc'){
        $where['starttime'] = array('gt',time());
        $where['status']    = array('egt',0);
        return $this->order($order)->where($where)->select();
    }

    /**
     * 活动关键字搜索
     * @param $keyword
     * @param int $limit
     * @param string $order
     * @return mixed
     */
    public function searchActivities($keyword,$limit = 0 ,$order='starttime desc'){
        $cid = A('Activity')->getCategoryId($keyword);
        if($cid){
            $where['category']      = array('eq',$cid);
        }
        $where['username']  = array('like', '%'.$keyword.'%');
        $where['subject']   = array('like','%'.$keyword.'%');
        $where['info']      = array('like','%'.$keyword.'%');
        $where['_logic']    = 'or';
        $map['_complex']    = $where;

        if($limit){
            return $this->where($map)->order($order)->limit($limit)->select();
        }else{
            return $this->where($map)->order($order)->select();
        }
    }

    /**
     * 通过活动id获取活动信息(find)
     * @param $aid
     */
    public function getActivityByAid($aid,$field=''){
        $where['aid'] = $aid;
        $field        = $field ? : '*';
        return $this->where($where)->field($field)->find();
    }
    
    /**
     * 通过活动id获取活动信息(select)
     * @param $aid
     */
    public function getActivitiesByAids($aids = array(),$field='',$orderby="dateline desc"){
    	$where['aid'] = array('in',$aids);
    	$field        = $field ? : '*';
    	return $this->where($where)->field($field)->order($orderby)->select();
    }
    
    /**
     * 检查活动是否可以申请报名
     * @param unknown $aid
     * @return boolean
     * @author JunhaoHo
     */
    public function checkCanJoinByAid($aid){
    	$rs     = $this->getActivityByAid($aid,'status');
    	$status = $rs['status'];
    	if($status==0){
    		return true;
    	} else{
    		return false;
    	}
    }
    
    /**
     * 检查活动是否为指定用户发布的
     * @author JunhaoHo
     */
    public function checkActivityFounder($aid,$uid){
    	$where['aid'] = $aid;
    	$where['uid'] = $uid;
    	$r = $this->where($where)->find();
    	return $r != null;   	
    }
    
    /**
     * 根据情况自动更新活动满人状态
     * @author JunhaoHo
     */
    public function updateStatusWhenMemberEnough($aid){
    	
    	$rs     = $this->getActivityByAid($aid,'status,limit');
    	$status = $rs['status'];
    	$limit  = $rs['limit'];
    	if($status==0 && $limit>0){//接收报名中且有人数限制
    		$num = D('ActivityMember')->getMemberNumByAid($aid);
    		if($num>=$limit){
    			return $this->updateActivityByAid($aid, array('status'=>1));
    		}
    	}
    	return true;
    }
    
    /**
     * 退出活动时更新状态
     * @param unknown $aid
     * @return Ambigous <\Home\Model\Ambigous, boolean, unknown>|boolean
     * @author JunhaoHo
     */
    public function updateStatusWhenMemberExit($aid){
    	 
    	$rs     = $this->getActivityByAid($aid,'status');
    	$status = $rs['status'];
    	if($status==1){//满人改为可报名
    		$this->updateActivityByAid($aid, array('status'=>0));
    	}
    }
    
    /**
     * 更新活动
     * @param unknown $data
     * @return Ambigous <boolean, unknown>
     * @author JunhaoHo
     */
    public function updateActivityByAid($aid,$data){
    	$where['aid'] = $aid;
    	return $this->where($where)->save($data);
    }

    /**
     * 自增自减计数器
     * @param unknown $aid
     * @param unknown $field 'replies','likes','views'
     * @param string $op 'setInc'默认 or  'setDec' 
     * @param number $step 幅度 1默认
     * @author JunhaoHo
     */
    public function updateActivityCounterByAid($aid,$field, $op = 'setInc' ,$step = 1){
    	$where['aid'] = $aid;
    	return $this->where($where)->$op($field,$step);
    }
    /**
     * 获取正在进行的活动列表
     */
    public function getActivitiesUnderway($order='starttime desc'){
        $curr = time();
        $where['status']    = array('egt',0);
        $where['starttime'] = array('elt',$curr);
        $where['endtime']   = array('gt',$curr);
        $rs = $this->where($where)->order($order)->select();
        return $rs;
    }

    /**
     * 获取过期活动
     */
    public function getActivitesExpired($order='endtime desc'){
        $where['status'] = array('egt',0);
        $where['endtime'] = array('lt',time());
        return $this->where($where)->order($order)->select();
    }
}