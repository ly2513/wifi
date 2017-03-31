<?php
// 新闻管理模型
class NewsModel extends  Model{
	protected $_auto = array (
			
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	protected $_validate =array(
		 array('title','require','请填写新闻标题'),
         array('info','require','请填写新闻内容'),

	);
}