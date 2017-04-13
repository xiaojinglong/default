<?php
namespace Home\Model;
use Think\Model;
class ArticleCommentModel extends Model {
    // 查找所有文章
    public  function  getArticleComment($aid,$p,$num){
    	return $this->where(array('aid'=>$aid))->limit(($p-1)*$num,$num)->order('time desc')->select();
    }
    public  function  addComment(){
        $data = array();
        $data['aid'] = I('aid',0,'intval');
        $data['content'] = I('content','','htmlspecialchars');
        if(!$data['aid']||!$data['content']) return array('msg' => '数据异常','code' => '4','data' => '');
        if(strlen($content)>255)  return array('msg' => '长度超过限制','code' => '3','data' => '');
        $data['time'] = time();
        $data['ip'] = get_client_ip();
        $data['headimg']='./Public/headimg/'.rand(1,6).'.gif';
        $result = $this->add($data);
        if($result){
            return array('msg'=>'发布成功','code'=>'1','data'=>'1','headimg'=>$data['headimg']);
        }else{
            return array('msg'=>'发布失败','code'=>'0','data'=>'');
        }
    }
   
}