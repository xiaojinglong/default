<?php
namespace Adxjl\Controller;
class ArticleController extends CommonController {
	private $article;
	private $category;
	public function article() {
		$this->article = D('Article');
		$count         = $this->article->articleCount();
		$num           = 6;
		$p             = $_GET['p']?$_GET['p']:1;
		$page          = new \Think\Page($count, $num);
		$show          = $page->show();
		$result        = $this->article->getAllArticle($p, $num);
		$this->assign('Article', $result);
		$this->assign('page', $show);
		$this->display();
	}
	public function addArticle() {
		if (IS_GET) {
			$this->category = D('Category');
			$result         = $this->category->allCategory();
			$this->assign('category', $result);
			$this->display();
		}
	}
	public function addArticleHandle() {
		if (IS_POST) {
			$this->article   = D('Article');
			$data['title']   = I('title', '', 'htmlspecialchars');
			$data['content'] = I('content', '', 'htmlspecialchars');
			$data['isshow']  = I('isshow', 1, 'intval');
			$data['cid']     = I('cid', 1, 'intval');
			$data['author']  = I('author', '', 'htmlspecialchars');
			$data['time']    = time();
			$picurl          = I('thumb', '', 'htmlspecialchars');
			$newpath = './Uploads/article/'.date('Ymd', time());
			$thumbnewurl = $newpath.substr($picurl, strrpos($picurl, '/'));

			$bigimgurl =  substr($picurl,0,strrpos($picurl, '/')).substr($picurl,0,substr($picurl, strrpos($picurl, '/'))).str_replace('_thumb.','.',substr($picurl, strrpos($picurl, '/')));
					
			$bigimgnewurl = $newpath.str_replace('_thumb.','.',substr($picurl, strrpos($picurl, '/')));

			if ($picurl) {
				if(!file_exists($thumbnewurl)){
					if (!file_exists($newpath)) {
						mkdir(iconv("UTF-8", "GBK", $newpath), 0777, true);
					}
					$r =copy($picurl, $thumbnewurl);
					$res = copy($bigimgurl, $bigimgnewurl);
					if($r&&$res){
						$data['thumb'] = $thumbnewurl;
					}
					unlink($picurl);
					unlink($bigimgurl);
					
				}
			} else {
				$this->ajaxReturn(array('code' => '5', 'msg' => '请上传图片!'));
			}

			if (!$this->article->create($data)) {
				$this->ajaxReturn(array('code' => '2', 'msg' => $this->article->getError()));
			} else {

				$result = $this->article->addArticle($data);
				if ($result) {
					$this->ajaxReturn(array('code' => '1', 'msg' => '添加成功!'));
				} else {
					$this->ajaxReturn(array('code' => '3', 'msg' => '添加失败!'));
				}
			}

		} else {
			$this->error('页面不存在!', U('Adxjl/Index/index'));
		}
	}
	public function articleDetail() {
		if (IS_GET) {
			$aid            = I('aid', 0, 'intval');
			$this->article  = D('Article');
			$result         = $this->article->getArticleByaid($aid);
			$this->category = D('Category');
			$results        = $this->category->allCategory();
			$this->assign('category', $results);
			$this->assign('articleInfo', $result);
			$this->display();
		}
	}
	//编辑文章
	public function editArticle() {
		if (IS_POST) {
			$this->article   = D('Article');
			$data['aid']     = I('aid', 0, 'intval');
			$data['title']   = I('title', '', 'htmlspecialchars');
			$data['content'] = I('content', '', 'htmlspecialchars');
			$data['isshow']  = I('isshow', 1, 'intval');
			$data['cid']     = I('cid', 1, 'intval');
			$data['author']  = I('author', '', 'htmlspecialchars');
			$data['time']    = time();
			$picurl          = I('thumb', '', 'htmlspecialchars');
			$newpath = './Uploads/article/'.date('Ymd', time());
			$thumbnewurl = $newpath.substr($picurl, strrpos($picurl, '/'));

			$bigimgurl =  substr($picurl,0,strrpos($picurl, '/')).substr($picurl,0,substr($picurl, strrpos($picurl, '/'))).str_replace('_thumb.','.',substr($picurl, strrpos($picurl, '/')));
					
			$bigimgnewurl = $newpath.str_replace('_thumb.','.',substr($picurl, strrpos($picurl, '/')));

			if ($picurl) {
				if(!file_exists($thumbnewurl)){
					if (!file_exists($newpath)) {
						mkdir(iconv("UTF-8", "GBK", $newpath), 0777, true);
					}
					$r =copy($picurl, $thumbnewurl);
					$res = copy($bigimgurl, $bigimgnewurl);
					if($r&&$res){
						$data['thumb'] = $thumbnewurl;
					}
					unlink($picurl);
					unlink($bigimgurl);
					
				}
			} else {
				$this->ajaxReturn(array('code' => '5', 'msg' => '请上传图片!'));
			}
			if (!$this->article->create($data)) {
				$this->ajaxReturn(array('code' => '2', 'msg' => $this->article->getError()));
			} else {
				$result = $this->article->editArticle($data);
				if ($result) {
					$this->ajaxReturn(array('code' => '1', 'msg' => '修改成功!'));
				} else {
					$this->ajaxReturn(array('code' => '3', 'msg' => '修改失败!'));
				}
			}
		} else {
			$this->error('页面不存在!', U('Adxjl/Index/index'));
		}
	}
	//删除文章
	public function delete() {
		if (IS_GET) {
			$aid           = I('aid', '', 'intval');
			$this->article = D('Article');
			$result        = $this->article->deleteArticle($aid);
			if ($result) {
				$this->success('删除成功', U('Adxjl/Article/article'));
			} else {
				$this->error('操作失败', U('Adxjl/Article/article'));
			}
		}
	}

	public function upimg() {

		$base64_string = $_POST['submitData'];
		$base64_string = $base64_string['base64_string'];
		$savename      = uniqid().'.jpeg';//localResizeIMG压缩后的图片都是jpeg格式
		$imgPath       = './Uploads/tempdir/admin/';
		if (!file_exists($imgPath)) {
			mkdir(iconv("UTF-8", "GBK", $imgPath), 0777, true);
		}
		$savepath = $imgPath.$savename;
		$image    = $this->base64_to_img($base64_string, $savepath);
		if ($image) {
			$thumb=$this->imgcrup($image,$savename,$imgPath);
			echo '{"status":1,"content":"上传成功","url":"'.$thumb.'"}';die;
		} else {
			echo '{"status":2,"content":"上传失败"}';die;
		}
	}
	/**
	 * [base64_to_img description]将base64数据解析为文件
	 * @param  [type] $base64_string [description]
	 * @param  [type] $output_file   [description]
	 * @return [type]                [description]
	 */
	public function base64_to_img($base64_string, $output_file) {
		$ifp = fopen($output_file, "wb");
		fwrite($ifp, base64_decode($base64_string));
		fclose($ifp);
		return ($output_file);
	}
	// 裁剪图片
	public function imgcrup($savepath, $savename, $imgPath) {
		$image = new \Think\Image();
		$image->open($savepath);
		$name        = str_replace('.', '_thumb.', $savename);
		$thumb       = $image->thumb(200, 200, 3);
		$newsavepath = $imgPath.$name;
		$info        = $thumb->save($newsavepath);
		if ($info) {
			return $newsavepath;
		} else {
			return false;
		}
	}

}