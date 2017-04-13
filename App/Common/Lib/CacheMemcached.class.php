<?php
Class CacheMemcached extends Cache{
	
	public function __construct($options=array()){
		if ( !extension_loaded('memcached') ) {
            throw_exception(L('_NOT_SUPPERT_').':memcached');
        }
        
		$servers = array();
		
		$persistent_id = C('PERSISTENTID') ? C('PERSISTENTID') : $options['persistent_id'];

		$this->options = $options;
		$this->options['expire'] = isset($options['expire']) ? $options['expire'] : C('DATA_CACHE_TIME');
        $this->options['prefix'] = isset($options['prefix']) ? $options['prefix'] : C('DATA_CACHE_PREFIX');        
        
		
		$host = C('MEMCACHED_HOST') ? C('MEMCACHED_HOST') : '127.0.0.1';
		$port = C('MEMCACHED_PORT') ? C('MEMCACHED_PORT') : 11211;
		$weight = C('MEMECACHED_WEIGHT') ? C('MEMECACHED_WEIGHT') : 0;
		$weight_is_arr = is_array($weight);
		
		
		if(is_array($host)){
			if(!is_array($port)){
				throw_exception('Memcached服务器IP和端口号要一一对应');
			}
			if($weight_is_arr && count($weight) != count($host)){
				throw_exception('Memcached服务器IP和权重值要一一对应');
			}
			foreach ($host as $key => $value) {
				$servers[] = array($value,$port[$key],$weight_is_arr ? $weight[$key] : $weight);
			}
		}else{
			if(is_array($port)){
				foreach ($port as $key => $value) {
					$servers[] = array($host,$value,$weight_is_arr ? $weight[$key] : $weight);
				}				
			}else{
				$servers[] = array($host,$port,$weight_is_arr ? $weight[0] : $weight);
			}
		}
		
		$this->handler = new Memcached($persistent_id);
		$this->handler->addServers($servers);
        
        // $serverList = $this->handler->getServerList();
        // p($serverList);
	}
	
	/**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        N('cache_read',1);
        return $this->handler->get($this->options['prefix'].$name);
    }
    
    /**
     * 读取多个元素值
     * @param  array  $keys [要读取的元素key数组]
     * @return [boolean]       [返回值]
     */
    public function getMulti($keys=array()){
        if(empty($keys)) return '';
        N('cache_read',1);
        return $this->handler->getMulti($keys);
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolen
     */
    public function set($name, $value, $expire = 0) {
        N('cache_write',1);
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        $name   =   $this->options['prefix'].$name;
        if($this->handler->set($name, $value, $expire)) {
            return true;
        }
        return false;
    }
    
    /**
     * 写入多个元素
     * @param [array] $items  [要写入的健/值元素数组]
     * @param [int] $expire [有效时间(秒)]
     */
    public function setMulti($items,$expire=0){
        N('cache_write',1);
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        $name   =   $this->options['prefix'].$name;
        
        if($this->handler->setMulti($items, $expire)) {
            return true;
        }
        return false;
    }
    
    /**
     * 添加一个元素，如果这个元素已经存在，则失败
     * @param [string]  $key    [要添加元素的key]
     * @param [string]  $value  [要添加元素的值]
     * @param integer $expire [有效时间]
     * @return boolean
     */
    public function add($key,$value,$expire=0){
        N('cache_write',1);
        return $this->handler->add($key,$value,$expire);
    }
    
    /**
     * 替换已存在key下的元素,如果不存在此key则失败
     * @param  [string]  $key    [要替换的key]
     * @param  [string]  $value  [要替换的值]
     * @param  integer $expire [有效时间]
     * @return [boolean]          
     */
    public function replace($key,$value,$expire=0){
        N('cache_write',1);
        return $this->handler->replace($key,$value,$expire);
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolen
     */
    public function rm($name) {
        $name   =   $this->options['prefix'].$name;
        return $this->handler->delete($name);
    }

    /**
     * 清除缓存
     * @access public
     * @return boolen
     */
    public function clear() {
        return $this->handler->flush();
    }
}
