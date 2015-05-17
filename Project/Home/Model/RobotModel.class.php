<?php
namespace Home\Model;
use Com\Tencent\Wechat;
use Think\Log\Driver\Sae;
use Think\Model;
class RobotModel extends Model{
	
	/*API 返回CODE字段类型标识*/
	const TEXT_TYPE_CODE        = 100000;//文本类
	const URL_TYPE_CODE         = 200000;//网址类
	const NEWS_TYPE_CODE 		= 302000;//新闻
	const APP_TYPE_CODE 		= 304000;//应用、软件下载
	const TRAIN_TYPE_CODE 		= 305000;//列车
	const FLIGHT_TYPE_CODE 		= 306000;//航班
	const INFO_TYPE_CODE 	    = 308000;//菜谱、视频
	const HOTEL_TYPE_CODE 		= 309000;//酒店
	const PRICE_TYPE_CODE 		= 311000;//价格
	const RESTAURANT_TYPE_CODE 	= 312000;//餐厅
	
	/*API 返回CODE字段错误标识*/
	private $code_list = array(
		'40001'=>'key的长度错误（32位）',
		'40002'=>'请求内容为空',
		'40003'=>'key错误或帐号未激活',
		'40004'=>'当天请求次数已用完',
		'40005'=>'暂不支持该功能',
		'40006'=>'服务器升级中',
		'40007'=>'服务器数据格式异常',
		'50000'=>'机器人设定的“学用户说话”或者“默认回答”',
	);

