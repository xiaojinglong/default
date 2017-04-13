<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    public  function _initialize(){
		header("Content-type:text/html;charset=utf-8");
		if(method_exists($this, '__auto')){
			$this->__auto();
		}
		$category=M('Category')->where(array('isopen'=>1))->order('sort asc')->select();
        $this->assign('category',$category);
         $userinfo=session('userqqinfo');
        if($userinfo){
            $this->assign('username',$userinfo['nickname']);
            $this->assign('userlogo',$userinfo['figureurl']);
        }
	}

	public  function _empty(){
    	$this->redirect('/Home/Error/index');
    }
    /**
     * [uploadFile description]公共上传
     * @param  [type] $savepath [description]
     * @return [type]           [description]
     */
    public  function  uploadFile($savepath){
    	$config = array(
		    'maxSize'    =>    3145728,
		    'rootPath'   =>    $savepath,
		    'savePath'   =>    '',
		    'saveName'   =>   uniqid(),
		    'exts'       =>    array('jpg', 'gif', 'png', 'jpeg','pdf','word','pptx','doc','docx','xlsx'),
		    'autoSub'    =>    false,//自动使用子目录保存上传文件 默认为true
		       
    	);
		$upload = new \Think\Upload($config);// 实例化上传类
		$info=$upload->upload();
		if(!$info) {// 上传错误提示错误信息
		  	echo  json_encode(array('msg'=>$upload->getError(),'code'=>0));
		}else{
			echo  json_encode(array('msg'=>$info['file']['name'],'code'=>1));
		}
    }
   /**
    * [imgThumb description]
    * @param  [type]  $path     [description]
    * @param  [type]  $savepath [description]
    * @param  integer $size     [description] 按比率裁剪给一个固定的大小算比率
    * @return [type]            [description]
    */
  	function   imgThumb($path,$savepath,$size=100){
  		$info=getimagesize($path);
  		if($info[1]>$size){
  			$rate=$info[1]/$size;
  			$image = new \Think\Image(); 
			$image->open($path);
			$ext=substr($path,strrpos($path,'.'));
			$newext=str_replace('.','._thumb',$ext);
			$thumb=$image->thumb($info[0]/$size,$info[1]/$size,3);
			$newsavepath=$savepath.$newext;
			$info=$thumb->save($newsavepath,'',100);
			if($info){
				return  $newsavepath;
			}else{
				return false;
			}
  		}
   }
   /**
    * [ismobile description]判断设备是否是手机
    * @return [type] [description]
    */
   	function ismobile() {
	    static $ismobile;
	    if(isset($ismobile))  return $ismobile;
	    if(empty($_SERVER['HTTP_USER_AGENT'])){
	        $ismobile = false;
	    }else if (strpos($_SERVER['HTTP_USER_AGENT'],'Mobile')!==false||strpos($_SERVER['HTTP_USER_AGENT'],'Android')!==false||strpos($_SERVER['HTTP_USER_AGENT'],'Silk/')!== false||strpos($_SERVER['HTTP_USER_AGENT'],'Kindle')!== false||strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry')!==false||strpos($_SERVER['HTTP_USER_AGENT'],'Opera Mini')!== false||strpos($_SERVER['HTTP_USER_AGENT'],'Opera Mobi')!==false){
	        $ismobile = true;
	    }else{
	        $ismobile = false;
	    }
	    return $ismobile;
	}

   
}