<?php
namespace Adxjl\Controller;
class RbacController extends CommonController {
    //用户列表
    public  function    user(){
        $userInfo=D('Admins')->getRelationInfo();
        $this->assign('user',$userInfo);
        $this->display();
    }
    //添加用户
    public  function   addUser(){
        if(IS_GET){
            $role=M('role')->select();
            $this->assign('role',$role);
            $this->display();
        }
        if(IS_POST){
            $data['uname']=I('uname','0','htmlspecialchars');
            $data['randnum']=1234;
            $data['password']=Md5(I('password','123456','htmlspecialchars').$data['randnum']);
            $data['status']=I('status',1,'intval');
            $role_id=I('role_id','','htmlspecialchars');
            $role_id=explode('_', $role_id);
            array_pop($role_id);
            if (!$data['uname']||!$data['password']||!$data['status']) {
                $this->ajaxReturn(array('code'=>'2','msg'=>'数据输入异常'));
            }
            $result=M('admins')->add($data);
            $role_user=M('role_user');
            if($result){
                //组装角色用户关联数据
                $info=array();
                foreach ($role_id as $k => $v) {
                    $info[$k]['user_id']=$result;
                    $info[$k]['role_id']=$v;
                }
                $rs=$role_user->addAll($info);
                if($rs){
                    $this->ajaxReturn(array('code'=>'1','msg'=>'success'));
                }else{
                    $this->ajaxReturn(array('code'=>'0','msg'=>'fail'));
                }
                
            }else{
                $this->ajaxReturn(array('code'=>'0','msg'=>'fail'));
            }
        }
    }
    // 节点列表
    public  function    node(){
        $node=M('node')->order('sort asc')->select();
        $node=nodeMerge($node);
        $this->assign('node',$node);
        $this->display();
    }
    // 添加节点
    public function     addNode(){
        if(IS_GET){
            $pid=$_GET['pid'] ? $_GET['pid'] :0;
            $level=$_GET['level'] ? $_GET['level'] :1;
            switch ($level) {
                case 1:
                    $type='应用';
                    break;
                case 2:
                    $type='控制器';
                    break;
                case 3:
                    $type='方法';
                    break;
                default:
                    $type='应用';
                    break;
            }
            $this->assign('type',$type);
            $this->assign('pid',$pid);
            $this->assign('level',$level);
            $this->display();
        }
        if(IS_POST){
            $data['name']=trim(I('name','','htmlspecialchars'));
            $data['remark']=I('remark','','htmlspecialchars');
            $data['status']=I('status',1,'intval');
            $data['title']=I('title','','htmlspecialchars');
            $data['sort']=I('sort','1','htmlspecialchars');
            $data['level']=I('level',1,'intval');
            $data['pid']=I('pid',0,'intval');
            if(!$data['name']&&!$data['remark']&&!$data['title']){
                $this->ajaxReturn(array('code'=>'2','msg'=>'输入数据有误!'));
            }
            $result=M('node')->add($data);
            if($result){
                $this->ajaxReturn(array('code'=>'1','msg'=>'success'));
            }else{
                $this->ajaxReturn(array('code'=>'0','msg'=>'添加失败'));
            }
        }
    }
    // 角色列表
    public  function    role(){
        $role=M('role')->select();
        $this->assign('role',$role);
        $this->display();
    }
    // 添加角色
    public  function    addRole(){
        if (IS_POST) {
            $data['name']=I('name','0','htmlspecialchars');
            $data['remark']=I('remark','0','htmlspecialchars');
            $data['status']=I('status',1,'intval');
            if (!$data['name']||!$data['remark']||!$data['status']) {
                $this->ajaxReturn(array('code'=>'2','msg'=>'数据输入异常'));
            }
            $result=M('role')->add($data);
            if($result){
                $this->ajaxReturn(array('code'=>'1','msg'=>'success'));
            }else{
                $this->ajaxReturn(array('code'=>'0','msg'=>'fail'));
            }
        }
        if(IS_GET){
            $this->display();
        }
    }
    public  function  access(){
        if (IS_GET) {
            $rid=I('rid',0,'intval');
            $node=M('node')->order('sort asc')->select();
            $access=M('access')->where(array('role_id'=>$rid))->getField('node_id',true);
            $node=nodeMerge($node,$access);
            $this->assign('node',$node);
            $this->assign('rid',$rid);
            $this->display();
        }
    }
    public  function  accessControl(){
        if(IS_POST){
            $rid=I('rid',0,'intval');
            $access=M('access');
            $status=$access->where(array('role_id'=>$rid))->delete();
            $data=array();
            foreach ($_POST['access'] as $k => $v) {
                $tem=explode('_', $v);
                $data[]=array(
                    'role_id'=>$rid,
                    'node_id'=>$tem[0],
                    'level'=>$tem[1]
                );
            }
            $result=$access->addAll($data);
           if ($result) {
               $this->success('成功',U('Adxjl/Rbac/role'));
           }else{
                $this->error('失败',U('Adxjl/Rbac/role'));
           }
        }
    }
}