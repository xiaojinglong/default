<?php
namespace Adxjl\Controller;
use Think\Controller;

class CommentController extends CommonController {
	private $article_comment;
	public function __construct() {
		parent::__construct();
		$this->article_comment = D('ArticleComment');

	}
	public function commentList() {
		$count  = $this->article->articleCommentCount();
		$num    = 6;
		$p      = $_GET['p']?$_GET['p']:1;
		$page   = new \Think\Page($count, $num);
		$show   = $page->show();
		$result = $this->article->getAllArticleComment($p, $num);
		$this->assign('ArticleComment', $result);
		$this->assign('page', $show);
		$this->display();
	}
}
?>