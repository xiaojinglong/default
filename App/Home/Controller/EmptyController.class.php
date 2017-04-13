<?php 
namespace Home\Controller;
use Think\Controller;
class EmptyController extends CommonController {

	public function _empty(){
		$this->redirect('/Home/Error/index');
	}

}


 ?>