	public function chat($info,$wechat){
		$url = C('ROBOT_API_URL');
		$param['key'] = C('ROBOT_API_KEY');
		$param['info'] = $info;
		$data = $this->http($url, $param);
		$data = json_decode($data,true);

        switch($data['code']){
            case self::TEXT_TYPE_CODE:{

            	D('RobotChatLog')->addChatLog($info,$data['text']);
                $wechat->replyText($data['text']);
                break;
            }
            case self::URL_TYPE_CODE:{

                $str = $data['text']."\n".$this->urlWrapper($data['url'],'点击查看');
                D('RobotChatLog')->addChatLog($info,$str);
                $wechat->replyText($str);
                break;
            }
            case self::NEWS_TYPE_CODE:{

                $news = array();
                foreach($data['list'] as $item){
                    if($item['icon']){//没有图标的不要
                        //[标题，说明，全文连接，图片链接]
                        $news[]=array($item['article'],'来自:'.$item['source'],$item['detailurl'],$item['icon']);
                    }
                }
                if(count($news)){
                	D('RobotChatLog')->addChatLog($info,'NEWS_TYPE_CODE');
                    $wechat->response($news,Wechat::MSG_TYPE_NEWS);
                }else{
                    $wechat->replyText('木有找到相关新闻(- -!)');
                }
                break;
            }
            case self::APP_TYPE_CODE:{

                $apps = array();
                foreach($data['list'] as $item){
                        //[标题，说明，全文连接，图片链接]
                        $apps[]=array($item['name']."\n【请在'浏览器中打开'即可下载】",$item['name'],"http://mp.weixin.qq.com/mp/redirect?url=".$item['detailurl'],$item['icon']);
                }
                if(count($apps)){
                	D('RobotChatLog')->addChatLog($info,'APP_TYPE_CODE');
                    $wechat->response($apps,Wechat::MSG_TYPE_NEWS);
                }else{
                    $wechat->replyText('木有找到相关内容(- -!)');
                }
                break;
            }
            case self::TRAIN_TYPE_CODE:{

                $trains = array();
                foreach($data['list'] as $item){
                    //[标题，说明，全文连接，图片链接]
                    $trains[]=array("【{$item['trainnum']}】\n{$item['start']}=>{$item['terminal']}\n{$item['starttime']}-{$item['endtime']}",$item['trainnum'],$item['detailurl'],$item['icon']);
                }
                if(count($trains)){
                	D('RobotChatLog')->addChatLog($info,'TRAIN_TYPE_CODE');
                    $wechat->response($trains,Wechat::MSG_TYPE_NEWS);
                }else{
                    $wechat->replyText('木有找到相关列车信息(- -!)');
                }
                break;
            }
            case self::FLIGHT_TYPE_CODE:{

                $message = "参考航班信息如下:\n====================\n";
                foreach($data['list'] as $item){
                    $message.="{$item['flight']}\n[起飞时间]{$item['starttime']}\n[到达时间]{$item['endtime']}\n--------------------\n";
                }
                $message.=$this->urlWrapper("http://flight.qunar.com/status/alphlet_order.jsp?ex_track=bd_aladding_flightsk_title","了解更多");
                D('RobotChatLog')->addChatLog($info,'FLIGHT_TYPE_CODE');
                $wechat->replyText($message);
                break;
            }
            case self::INFO_TYPE_CODE:{

                $infos = array();
                foreach($data['list'] as $item){
                    //[标题，说明，全文连接，图片链接]
                    $infos[]=array($item['name'],$item['info'],$item['detailurl'],$item['icon']);
                }
                if(count($infos)){
                	D('RobotChatLog')->addChatLog($info,'INFO_TYPE_CODE');
                    $wechat->response($infos,Wechat::MSG_TYPE_NEWS);
                }else{
                    $wechat->replyText('木有找到相关内容(- -!)');
                }
                break;
            }
            case self::PRICE_TYPE_CODE:{

                $prices = array();
                foreach($data['list'] as $item){
                    //[标题，说明，全文连接，图片链接]
                    $item['icon'] = "http://t12.baidu.com/it/u=3606878177,1399277378&fm=58";
                    $prices[]=array($item['name']."\n".$item['price'],$item['name'],$item['detailurl'],$item['icon']);
                }
                if(count($prices)){
                	D('RobotChatLog')->addChatLog($info,'PRICE_TYPE_CODE');
                    $wechat->response($prices,Wechat::MSG_TYPE_NEWS);
                }else{
                    $wechat->replyText('木有找到相关内容(- -!)');
                }
                break;
            }
                default:{

                    if($this->code_list[$data['code']]){
                        $wechat->replyText($this->code_list[$data['code']]);
                    }else{
                        $r = array('唔知道!','额...不是很懂你说啥','听不懂..','不要乱说话!!!','Zzzzzz..~~','可以讲人话?','你的语文是门卫教的吗...');
                        shuffle($r);
                        D('RobotChatLog')->addChatLog($info,$r[0]);
                        $wechat->replyText($r[0]);
                    }
                }
        }
	}

    /**
     * 组装url以及文本信息，返回a标签字符串
     * @param $url
     * @param $content
     */
    private function urlWrapper($url,$content){

        $format = '<a href="%s">%s</a>';
        return sprintf($format,$url,$content);
    }

	/**
	 * 发送HTTP请求方法，目前只支持CURL发送请求
	 * @param  string $url    请求URL
	 * @param  array  $param  GET参数数组
	 * @param  array  $data   POST的数据，GET请求时该参数无效
	 * @param  string $method 请求方法GET/POST
	 * @return array          响应数据
	 */
	protected static function http($url, $param, $data = '', $method = 'GET'){
		$opts = array(
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => false,
		);
	
		/* 根据请求类型设置特定参数 */
		$opts[CURLOPT_URL] = $url . '?' . http_build_query($param);
	
		if(strtoupper($method) == 'POST'){
			$opts[CURLOPT_POST] = 1;
			$opts[CURLOPT_POSTFIELDS] = $data;
	
			if(is_string($data)){ //发送JSON数据
				$opts[CURLOPT_HTTPHEADER] = array(
						'Content-Type: application/json; charset=utf-8',
						'Content-Length: ' . strlen($data),
				);
			}
		}
	
		/* 初始化并执行curl请求 */
		$ch = curl_init();
		curl_setopt_array($ch, $opts);
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
	
		//发生错误，抛出异常
		if($error) throw new \Exception('请求发生错误：' . $error);
		return  $data;
	}
}