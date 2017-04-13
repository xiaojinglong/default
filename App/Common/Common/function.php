<?php 


	function   p($arr){
		echo "<pre>";
		print_r($arr);
		echo  "</pre>";
	}
	/**
	 * 获得微信操作的token
	 */
	function getaccesstoken(){
	    if(S('wx_accesstoken')){
	        return S('wx_accesstoken');
	    }
	    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.C("WX_APPID").'&secret='.C("WX_SECRET");
	    
	    $token = json_decode(file_get_contents($url),true);
	    S('wx_accesstoken',$token['access_token'],array('type'=>'file','expire'=>$token['expires_in'] - 7000)); //减去10分钟 防止accesstoken 提前失效
	    return $token['access_token'];
	}
	
	
	/*获取图片*/
	function getimgpost($url){
    	$curl = curl_init();
     	curl_setopt($curl, CURLOPT_URL, $url);
     	curl_setopt($curl, CURLOPT_HEADER, 0);
     	curl_setopt($curl, CURLOPT_NOBODY, 0);
     	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
     	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
     	if (!empty($data)){
         	curl_setopt($curl, CURLOPT_POST, 1);
         	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
     	}
     	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	// $output = curl_exec($curl);
    	$httpinfo=curl_getinfo($curl);
     	curl_close($curl);
     	return array_merge(array('header'=>$httpinfo));
 	}
    function   createmenu($data){
        if(S('wx_accesstoken')){
            $token= S('wx_accesstoken');
        }else{
            $token=getaccesstoken();
        }
        $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$token;
        $result=https_post($url,$data);
        return $result;

    }
	/*post请求*/
 	function https_post($url, $data = null){
    	$curl = curl_init();
     	curl_setopt($curl, CURLOPT_URL, $url);
     	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
     	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
     	if (!empty($data)){
         	curl_setopt($curl, CURLOPT_POST, 1);
         	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
     	}
     	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	$output = curl_exec($curl);
     	curl_close($curl);
     	return $output;
 	}

    /**
 * 邮件发送函数
 */
    function sendMail($to, $title, $content) {
     
        Vendor('PHPMailer.PHPMailerAutoload');     
        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
        $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
        $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
        $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
        $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
        $mail->AddAddress($to,"尊敬的客户");
        $mail->WordWrap = 50; //设置每行字符长度
        $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
        $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
        $mail->Subject =$title; //邮件主题
        $mail->Body = $content; //邮件内容
        $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
        return($mail->Send());
    }

 ?>