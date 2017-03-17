<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
//        $url = I('get.');
//		$url['url'] = implode('-',$url);
//        dump($url);
//        $a = D('Weixin');
//        $str = $a->addurl($url);
//        echo $str;
//        die;
       // 1.讲timestamp，nonce，token按字典排序
		$timestamp 	= $_GET['timestamp'];
		$nonce		= $_GET['nonce'];
		$token		= 'dacaozhuang';
		$signature	= $_GET['signature'];
        $echostr    = $_GET['echostr'];
		$array 		= array($timestamp,$nonce,$token);
//		sort($array);
		// 2.将排序后的三个参数拼接之后用sha1加密
		$tmpstr 	= implode($array);//join
        $tmpstr		= sha1($tmpstr);
		// 3.将加密后的字符串与signature进行对比，判断该请求是否是来自微信
		if($tmpstr == $signature and $echostr){
//            header('content-type:text');
			echo $echostr;
			exit;
		}else{
			$this->reponsemsg();
		}
    }
    // 接受时间推送并回复
    public function reponsemsg(){
    	// 1.获取到微信推送过来的post数据（xml格式）
//    	$postarr = $GLOBALS['HTTP_RAW_POST_DATA'];
    	$postarr = file_get_contents('php://input');
    	//2.处理获取到的数据
		// <xml>
		// <ToUserName><![CDATA[toUser]]></ToUserName>
		// <FromUserName><![CDATA[FromUser]]></FromUserName>
		// <CreateTime>123456789</CreateTime>
		// <MsgType><![CDATA[event]]></MsgType>
		// <Event><![CDATA[subscribe]]></Event>
		// </xml>
    	$postobj = simplexml_load_string($postarr);
    	// $postobj->ToUserName = '';
    	// $postobj->FromUserName = '';
    	// $postobj->CreateTime = '';
    	// $postobj->MsgType = '';
    	// $postobj->Event = '';
    	if(strtolower($postobj->MsgType) == 'event'){
    		// 是否是关注
    		if (strtolower($postobj->Event) == 'subscribe') {
    			//回复用户消息
    			$toUser 	= $postobj->FromUserName;
    			$fromUser 	= $postobj->ToUserName;
    			$time		= time();
    			$MsgType	= 'text';
    			$content	= '欢迎加入大家庭';
    			$template = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Content><![CDATA[%s]]></Content></xml>';
				$info = sprintf($template,$toUser,$fromUser,$time,$MsgType,$content);
				echo $info;
    		}
    	}
    }
}