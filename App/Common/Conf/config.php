<?php
return array(
	//'配置项'=>'配置值'
	'WX_TOKEN'=>'1111111111',
	'WX_APPID'=>'1111111111',
	'WX_SECRET' =>'405b5e8b3af8ce2e50c667beec8dc9ad',
	// 'URL_MODEL' => 1,
	'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'xiaojinglong',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'xjl_',    // 数据库表前缀
   	'URL_MODEL'             => 2,
   	'MODULE_ALLOW_LIST'    =>    array('Home','Adxjl'),
	'DEFAULT_MODULE'       =>    'Home',
    'URL_HTML_SUFFIX' => 'jsp',

    // 邮件配置
    'MAIL_HOST' =>'SMTP.163.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'chen791652232@163.com',//你的邮箱名
    'MAIL_FROM' =>'chen791652232@163.com',//发件人地址
    'MAIL_FROMNAME'=>'小静龙',//发件人姓名
    'MAIL_PASSWORD' =>'chen1801',//邮箱密码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
    //memcache
    'HTML_CACHE_ON'     =>    false, // 开启静态缓存   
    'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）  
    'HTML_PATH' => '__APP__/html',
    'HTML_FILE_SUFFIX'  =>    '.html', // 设置静态缓存文件后缀
    'HTML_CACHE_RULES'  =>     array(  // 定义静态缓存规则
        'index:index'=>array('{:module}_{:controller}_{:action}',10),  // 首页静态缓存 3秒钟       
        //ajax 请求的商品列表内容在 ajaxGoodsList 函数中  S($keys,$html,300); 缓存
        'Article:detail'=>array('{:module}_{:controller}_{:action}_{aid}',10),  // 详情页静态缓存                
    ),  
    // 'DATA_CACHE_TYPE' => 'Memcache', 
    // 'MEMCACHE_HOST'   => 'tcp://127.0.0.1:11211',  
    // 'DATA_CACHE_TIME' => '3600',




);