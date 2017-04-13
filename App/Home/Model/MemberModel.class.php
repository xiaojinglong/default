<?php 
namespace   Home\Model;
use Think\Model;
class  MemberModel extends Model{
	private $map=array();
	public  function  addUser($data){
		if(!$this->findOpenid($data['openid'])){
			return $this->add($data);
		}
	}
	public  function  findOpenid($openid){
		$map['openid']=$openid;
		return  $this->where($map)->find();
	}
	
}
 ?>
