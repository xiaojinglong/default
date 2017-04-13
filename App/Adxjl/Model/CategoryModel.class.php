<?php
namespace Adxjl\Model;
use Think\Model;
class CategoryModel extends Model {
    protected $_validate = array(
        array('cname','','分类名称不能重复',0,'unique',1),
        array('description','require','分类描述必须填写'), 
        array('isopen',array(1,2),'值的范围不正确！',2,'in'), 
        array('sort','require','排序必须填写'), 
   );
    public  function   addCategory($data){
        return $this->add($data);
    }
    // 查找所有分类数据
    public  function  getAllCategory($p,$num){
    	return $this->limit(($p-1)*$num,$num)->order('sort asc')->select();
    }
    //查出总数
    public function categoryCount(){
    	return  $this->count();
    }
    //根据cid查询
    public  function   getCategoryBycid($cid){
    	return $this->where(array('cid'=>$cid))->find();
    }
    //修改分类
    public  function   editCategory($data){
    	return $this->save($data);
    }
    //删除分类
    public function deleteCategory($cid){
    	return $this->where(array('cid'=>$cid))->delete();
    }
    //获取所有分类
    public  function  allCategory(){
        return $this->where(array('isopen'=>1))->order('sort asc')->select();
    }
    
}