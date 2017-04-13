<?php
namespace Adxjl\Model;
use Think\Model;

class ArticleCommentModel extends Model {

	// 查找所有文章评论数据
	public function getAllArticleComment($p, $num) {
		return $this->limit(($p-1)*$num, $num)->order('time desc')->select();
	}
	//查出总数
	public function articleCommentCount() {
		return $this->count();
	}

}