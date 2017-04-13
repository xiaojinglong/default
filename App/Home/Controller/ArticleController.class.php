<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends CommonController {
    private $article;
    private $category;
    private $articlecomment;
    public function article(){
        $this->article=D('Article');
        $count=$this->article->articleCount();
        $num=6;
        $p=$_GET['p']?$_GET['p']:1;
        $page= new \Think\Page($count,$num);
        $show=$page->show();
        $result=$this->article->getAllArticle($p,$num);
        $this->assign('Article',$result);
        $this->assign('page',$show);
    	$this->display();
    }
    /**
     * [detail description]
     * @return [type] [description]
     */
    public  function   detail(){

    	if(IS_GET){
            $BIAOQING=C('BIAOQING');
            if($BIAOQING){
                $this->assign('BIAOQING',$BIAOQING);
            }
            $aid=I('aid',0,'intval');
            $this->article=D('Article');
            $result=$this->article->getArticleByaid($aid);
            if (!$result) {
                $this->error('文章不存在');
            }
            $this->assign('Article',$result);
            $p=1;
            $limit=5;
            $this->articlecomment=D('ArticleComment');
            $articleComment=$this->articlecomment->getArticleComment($aid,$p,$limit);
            $this->assign('articleComment',$articleComment);
    		$this->display();
    	}
    }

    /**
     * [zan description赞
     * @return [type] [description]
     */
    public  function  zan(){
        if(IS_AJAX){
            $aid=I('aid',0,'intval');
            if(!$aid){
                $this->ajaxReturn(array('msg'=>'数据异常','code'=>'2','data'=>''));
            }
            if(in_array($aid,cookie('Zanhistory'))) $this->ajaxReturn(array('msg'=>'已经赞过了','code'=>'3','data'=>''));
            if(in_array($aid,cookie('Caihistory'))) $this->ajaxReturn(array('msg'=>'已经踩过了','code'=>'3','data'=>''));
            $this->article=D('Article');
            $result=$this->article->addZan($aid);
            $Zanhistory[]=$aid;
            cookie('Zanhistory',$Zanhistory,86400); 
            if($result){
                $this->ajaxReturn(array('msg'=>'success','code'=>'1','data'=>$result));
            }else{
                $this->ajaxReturn(array('msg'=>'fail','code'=>'0','data'=>''));
            }
        }else{
            $this->error('非法访问',U('Home/Index/index'));
        }
    }

    /**
     * [cai description]踩
     * @return [type] [description]
     */
    public  function cai(){
        if(IS_AJAX){
            $aid=I('aid',0,'intval');
            if(!$aid){
                $this->ajaxReturn(array('msg'=>'数据异常','code'=>'2','data'=>''));
            }
            if(in_array($aid,cookie('Caihistory'))) $this->ajaxReturn(array('msg'=>'已经踩过了','code'=>'3','data'=>''));
            if(in_array($aid,cookie('Zanhistory'))) $this->ajaxReturn(array('msg'=>'已经赞过了','code'=>'3','data'=>''));
            $this->article=D('Article');
            $result=$this->article->addCai($aid);
            $Caihistory[]=$aid;
            cookie('Caihistory',$Caihistory,86400); 
            if($result){
                $this->ajaxReturn(array('msg'=>'success','code'=>'1','data'=>$result));
            }else{
                $this->ajaxReturn(array('msg'=>'fail','code'=>'0','data'=>''));
            }
        }else{
            $this->error('非法访问',U('Home/Index/index'));
        }
    }
    /**
     * [upimg description]
     * @return [type] [description]
     */
    public  function  upimg(){

        $base64_string = $_POST['submitData'];
        $base64_string=$base64_string['base64_string'];
        $savename = uniqid().'.jpeg';//localResizeIMG压缩后的图片都是jpeg格式
        $imgPath='./Uploads/tempdir/admin/';
        if (!file_exists($imgPath)){ 
                mkdir(iconv("UTF-8", "GBK", $imgPath),0777,true);
            } 
        $savepath = $imgPath.$savename; 
        $image =$this->base64_to_img($base64_string,$savepath );
        if($image){
            S('tempImg',$image,600);
            echo '{"status":1,"content":"上传成功","url":"'.$image.'"}';
        }else{
            echo '{"status":2,"content":"上传失败"}';
        } 
    }
    /**
     * [base64_to_img description]将base64数据解析为文件
     * @param  [type] $base64_string [description]
     * @param  [type] $output_file   [description]
     * @return [type]                [description]
     */
    public  function base64_to_img($base64_string, $output_file ) {
        $ifp = fopen( $output_file, "wb" ); 
        fwrite( $ifp, base64_decode( $base64_string) ); 
        fclose( $ifp ); 
        return( $output_file ); 
    } 
    /**
     * [comment description]评价
     * @return [type] [description]
     */
    public  function  comment(){
        if(IS_AJAX){
            $this->articlecomment=D('ArticleComment');
            $result=$this->articlecomment->addComment();
            $this->ajaxReturn($result);
        }else{
            $this->error('非法访问',U('Home/Index/index'));
        }
    }
    /**
     * [getMoreComment description]
     * @return [type] [description]
     */
    public  function getMoreComment(){
        if(IS_AJAX){
            $aid=I('aid',0,'intval');
            $p=I('p',0,'intval');
            $limit=5;
            $this->articlecomment=D('ArticleComment');
            $result=$this->articlecomment->getArticleComment($aid,$p,$limit);
            $res=array();
            if(is_array($result)){
                foreach ($result as $key => $value) {
                    $res[$key]['cid'] = $value['cid'];
                    $res[$key]['aid'] = $value['aid'];
                    $res[$key]['content'] = htmlspecialchars_decode($value['content']);
                    $res[$key]['headimg'] = $value['headimg'];
                    $res[$key]['time'] = $value['time'];
                    $res[$key]['ip'] = $value['ip'];
                }
            }
            
            if($res){
                $this->ajaxReturn(array('msg'=>'success','code'=>'1','data'=>$res));
            }else{
                $this->ajaxReturn(array('msg'=>'没有更多了','code'=>'0','data'=>''));
            }
            $this->ajaxReturn($result);
        }else{
            $this->error('非法访问',U('Home/Index/index'));
        }
    }
}