<?php
// 系统提示信息模型
class NoticeModel extends  Model{
	protected $_auto = array (
			
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	protected $_validate =array(
		 array('title','require','请填写消息标题'),
         array('info','require','请填写消息内容'),

	);
}