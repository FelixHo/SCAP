<?php
namespace Home\Controller;

use Home\Controller\BaseController;
use Think\Upload;
use Home\Model\ActivityMemberModel;
use Think\Image;
/**
 * 用户类
 */

class UserController extends BaseController{

    const EDIT_PROFILE_SUCCESS_CODE      =  1;
    const EDIT_PROFILE_FAILED_CODE       = -1;
    const BAD_REQUEST__CODE              = -600;
    const CHANGE_PASSWORD_FAILED_CODE    = -11;
    const CHANGE_PASSWORD_SUCCESS_CODE   =  11;
    const UPDATE_AVATAR_FAILED_CODE      =  -21;
    const UPDATE_AVATAR_SUCCESS_CODE     =  21;

    /**
     * 个人中心
     */
    public function personalSpace(){

        $user = D('Member')->getUserByUid(session('uid'));
        $aids = D('ActivityMember')->getAidsByUid(session('uid'));

        $activities      = D('Activity')->getActivitiesByAids($aids);
        $activityMemberD = new ActivityMemberModel();
        foreach ($activities as &$activity){
        	if(time()>$activity['starttime'] && time()<$activity['endtime'] && $activity['status']>=0){//活动进行中
        		$activity['status'] = 10;
        	}elseif(time()>$activity['endtime'] && $activity['status']>=0){//已经结束
        		$activity['status'] = -2;
        	}
        	$activity['status_info']    = A('Activity')->getStatusInfo($activity['status']);
        	$activity['members_num']    = $activityMemberD->getMemberNumByAid($activity['aid']);
        	$activity['isfounder']      = $activity['uid'] == session('uid') ? true : false;
        }
        
        $this->assign("activities",$activities);
        $this->assign('user',$user);
        $this->display();
    }

    /**
     * 编辑用户信息
     */
    public function editProfile(){
        if(IS_POST){
            $email = I('email','','trim');
            $profile = I('profile','','trim');
            $mobile  = I('mobile','','trim');
            if(empty($email)){
                $result['code'] = self::BAD_REQUEST__CODE;
                $result['error']= '邮箱地址不能为空';
            }else{
                $data = array(
                    'uid'=>session('uid'),
                    'email'=>$email,
                    'mobile'=>$mobile,
                    'profile'=>$profile
                );
                $rs = D('Member')->updateMemberByUid(session('uid'),$data);
                if($rs!==false){
                	session('email',$email);
                	$this->_updateAvatar();
                    $result['code'] = self::EDIT_PROFILE_SUCCESS_CODE;
                }else{
                    $result['code'] = self::EDIT_PROFILE_FAILED_CODE;
                    $error = D('Member')->getDbError();
                    $error = substr($error,0,strpos($error,"[ SQL语句 ]"));
                    $result['error'] = $error;
                }
            }
            $this->ajaxReturn($result);
        }
    }
    
    /**
     * 头像更新
     * @author JunhaoHo
     */
    private function _updateAvatar(){
    	$hasimg   = $_FILES['pic']['name'] ? 1 : 0;
    	if($hasimg){
    		$upload = new Upload();
    		$upload->maxSize   = 1024*1024*2 ;// 设置附件上传大小
    		$upload->exts      = array('jpg', 'png', 'jpeg');// 设置附件上传类型
    		$upload->saveName  = array('uniqid','');
    		$upload->rootPath  = C("UPLOAD_ROOT_PATH"); // 设置附件上传根目录
    		$upload->savePath  = 'upload/avatar/'; // 设置附件上传（子）目录
    		$upload->autoSub   = false;
    		$upload->driver    = 'Sae';
    		// 上传文件
    		$info   =   $upload->upload();
    		if(!$info) {// 上传错误提示错误信息
    			$result['code']  = self::UPDATE_AVATAR_FAILED_CODE;
    			$error = $upload->getError();
    			if(strpos($error, 'upload_max_filesize')||strpos($error, '文件大小不符')){
    				$error = '头像大小不得大于2MB';
    			}elseif(strpos($error, '后缀')){
    				$error = '不支持该图片格式';
    			}
    			$result['error'] = $error;
    		}else{// 上传成功
    			$imgname = $info['pic']['savename'];
    			//生成等比例缩略图
    			$image   = new Image();
    			$path    = $upload->rootPath.$upload->savePath;
    			$image->open($path.$imgname);
    			$image->thumb(128, 128)->save($path.'thumbmini_'.$imgname,null,90);
    			$rs = D('Member')->updateMemberByUid(session('uid'),array('avatar'=>$imgname));
    			if($rs){
    				$result['code'] = self::UPDATE_AVATAR_SUCCESS_CODE;
    				session('avatar',$imgname);
    			}else{
    				$result['code'] = self::UPDATE_AVATAR_FAILED_CODE;
    				$error = D('Member')->getDbError();
    				$error = substr($error,0,strpos($error,"[ SQL语句 ]"));
    				$result['error'] = $error;
    			}
    		}
    	}
    	if($result['code']==self::UPDATE_AVATAR_FAILED_CODE){
    		$this->ajaxReturn($result);
    	}
    }

    /**
     * 修改密码
     */
    public function changePassword(){

        $oldpsw = trim(I('oldpsw',''));
        $newpsw = trim(I('newpsw',''));
        $cfmpsw = trim(I('cfmpsw',''));

        if(empty($newpsw)||empty($oldpsw)||empty($cfmpsw)){
            $result['code'] = self::BAD_REQUEST__CODE;
            $result['error']= '非法请求:参数不完整';
        }elseif($newpsw==$oldpsw && $newpsw==$cfmpsw){
            $result['code'] = self::BAD_REQUEST__CODE;
            $result['error']= '新旧密码不能相同!';
        }elseif($newpsw!=$cfmpsw){
            $result['code'] = self::BAD_REQUEST__CODE;
            $result['error']= '两次输入的新密码不一致,请重新输入！';
        }else{
            $user = D('Member')->getUserByUid(session('uid'),'salted_hash');
            $hash = $user['salted_hash'];
            $iscorrect = validate_password($oldpsw,$hash);
            if($iscorrect){
                $newhash = create_hash($newpsw);
                $rs = D('Member')->updateMemberByUid(session('uid'),array('salted_hash'=>$newhash));
                if($rs){
                    $result['code'] = self::CHANGE_PASSWORD_SUCCESS_CODE;
                    session('username',null);
                    session('uid',null);
                    session('email',null);
                    session('groupid',null);
                    session('avatar',null);
                    session('gender',null);
                }else{
                    $result['code'] = self::CHANGE_PASSWORD_FAILED_CODE;
                    $error = D('Member')->getDbError();
                    $error = substr($error,0,strpos($error,"[ SQL语句 ]"));
                    $result['error'] = $error;
                }

            }else{
                $result['code'] = self::CHANGE_PASSWORD_FAILED_CODE;
                $result['error']= '密码错误,请重新输入！';
            }
        }
        $this->ajaxReturn($result);
    }
    
    /**
     * 
     * 获取用户资料
     * @author JunhaoHo
     */
    public function getProfile(){
    	if(IS_POST){
    		$uid = I('uid',null);
    		$profile = D('Member')->getUserByUid($uid);
    		$profile['group'] = $profile['groupid'] == 1 ? '普通用户' : '管理员';
    		$profile['gender'] = $profile['gender'] == 'male' ? '男' : '女';
    		$profile['profile'] = $profile['profile'] ? : '保密';
    		$profile['mobile'] = $profile['mobile'] ?  : '保密';
    		$this->ajaxReturn($profile);
    	}
    }
}