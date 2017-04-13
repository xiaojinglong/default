<?php
namespace Home\Controller;
class NewIndexController extends CommonController {
	private $article;
	private $mem;
	private $redis;
	public function __construct() {
		parent::__construct();
		$this->mem = new \Memcache();
		$this->mem->connect('127.0.0.1', 11211);
		$this->redis = new \Redis();
		$this->redis->connect('127.0.0.1', 6379);
		$this->redis->auth('Chen1801');
	}
	public function index() {

		$this->display();

	}

}