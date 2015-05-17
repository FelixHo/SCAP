<?php
namespace Home\Controller;

use Think\Controller;
use Home\Model\MemberModel;
/**
 * 管理员控制器
 * @author JunhaoHo
 */
class AdminController extends Controller{
	
	const   ADD_USER_FAILED_CODE    =   -1;
	const   ADD_USER_SUCCESS_CODE   =    1;
	
	/**
	 * 新增用户
	 * @author JunhaoHo
	 */
	public function addUser(){
		
		if(IS_POST){//防止#jQuery.load()的AJAX误判,不要用IS_AJAX判断
			
			$username 	 = I('username',null);//用户名
			$email    	 = I('email',null);//邮件地址
			$gender   	 = I('gender',null);//性别
			$groupid  	 = I('groupid',null);//用户组id
			$password 	 = "password";//默认密码
			$salted_hash = create_hash($password);
			$regtime     = time();
			$avatar      = $gender.'_default.png';
			
			$data = array(
				'username' 		=> $username,
				'email'    		=> $email,
				'gender'   		=> $gender,
				'groupid'	 	=> $groupid,
				'salted_hash' 	=> $salted_hash,
				'regtime'       => $regtime,
				'avatar'        => $avatar 
			);
			$uid = D('Member')->addUser($data);
			if($uid){
				$result['code']  = self::ADD_USER_SUCCESS_CODE;
			}else{
				$result['code']  = self::ADD_USER_FAILED_CODE;
				$error = D('Member')->getDbError();
                $str   = "Duplicate entry '{$username}' for key 'username'";

				$error = substr($error,0,strpos($error,"[ SQL语句 ]"));
                $error = strpos($error,$str) ? '已存在相同用户名!' : $error;
				$result['error'] = $error;
			}
			$this->ajaxReturn($result);
		}
		$this->display();
	}
}