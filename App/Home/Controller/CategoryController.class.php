<?php
namespace Home\Controller;
class CategoryController extends CommonController {
    public  function   cate(){
        if (IS_GET) {
            $cid=I('cid',0,'intval');
            $p=I('p',1,'intval');
            $num=2;
            $data=D('Article')->getArticleBycid($cid,$p,$num);
            if($data){
                $this->assign('article',$data);
            }
            $count=M('article')->where(array('cid'=>$cid))->count();
            $page= new \Think\Page($count,$num);
            $show=$page->show();
            $this->assign('page',$show);
            $this->display();
        }
    }
}