<?php
namespace Home\Controller;
use Think\Controller;
class WeichatController extends Controller {
   
   public  function index(){
	
	   if(!isset($_GET['echostr'])){
	   		$this->responseMsg();
	   }else{

	   		$this->valid();
	   }
    }
    /*验证token */
    public function valid() {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = C('WX_TOKEN');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            echo $echoStr;
            exit;
        }
    }
     public  function  createmenu(){
        $data = '{
        "button":[
        {
            "name": "爱租", 
            "type":"view",
            "url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('WX_APPID').'&redirect_uri=http://www.gswwlz.com&response_type=code&scope=snsapi_base&state=123#wechat_redirect"
        }, 
        {
            "name":"发现",
            "sub_button":[
                {
                    "type":"click",
                    "name":"关于爱租",
                    "key": "ATTENTION"
                },
                {
                    "type":"click",
                    "name":"联系我们",
                    "key": "CONNECT"
                },
                {
                    "type":"click",
                    "name":"常见问题",
                    "key": "INVITE"
                }
            ]
        },

        {
            "name":"我的",
            "sub_button":[
                
                {
                    "type":"view", 
                    "name":"推荐有奖",
                    "url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('WX_APPID').'&redirect_uri=http://www.gswwlz.com/Home/Usercharge/createqcode&response_type=code&scope=snsapi_base&state=123#wechat_redirect"
                },
                {
                    "type":"view", 
                    "name":"我的优惠券",
                    "url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('WX_APPID').'&redirect_uri=http://m.5aizu.com/Home/User/mycoupon&response_type=code&scope=snsapi_base&state=123#wechat_redirect"
                },
                {
                    "type":"view", 
                    "name":"快递查询",
                    "url":"http://m.kuaidi100.com/index_all.html"
                },
            ]
        }
    ]
}';
        $result=createmenu($data);
        if($result){
            p($result);
            echo  'success';
        }else{
            echo 'fail';
        }
    
    }
    public function responseMsg() {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)) {
            $this->logger("R " . $postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            //消息类型分离
            switch ($RX_TYPE) {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: " . $RX_TYPE;
                    break;
            }
            $this->logger("T " . $result);
            echo $result;
        } else {
            echo "";
            exit;
        }
    }

    //接收文本消息
    private function receiveText($object) {
        $keyword = trim($object->Content);
        if (strstr($keyword, "您好") || strstr($keyword, "你好") || strstr($keyword, "在吗")) {
        	$content='你好啊！';
            $result = $this->transmitText($object,$content);

        }else if(strstr($keyword,"图文")){
        	$newarr=array();
        	$newarr[]=array(
        		'Title'=>'文章',
        		'Description'=>'文章介绍',
        		'PicUrl'=>'http://img30.360buyimg.com/pop/jfs/t2221/105/1298537991/15893/ce80eaaf/5654169aN3c826d91.jpg',
        		'Url'=>'http://www.baidu.com/'
        		);
        	$newarr[]=array(
        		'Title'=>'文章',
        		'Description'=>'文章介绍',
        		'PicUrl'=>'http://img30.360buyimg.com/pop/jfs/t2221/105/1298537991/15893/ce80eaaf/5654169aN3c826d91.jpg',
        		'Url'=>'http://www.baidu.com/'
        		);
        	$newarr[]=array(
        		'Title'=>'文章',
        		'Description'=>'文章介绍',
        		'PicUrl'=>'http://img30.360buyimg.com/pop/jfs/t2221/105/1298537991/15893/ce80eaaf/5654169aN3c826d91.jpg',
        		'Url'=>'http://www.baidu.com/'
        		);
        	$newarr[]=array(
        		'Title'=>'文章',
        		'Description'=>'文章介绍',
        		'PicUrl'=>'http://img30.360buyimg.com/pop/jfs/t2221/105/1298537991/15893/ce80eaaf/5654169aN3c826d91.jpg',
        		'Url'=>'http://www.baidu.com/'
        		);
        	$result=$this->transmitPicAndText($object,$newarr);
        }else if(strstr($keyword,"音乐")){
        	$content=array(
        		'Title'=>'音乐',
        		'Description'=>'音乐',
        		'MusicUrl'=>'http://play.baidu.com/?__m=mboxCtrl.playSong&__a=247911654&__o=song/247911654',
        		'HQMusicUrl'=>'http://play.baidu.com/?__m=mboxCtrl.playSong&__a=247911654&__o=song/247911654',
        		);
        	$result=$this->transmitYinyue($object,$content);
        }
        return $result;
    }
    private function  transmitPicAndText($object,$newarr){
    	if(!is_array($newarr)){
    		return '0';
    	}
    	$item='<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>';
$items='';
foreach ($newarr as $v) {
	$items.=sprintf($item,$v['Title'],$v['Description'],$v['PicUrl'],$v['Url']);
}
    	$str="<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
$items
</Articles>
</xml> ";
$result=sprintf($str,$object->FromUserName,$object->ToUserName,time(),count($newarr));
    return $result;
    }
    //接收图片消息
    private function receiveImage($object) {
        $content = array("MediaId" => $object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    private function receiveLocation($object) {
        $content = "你发送的是位置，纬度为：" . $object->Location_X . "；经度为：" . $object->Location_Y . "；缩放级别为：" . $object->Scale . "；位置为：" . $object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object) {
        if (isset($object->Recognition) && !empty($object->Recognition)) {
            $content = "你刚才说的是：" . $object->Recognition;
            $result = $this->transmitText($object, $content);
        } else {
            $content = array("MediaId" => $object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }

        return $result;
    }

    //接收视频消息
    private function receiveVideo($object) {
        $content = array("MediaId" => $object->MediaId, "ThumbMediaId" => $object->ThumbMediaId, "Title" => "", "Description" => "");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    //接收链接消息
    private function receiveLink($object) {
        $content = "你发送的是链接，标题为：" . $object->Title . "；内容为：" . $object->Description . "；链接地址为：" . $object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //回复文本消息
    private function transmitText($object, $content) {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
    private  function  transmitYinyue($object,$content){
    	$str="<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>
</xml>";
$result=sprintf($str,$object->FromUserName, $object->ToUserName, time(),$content['Title'],$content['Description'],$content['MusicUrl'],$content['HQMusicUrl']);
   return $result;
    }
    //回复图片消息
    private function transmitImage($object, $imageArray) {
        $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray) {
        $itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray) {
        $itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图文消息
    public function transmitNews($object, $newsArray) {
        if (!is_array($newsArray)) {
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item) {
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray) {
        $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复多客服消息
    private function transmitService($object) {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //日志记录
    private function logger($log_content) {

            $max_size = 10000;
            $log_filename = "log.xml";
            if (file_exists($log_filename) and ( abs(filesize($log_filename)) > $max_size)) {
                unlink($log_filename);
            }
            file_put_contents($log_filename, date('H:i:s') . " " . $log_content . "\r\n", FILE_APPEND);
    }


}