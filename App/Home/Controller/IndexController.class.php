<?php
namespace Home\Controller;
class IndexController extends CommonController {
    private $article;
    private $mem;
    private $redis;
    public   function  __construct(){
        parent::__construct();
        $this->mem = new \Memcache();
        $this->mem->connect('127.0.0.1', 11211);
        $this->redis = new \Redis();
		$this->redis->connect('127.0.0.1',6379);
		$this->redis->auth('11111');
    }
    public  function index(){
        $num=5;
        $p=I('p',1,'intval');
        $this->article=D('Article');
        $count=$this->article->articleCount();
         $page= new \Think\Page($count,$num);
        $show=$page->show();
        if($this->mem->get('article'.$p)){
            $result=$this->mem->get('article'.$p);
        }else{
            $result=$this->article->getAllArticle($p,$num);
            $re=$this->mem->add('article'.$p,json_encode($result),MEMCACHE_COMPRESSED,600); 
        }
        if($this->redis->get('article'.$p)){
            $resultRedis=$this->redis->get('article'.$p);
        }else{
            $resultRedis=$this->article->getAllArticle($p,$num);
            $re=$this->redis->set('article'.$p,json_encode($resultRedis));
            if(!$re){
                if(!$this->mem->get('article')){
                    $this->mem->add('article',$resultRedis ,MEMCACHE_COMPRESSED,600);
                }else{
                    $resultRedis=$this->mem->get('article');
                }
                
            }
            
        }
       
       
        //第一次本来就是数组
        if(is_array($result)){
        	$this->assign('Article',$result);
        }else{
        	$this->assign('Article',json_decode($result,true));
        }
        
        $this->assign('page',$show);
        $this->display();
  
    }
    public   function  getNewArticle(){
    	if(IS_GET){
    		
            if($this->mem->get('newArticle')){
                $article=$this->mem->get('newArticle');
          
            }else{
                $article=M('article')->limit(5)->order('time desc')->select();
                $res=$this->mem->add('newArticle',json_encode($article),MEMCACHE_COMPRESSED,600);

            }
    		if(is_array($article)){
    			$this->ajaxReturn(array('msg'=>'success','code'=>'1','data'=>$article));
    		}else{
    			$this->ajaxReturn(array('msg'=>'success','code'=>'1','data'=>json_decode($article,true)));
    		}
    	}
    }
    public   function  getClickArticle(){
    	if(IS_GET){
    		
            if($this->mem->get('clickArticle')){
                $article=$this->mem->get('clickArticle');
            }else{
                $article=M('article')->limit(5)->order('click desc')->select();
                $this->mem->add('clickArticle',json_encode($article),MEMCACHE_COMPRESSED,600);
            }
    		if(is_array($article)){
    			$this->ajaxReturn(array('msg'=>'success','code'=>'1','data'=>$article));
    		}else{
    			$this->ajaxReturn(array('msg'=>'success','code'=>'1','data'=>json_decode($article,true)));
    		}
    	}
    }
    public  function  header(){
        $this->display();
    }

    public   function  bottom(){
        $this->display();
    }

    public function  mobile(){
    	$this->display();
    }

    public  function  right(){
        $this->display();
    }
   
}