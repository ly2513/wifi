<?php

//mongodb配置
return array(
	//'配置项'=>'配置值'
	'APP_AUTOLOAD_PATH'     =>'ORG',
	'OUTPUT_ENCODE'         =>  true, 			//页面压缩输出
		/*Cookie配置*/
	'COOKIE_PATH'           => '/',     		// Cookie路径
	'COOKIE_PREFIX'         => '',      		// Cookie前缀 避免冲突
	/*定义模版标签*/
	'TMPL_L_DELIM'   		=>'<#',			//模板引擎普通标签开始标记
	'TMPL_R_DELIM'		=>'#>',			//模板引擎普通标签结束标记
	'LOAD_EXT_CONFIG' 	=> 'db,cache,site,log,safe,upload,route,adv',
	'APP_GROUP_LIST'        => 'Index,Api,Agent,Wifiadmin',      // 首页,接口页面，后台，客户，代理商
	'DEFAULT_GROUP'         => 'Index',  // 默认分组
	'DEFAULT_THEME'=>'default',
	'TMPL_FILE_DEPR'=>'_',
	'SMSURL'=>'http://221.122.112.136:8080/sms/mt.jsp',//'http://115.239.252.188:820/sms.asmx?WSDL',	发送短信的服务器地址
	'WAP_List'=>5,
	'URL_CASE_INSENSITIVE'  => true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
	'STORE_TYPE' =>1,//1为默认,2为7牛，7牛就要配置以下
	'QINIU'=>array('bucket' => 'ahwifiwx',
			   'accessKey' => 'ohhOJKKa6WYLBpRZIFa2Jg0S6WYvaQAzRg5ZNty5',
			   'secretKey' => 'sGHr4xTwJDWeTOIThGYhB5xV0eCC0_zDcHN0sgfY',
			   'domain' =>'ahwifiwx.qiniudn.com',),
	'BSC'=>array('bucket' => 'ahwifiwx',
			   'accessKey' => '4AmBFaxaAfWYeVQxaRsPsm2s',
			   'secretKey' => 'eifrbmWASTQeWnzCGGVEv4wjoZINp9RO',
				'host' => 'bcs.duapp.com',),//host不用改
);
?>