<?php
return array(
/*
		 * 身份验证
		 */
		'ADMINPAGE'=>20,//后台分页数量
		'USER_AUTH_ON'=>true,
		'USER_AUTH_TYPE'=> 2,		// 默认认证类型 1 登录认证 2 实时认证
		'USER_AUTH_KEY'             =>  'authid',	// 用户认证SESSION标记
		
		'ADMIN_AUTH_KEY'			=>  'wifiadmin',
		
		'USER_AUTH_MODEL'           =>  'admin',	// 默认验证数据表模型
		
		'AUTH_PWD_ENCODER'          =>  'md5',	// 用户认证密码加密方式
		
		'USER_AUTH_GATEWAY'         =>  '/wifiadmin/login/index',// 默认认证网关
		
		'NOT_AUTH_MODULE'           =>  'Login,Pub',	// 默认无需认证模块
		
		'REQUIRE_AUTH_MODULE'       =>  '',		// 默认需要认证模块
		
		'NOT_AUTH_ACTION'           =>  '',		// 默认无需认证操作
		
		'REQUIRE_AUTH_ACTION'       =>  '',		// 默认需要认证操作
		
		'GUEST_AUTH_ON'             =>  false,    // 是否开启游客授权访问
		
		'GUEST_AUTH_ID'             =>  0,        // 游客的用户ID
		
		'RBAC_ROLE_TABLE'			=>	'wifi_role',
		
		'RBAC_USER_TABLE'			=>	'wifi_role_user',
		
		'RBAC_ACCESS_TABLE'			=>	'wifi_access',
		
		'RBAC_NODE_TABLE'			=>	'wifi_treenode',
		
		'SPECIAL_USER'				=>	'admin',
);