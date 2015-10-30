<?php
//zskj0328
// 设置编码
header("Content-type: text/html; charset=utf-8");
// 应用名称
define('APP_NAME', 'WIFI');
// 配置项目录
define('CONF_PATH','./Conf/');
// 编译目录、临时文件目录
define('RUNTIME_PATH','./Runtime/');
// 模板目录
define('TMPL_PATH','./UI/');
// 应用目录
define('APP_PATH','./APP/');
// 开启调试模式
// define('DEBUG',true);
//框架所在目录
define('CORE','./Core/');
// 上传目录
define('UPLOAD_PATH','./Upload/');
// 公共文件目录
define('PUBLIC_PATH','./Public/');
// 数据目录(短信发送接口)
define('DATA_PATH','./Data/');
define('MODE_NAME','rest');
// 开启应用调试模式
define('APP_DEBUG',true);
if (get_magic_quotes_gpc()) {
	function stripslashes_deep($value){ $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	return $value;
}
	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

//require(CORE.'/Extend/Engine/sae.php');
//加载框架
require(CORE.'/ThinkPHP.php');
?>