<?php
namespace Adxjl\Controller;
class CategoryController extends CommonController {
    private $category;
    public  function   category(){
        $this->category=D('category');
        $p=I('p',1,'intval');
        $num=10;
        $count=$this->category->categoryCount();
        $page=new \Think\Page($count,25);
        $show=$page->show();
        $this->assign('page',$show);
        $category=$this->category->getAllCategory($p,$num);
        $this->assign('Category',$category);
        $this->display();
    }
    public  function  addCategory(){
        if (IS_GET) {
           $this->display();
        }
    }
    public  function  addCategoryHandle(){
        if(IS_POST){
            $data['cname']=I('cname','','htmlspecialchars');
            $data['description']=I('description','','htmlspecialchars');
            $data['sort']=I('sort',1,'intval');
            $data['isopen']=I('isopen',1,'intval');
            $this->category=D('category');
            if(!$this->category->create($data)){
                $this->ajaxReturn(array('code'=>0,'msg'=>$this->category->getError()));
            }else{
                $result=$this->category->addCategory($data);
                if($result){
                    $this->ajaxReturn(array('code'=>1,'msg'=>'success'));
                }else{
                    $this->ajaxReturn(array('code'=>2,'msg'=>'fail'));
                }
            }
        }
    }

    public  function  categoryDetail(){
        if(IS_GET){
            $cid=I('cid',0,'intval');
            $this->category=D('category');
            $category=$this->category->getCategoryBycid($cid);
            $this->assign('category',$category);
            $this->display();
        }
    }

    public  function  editCategory(){
        if(IS_POST){
            $data['cid']=I('cid',0,'intval');
            $data['cname']=I('cname','','htmlspecialchars');
            $data['description']=I('description','','htmlspecialchars');
            $data['sort']=I('sort',1,'intval');
            $data['isopen']=I('isopen',1,'intval');
            $this->category=D('category');
            if(!$this->category->create($data)){
                $this->ajaxReturn(array('code'=>0,'msg'=>$this->category->getError()));
            }else{
                $result=$this->category->editCategory($data);
                if($result){
                    $this->ajaxReturn(array('code'=>1,'msg'=>'success'));
                }else{
                    $this->ajaxReturn(array('code'=>2,'msg'=>'fail'));
                }
            }
        }
    }

    public  function  delete(){
        if(IS_GET){
            $cid=I('cid',0,'intval');
            $this->category=D('category');
            $result=$this->category->deleteCategory($cid);
            if($result){    
                $this->success('删除成功',U('Adxjl/Category/category'));
            }else{
                $this->error('删除失败',U('Adxjl/Category/category'));
            }
        }
    }
}