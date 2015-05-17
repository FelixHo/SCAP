<?php
namespace Home\Controller;
use Home\Controller\BaseController;
use Home\Model\RobotModel;
class IndexController extends BaseController {
    public function index(){
        redirect(APP_URL.'/Home/Main/index');
    	$info = I('info');
    	if ($info) {
    		D('Robot')->chat($info);
    	}else{
    		echo "tell me sth.";
    	}
    }
}