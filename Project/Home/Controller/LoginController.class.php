<?php
namespace Home\Controller;

use Think\Controller;
use Think\Verify;
/**
 * 登录控制器
 * @author JunhaoHo
 */
class LoginController extends Controller{

    const VERIFY_FAILED_TIP     =   '验证码不正确,请重新输入~';
    const   AUTH_FAILED_TIP     =   '用户名或密码不正确!';

    const VERIFY_FAILED_CODE    =   -2;
    const   AUTH_FAILED_CODE    =   -1;
    const       SUCCESS_CODE    =    1;
	
	public function index(){
		checkIE();
        if(IS_POST){
            $username = I('username',null);
            $password = I('password',null);
            $code = I('code',null);
            $check_verify = $this->_check_verify($code);

            if($check_verify){
                $user = D('Member')->getUsersByUsername($username);
                $auth_success = false;

                if($user){
                    $auth_success =  validate_password($password,$user[0]['salted_hash']);
                }

                if($auth_success){
                    $result['code'] = self::SUCCESS_CODE;
                    session('username',$user[0]['username']);
                    session('uid',$user[0]['uid']);
                    session('email',$user[0]['email']);
                    session('groupid',$user[0]['groupid']);
                    session('gender',$user[0]['gender']);
                    session('avatar',$user[0]['avatar']);
                }else{
                    $result['code']  = self::AUTH_FAILED_CODE;
                    $result['error'] = self::AUTH_FAILED_TIP;
                }

            }else{
                $result['code']  = self::VERIFY_FAILED_CODE;
                $result['error'] = self::VERIFY_FAILED_TIP;
            }
            $this->ajaxReturn($result);
        }
		$this->display();
	}

    /**
     * 生成验证码图
     */
    public function verify(){
        $config =    array(
            'fontSize'    =>    18,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
            'expire'      =>    600,   // 验证码过期时间（s）
            'useImgBg'    =>    true,  // 使用背景图片
            'imageH'      =>    35,    // 验证码图片高度
            'fontttf'     =>  '6.ttf', // 验证码字体，不设置随机获取
        );
        $Verify =     new Verify($config);
        $Verify->entry();
    }

    /**
     * 检测输入的验证码是否正确，$code为用户输入的验证码字符串
     * @param $code
     * @param string $id
     * @return bool
     */
    private function _check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }
    
    /**
     * 登出
     * @author JunhaoHo
     */
    public function logout(){
    	session('username',null);
    	session('uid',null);
    	session('email',null);
        session('groupid',null);
    	session('avatar',null);
    	session('gender',null);
    	redirect(APP_URL.'/Home/Login/index');
    }
}