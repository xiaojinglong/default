<?php
namespace Home\Controller;
use Think\Controller;
class QQloginController extends Controller {
    public function  __construct(){
      import('Common.Lib.Connect.qqConnectAPI', '', '.php');
    }
   
   public  function handle(){
   		$code=$_GET['code'];
    	//访问Qq登录页面
    	$oauth=new \Oauth();
    	$accesstoken=$oauth->qq_callback();
    	$openid=$oauth->get_openid();
   		$qc=new \QC($accesstoken,$openid);
   		$userinfo=$qc->get_user_info();
      if($userinfo){
          $data['name']=$userinfo['nickname'];
          $data['openid']=$openid;
          $addUser=D('Member')->addUser($data);
          session('userqqinfo',$userinfo);
          header('Location: http://www.doadc.com/');
      }else{
          $this->QQlogin();
      }
       
    }
    public  function  QQlogin(){
    	//访问Qq登录页面
    	$oauth=new \Oauth();
    	$oauth->qq_login();
    }

    public  function   loginout(){
      session('userqqinfo',null);
      $this->redirect('/Home/Index/index');
    }
}