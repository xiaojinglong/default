<?php
namespace Adxjl\Model;
use Think\Model;
class AdminsModel extends Model {
    public  function  getRelationInfo(){
    	$data=M('admins')->field(array('uid','uname','status'))->select();
    	$info=array();
    	foreach ($data as $k => $v) {
            $sql='select r.id as rid,r.name as rname,r.status as rstatus from xjl_role_user as ru  left join xjl_role as r on r.id=ru.role_id where ru.user_id='.$v['uid'];
            $info[$k]['uid']=$v['uid'];
            $info[$k]['uname']=$v['uname'];
             $info[$k]['status']=$v['status'];
            $info[$k]['role']=$this->query($sql);
        }
        return  $info;
    }
}