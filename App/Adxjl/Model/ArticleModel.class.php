<?php
namespace Adxjl\Model;
use Think\Model;

class ArticleModel extends Model {
	protected $_validate = array(
		array('aid', '', '文章编号不能重复', 0, 'unique', 1),
		array('title', 'require', '文章标题必须填写'),
		array('content', 'require', '文章内容必须填写'),
		array('cid', 'require', '分类id必须填写'),
		array('time', 'require', '发布时间必须填写'),
		array('thumb', 'require', '图片必须上传'),
		array('isshow', array(1, 2), '值的范围不正确！', 2, 'in'),
	);
	public function addArticle($data) {
		return $this->add($data);
	}
	// 查找所有文章数据
	public function getAllArticle($p, $num) {
		return $this->limit(($p-1)*$num, $num)->order('time desc')->select();
	}
	//查出总数
	public function articleCount() {
		return $this->count();
	}
	//根据aid查询
	public function getArticleByaid($aid) {
		return $this->alias('a')->where(array('a.aid' => $aid, 'c.isopen' => 1))->field(array('a.aid', 'c.cid' => 'ccid', 'c.cname', 'a.title', 'a.isshow', 'a.content', 'a.author', 'a.thumb', 'a.cid' => 'acid'))->join('__CATEGORY__ c ON c.cid=a.cid ')->find();
	}
	//修改文章
	public function editArticle($data) {
		return $info = $this->save($data);
	}
	//删除文章
	public function deleteArticle($aid) {
		return $this->where(array('aid' => $aid))->delete();
	}
	//根据分类查找文章
	public function getArticleBycid($cid, $p, $num) {
		return $this->where(array('cid' => $cid))->limit(($p-1)*$num, $num)->select();
	}

}