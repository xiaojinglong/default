<?php 
namespace  Adxjl\Controller;
use Think\Controller;
class CommonController extends   Controller{
	/*初始化方法*/
	public  function _initialize(){
		header("Content-type:text/html;charset=utf-8");
		if(method_exists($this, '__auto')){
			$this->__auto();
		}
		if(!session(C('USER_AUTH_KEY'))){
			$this->redirect('Adxjl/Login/login');
		}
		if(C('USER_AUTH_ON')){
			$rbac=new \Org\Util\Rbac();
			$status=$rbac->AccessDecision();
			if(!$status){
				$this->error('您没有权限!');
			}
		}
		
    }

    public  function _empty(){
    	$this->redirect('/Home/Error/index');
    }
}
 ?>