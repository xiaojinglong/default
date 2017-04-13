<?php
namespace Adxjl\Controller;
use Think\Controller;
use Think\Verify;
class LoginController extends Controller {
	public function code(){
		$config=array(
			'codeSet'   =>  '1234567890',             // 验证码字符集合
	        'expire'    =>  1800,            // 验证码过期时间（s）
	        'fontSize'  =>  16,              // 验证码字体大小(px)
	        'useCurve'  =>  false,            // 是否画混淆曲线
	        'useNoise'  =>  false,            // 是否添加杂点	
	        'imageH'    =>  40,               // 验证码图片高度
	        'imageW'    => 110,               // 验证码图片宽度
	        'length'    =>  4,               // 验证码位数
	        'bg'        =>  array(152, 251, 132),
			);
		$verify=new Verify($config);
		$code=$verify->entry();
	}
	/**
	 * [login description]
	 * @return [type] [description]
	 */
    public function login(){
    	$this->display();
    }
    /**
     * [handle description] 
     * @return [type] [description]
     */
    public  function  handle(){
    	if (IS_POST) {
    		$username=trim(I('username','','htmlspecialchars'));
    		$password=trim(I('password','','htmlspecialchars'));
    		$verify=new Verify();
			if(!$verify->check(I('verify','','htmlspecialchars'))){
				$this->ajaxReturn(array('msg'=>'验证码错误','code'=>'2'));
			}
			$result=M('admins')->where(array('uname'=>$username))->find();
			if($result){
				if(md5($password.$result['randnum'])==$result['password']){
					session('uname',$result['uname']);
					session('uid',$result['uid']);
					if($result['uname']==C('RBAC_SUPERADMIN')){
						session('ADMIN_AUTH_KEY',true);
					}
					$rbac=new \Org\Util\Rbac();
					$rbac->saveAccessList();
					$this->ajaxReturn(array('msg'=>'登录成功','code'=>'1'));
				}else{
					$this->ajaxReturn(array('msg'=>'密码错误','code'=>'3'));
				}
			}else{
				$this->ajaxReturn(array('msg'=>'用户名错误','code'=>'4'));
			}

    	}else{
    		$this->error('页面不存在',U('Adxjl/Login/login'));
    	}
    }

    public  function  loginout(){
    	session('uid',null);
    	session('uname',null);
    	$this->redirect('/Adxjl/Login/login');
    }
}