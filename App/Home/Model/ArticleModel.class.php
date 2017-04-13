<?php
namespace Home\Model;
use Think\Model;
class ArticleModel extends Model {
    // 查找所有文章数据
    public  function  getAllArticle($p,$num){
    	return $this->limit(($p-1)*$num,$num)->select();
    }
    //查出总数
    public function articleCount(){
    	return  $this->count();
    }
    //根据aid查询
    public  function   getArticleByaid($aid){
    	return  $this->alias('a')->where(array('a.aid'=>$aid,'c.isopen'=>1))->field(array('a.aid','c.cid'=>'ccid','c.cname','a.title','a.time','a.isshow','a.content','a.author','a.thumb','a.cid'=>'acid','a.zan','a.cai'))->join('__CATEGORY__ c ON c.cid=a.cid ')->find();
    }
    //修改文章
    public  function   editArticle($data){
    	return $info= $this->save($data);
    }
    //删除文章
    public function deleteArticle($aid){
    	return $this->where(array('aid'=>$aid))->delete();
    }
    //根据分类查找文章
    public   function   getArticleBycid($cid,$p,$num){
        return $this->alias('a')->where(array('a.cid'=>$cid,'c.isopen'=>1))->field(array('a.aid','a.title','a.content','a.thumb','c.cname','a.author','a.time'))->limit(($p-1)*$num,$num)->join('__CATEGORY__ c on c.cid=a.cid')->select();
    }
    /**
     * [addZan description]
     */
    public  function addZan($aid){
        return $this->where(array('aid'=>$aid))->setInc('zan');
    }
    /**
     * [addCai description]
     */
    public  function addCai($aid){
        return $this->where(array('aid'=>$aid))->setInc('cai');
    }
    
}