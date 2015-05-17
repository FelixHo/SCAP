<?php
namespace Home\Controller;

use Think\Controller;
use Com\Tencent\Wechat;
use Think\Log\Driver\Sae;
use Com\Tencent\WechatAuth;
use Home\Model\RobotModel;

class WechatController extends Controller{
    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    public function index($id = ''){
    	
        $token = C('WECHAT_CONFIG.WECHAT_TOKEN'); //微信后台填写的TOKEN
        
        /* 加载微信SDK */
        $wechat = new Wechat($token);

        /* 获取请求信息 */
        $data = $wechat->request();
        
        /*打印接收到的信息,仅作为sae调试之用*/
        $sae = new Sae();
        $sae->write(var_export($data,true));

        if($data && is_array($data)){

            /**
             * 你可以在这里分析数据，决定要返回给用户什么样的信息
             * 接受到的信息类型有9种，分别使用下面九个常量标识
             * Wechat::MSG_TYPE_TEXT       //文本消息
             * Wechat::MSG_TYPE_IMAGE      //图片消息
             * Wechat::MSG_TYPE_VOICE      //音频消息
             * Wechat::MSG_TYPE_VIDEO      //视频消息
             * Wechat::MSG_TYPE_MUSIC      //音乐消息
             * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
             * Wechat::MSG_TYPE_LOCATION   //位置消息
             * Wechat::MSG_TYPE_LINK       //连接消息
             * Wechat::MSG_TYPE_EVENT      //事件消息
             *
             * 事件消息又分为下面五种
             * Wechat::MSG_EVENT_SUBSCRIBE          //订阅
             * Wechat::MSG_EVENT_SCAN               //二维码扫描
             * Wechat::MSG_EVENT_LOCATION           //报告位置
             * Wechat::MSG_EVENT_CLICK              //菜单点击
             * Wechat::MSG_EVENT_MASSSENDJOBFINISH  //群发消息成功
             */

             

            /* 响应当前请求(自动回复) */
            // $wechat->response($content, $type);

        	/**
             * 响应当前请求还有以下方法可以只使用
             * 具体参数格式说明请参考文档
             * 
             * $wechat->replyText($text); //回复文本消息
             * $wechat->replyImage($media_id); //回复图片消息
             * $wechat->replyVoice($media_id); //回复音频消息
             * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
             * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
             * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
             * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
             * 
             */
             $msg_type = $data['MsgType'];
             
             if($msg_type==Wechat::MSG_TYPE_EVENT){
             	
             	if($data['Event']=='subscribe'){
             		$wechat->replyText('Hello , thanks for your subscribtion');
             	}
             }elseif($msg_type==Wechat::MSG_TYPE_IMAGE){
             	
             	$wechat->replyText("你发了一张图片,PicUrl:{$data['PicUrl']} ,MediaId:{$data['MediaId']} ,MsgId:{$data['MsgId']}");
             
             }elseif ($msg_type==Wechat::MSG_TYPE_LOCATION){
             	
             	$wechat->replyText("你发了一个地理位置,Location_X:{$data['Location_X']} ,Location_Y:{$data['Location_Y']} ,Label:{$data['Label']} , Scale:{$data['Scale']}");
             
             }elseif($data['Content']=="one news"){
                $content = date('Y-m-d H:i:s'); //回复内容，回复不同类型消息，内容的格式有所不同
             	$wechat->replyNewsOnce("这是一个标题{$content}", "here is the default description", "http://hejunhao.me", 'http://cdn.duitang.com/uploads/item/201403/04/20140304122431_XMCuj.jpeg');
             
             }elseif($data['Content']=='music'){
             	
             	$wechat->replyMusic("When You Told Me You Loved Me ", "Jessica Simpson", "http://yinyueshiting.baidu.com/data2/music/35490329/8322233147600128.mp3?xcode=73f18b828b4376f671a3d2f34deba9b44840997fc218e9e7", "http://yinyueshiting.baidu.com/data2/music/35490329/8322233147600128.mp3?xcode=73f18b828b4376f671a3d2f34deba9b44840997fc218e9e7",null);
             	
             }else{
                 $robot = new RobotModel();
             	 $robot->chat($data['Content'],$wechat);
             }
        }
    }
    
     private function _get_access_token(){
    	$key = C('WECHAT_CONFIG.WECHAT_ACCESS_TOKEN_KEY');
    	$access_token = S($key);
    	
    	if (!$access_token) {//access_token已经过期
    		$appid = C('WECHAT_CONFIG.WECHAT_APPID');
    		$secret = C('WECHAT_CONFIG.WECHAT_APPSECRET');
    	    
    		$wechatAuth = new WechatAuth($appid, $secret);
    		$data = $wechatAuth->getAccessToken();
    		$access_token = $data['access_token'];
    		$expire = $data['expires_in']-60;
    		
    		S($key,$access_token,$expire);
    	}
    	return $access_token;
    }
}
