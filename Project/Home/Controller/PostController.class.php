<?php
namespace Home\Controller;

use Home\Controller\BaseController;
use Think\Upload;
use Think\Image;

/**
 * 回复类
 * Class PostController
 * @package Home\Controller
 */
class PostController extends BaseController{
	
	const ADD_POST_SUCCESS_CODE  =  1;
	const ADD_POST_FAILED_CODE   = -1;
	const BAD_REQUEST_CODE       = -2;
	const UPLOAD_IMG_FAILED_CODE = -3;
	
	/**
	 * 查看回复
	 * @author JunhaoHo
	 */
    public function viewPosts(){
    	$aid     = I('aid',null);
    	//更优的方法是通过SAE的KVDB实现计数，考虑到用户数较少暂且先这样做↓↓↓↓↓
    	D('Activity')->updateActivityCounterByAid($aid,'views');
    	$posts   = D('Post')->getPostsByAid($aid);
    	$info    = D('Activity')->getActivityByAid($aid,'subject,replies,views');
    	$subject = $info['subject'];
    	$replies = $info['replies'];
    	$views   = $info['views'];
    	$uids    = array();
    	foreach ($posts as $post){
    		$uids[]=$post['uid'];
    	}
    	$avatars = D('Member')->getAvatarsByUids($uids);
    	foreach ($posts as &$post){
    		$post['avatar'] = $avatars[$post['uid']];
    		$post['friendly_date'] = friendlyDate($post['dateline']);
    	}
    	$members_num = D('ActivityMember')->getMemberNumByAid($aid);
    	$haspost = count($posts) ? true : false ;
    	$this->assign('members_num',$members_num);
    	$this->assign('posts',$posts);
    	$this->assign("haspost",$haspost);
    	$this->assign("aid",$aid);
    	$this->assign('subject',$subject);
    	$this->assign('replies',$replies);
    	$this->assign('views',$views);
    	$this->display();
    }

    /**
     * 发表回复
     * @author JunhaoHo
     */
    public function addPost(){
    	$aid     = I('aid',null);
    	$content = I('content','');
    	if (empty($aid)) {
    		$result['code']  = self::BAD_REQUEST_CODE;
    		$result['error'] = "非法请求：请求参数不完整";
    	}else{
    		$uid      = session('uid');
    		$username = session('username');
    		$dateline = time();
    		$hasimg   = $_FILES['pic']['name'] ? 1 : 0;
    		if($hasimg){
    			$upload = new Upload();
			    $upload->maxSize   = 1024*1024*3 ;// 设置附件上传大小
			    $upload->exts      = array('jpg', 'png', 'jpeg');// 设置附件上传类型
			    $upload->saveName  = array('uniqid','');
			    $upload->rootPath  = C("UPLOAD_ROOT_PATH"); // 设置附件上传根目录
			    $upload->savePath  = 'upload/post/image/'; // 设置附件上传（子）目录
			    $upload->autoSub   = false;
			    $upload->driver    = 'Sae';
			    // 上传文件
			    $info   =   $upload->upload();
			    if(!$info) {// 上传错误提示错误信息
			    	$result['code']  = self::UPLOAD_IMG_FAILED_CODE;
			    	$error = $upload->getError();
			    	if(strpos($error, 'upload_max_filesize')){
			    		$error = '图片大小不得大于3MB';
			    	}elseif(strpos($error, '后缀')){
			    		$error = '不支持该图片格式';
			    	}
			    	$result['error'] = $error;
			    	$this->ajaxReturn($result);
			    }else{// 上传成功
			    	$imgname = $info['pic']['savename'];
			    	
			    	//生成等比例缩略图
			    	$image   = new Image();
			    	$path    = $upload->rootPath.$upload->savePath;
			    	$image->open($path.$imgname);
			    	$image->thumb(320, 320)->save($path.'thumbsmall_'.$imgname,null,90);
			    	$image->open($path.$imgname);
			    	$image->thumb(640, 640)->save($path.'thumbmedium_'.$imgname,null,90);
			    }
    		}else{
    			if(empty($content)){//图文都无不能post
    				$result['code']  = self::BAD_REQUEST_CODE;
    				$result['error'] = "您无语了吗...";
    				$this->ajaxReturn($result);
    			}else{
    				$imgname = '';
    			}
    		}
    		$post = array(
    			'aid'		=> $aid,
    			'uid' 		=> $uid,
    			'username'  => $username,
    			'hasimg'  	=> $hasimg,
    			'img' 		=> $imgname,
    			'content'   => $content,
    			'dateline'  => $dateline
    		);
    		$rs = D('Post')->addPost($post);
    		if($rs){
    			$result['code'] = self::ADD_POST_SUCCESS_CODE;
    			D('Activity')->updateActivityCounterByAid($aid,'replies');
    		}else{
    			$result['code'] = self::ADD_POST_FAILED_CODE;
    			$error = D('Post')->getDbError();
    			$error = substr($error,0,strpos($error,"[ SQL语句 ]"));
    			$result['error'] = $error;
    		}
    	}
    	$this->ajaxReturn($result);
    }
}