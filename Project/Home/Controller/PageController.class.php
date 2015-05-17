<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 异常页面类
 * @author JunhaoHo
 */
class PageController extends Controller{
	
	//浏览器不兼容
	public function alert(){
		if(checkIE(false)){
			$this->display();
		}else{
			redirect(APP_URL.'/Home/main/index');
		}
	}
}