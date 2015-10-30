<?php
/**
 *网站缓存配置
 *@package ViiCms
 *@author ViiCms
 **/
return array(
	/*缓存定义*/
	'DATA_CACHE_TYPE'       => 'File',  		//缓存类型
	'DATA_CACHE_COMPRESS'   => true,   		// 数据缓存是否压缩缓存
	'DATA_CACHE_SUBDIR'     => true,    	// 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'       => 2,        		// 子目录缓存级别
	'HTML_CACHE_ON'			=> false,			//是否开启静态缓存
	//'HTML_CACHE_RULES'   	=>array('*'=>array('{$_SERVER.REQUEST_URI|md5}')),			//静态缓存规则
	'HTML_CACHE_TIME'		=>   60,			//静态缓存有效期（秒）
	'HTML_FILE_SUFFIX' 		=>'.html',			//静态缓存后缀 
	'TMPL_CACHE_ON'=>false,      // 默认开启模板缓存
	

	'APP_DEBUG'=>false,
	'DB_FIELD_CACHE'=>false,
	'HTML_CACHE_ON'=>false,
);