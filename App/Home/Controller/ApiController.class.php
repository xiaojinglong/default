<?php
namespace Home\Controller;
use Think\Controller;
class ApiController extends CommonController {
    private $article;
    public function article(){
        if(IS_GET){
            $this->article=D('Article');
            $count=$this->article->articleCount();
            $num=I('num',5,'intval');
            $p=I('p',1,'intval');
            $page= new \Think\Page($count,$num);
            $result=$this->article->getAllArticle($p,$num);
            $info=array();
            foreach ($result as $k => $v) {
                $info[$k]['aid']=$v['aid'];
                $info[$k]['title']=$v['title'];
                $info[$k]['content']=htmlspecialchars_decode(str_replace('/Uploads','http://www.doadc.com/Uploads',$v['content']));
                $info[$k]['cid']=$v['cid'];
                $info[$k]['author']=$v['author'];
                $info[$k]['time']=$v['time'];
                $info[$k]['thumb']=htmlspecialchars_decode($v['thumb']);

            }
            // echo json_encode(array('Article'=>$result,'page'=>$page));
            $this->ajaxReturn(array('Article'=>$info,'page'=>$page));
        }
    }
}