<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 基础控制器
 * @author JunhaoHo
 */
class BaseController extends Controller{
	public function _initialize(){
		// 发送header, 修复 IE 浏览器在 iframe 下限制写入 cookie 的问题
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		header('Content-Type:text/html;charset=utf-8');
		checkIE();
		if(session('username')){
            $this->username =  session('username');
            $this->uid      = session('uid');
            $this->assign('username', $this->username);
            $this->assign('uid', $this->uid);
		}else{
			redirect(APP_URL.'/Home/login/index');
		}		
	}
}