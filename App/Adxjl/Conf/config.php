<?php
return array(
	//'配置项'=>'配置值'
	'RBAC_SUPERADMIN'=>'xjladmin',  //超级管理员名称
	'ADMIN_AUTH_KEY'=>'superadmin', //超级管理员标示
	'USER_AUTH_ON'=>true, //是否开启验证
	'USER_AUTH_TYPE'=>1, //验证类型   1、登录验证  2、实时验证
	'USER_AUTH_KEY'=>'uid',   //用户识别号
	'NOT_AUTH_MODULE'=>'',
	'NOT_AUTH_ACTION'=>'welcome',
	'RBAC_ROLE_TABLE'=>'xjl_role',
	'RBAC_USER_TABLE'=>'xjl_role_user',
	'RBAC_ACCESS_TABLE'=>'xjl_access',
	'RBAC_NODE_TABLE'  =>'xjl_node',

	// USER_AUTH_ON 是否需要认证
// USER_AUTH_TYPE 认证类型
// USER_AUTH_KEY 认证识别号
// REQUIRE_AUTH_MODULE  需要认证模块
// NOT_AUTH_MODULE 无需认证模块
// USER_AUTH_GATEWAY 认证网关
// RBAC_DB_DSN  数据库连接DSN
// RBAC_ROLE_TABLE 角色表名称
// RBAC_USER_TABLE 用户表名称
// RBAC_ACCESS_TABLE 权限表名称
// RBAC_NODE_TABLE 节点表名称

);