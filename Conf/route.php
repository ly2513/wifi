<?php 
//rest 路由配置
$rest_routes = array(
				array('login$','api/login/index','','GET'),//下载
				array('ping$','api/ping/index','','GET'),//网关心跳协议
				array('auth$','api/auth/index','','GET'),//检测
				array('portal$','api/user/index','','GET'),//检测
				array('library/test/success.html','api/login/apple','','GET'),//检测
				array('weichat/command/:wei_code$','weixin/command/sing_check_get','','get'),//消息认证
				array('weichat/command/:wei_code$','weixin/command/command_post','','post'),//命令处理
				array('download/img$','baidu/bscdownload/downloadimg','','get'),//命令处理
				
			);
return array (
	'URL_ROUTER_ON'=>true,
	'URL_ROUTE_RULES'=>$rest_routes,//rest路由
  	'OpenMaxCount' => '600',
	
);