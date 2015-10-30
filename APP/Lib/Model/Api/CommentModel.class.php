<?php
class CommentModel extends Model{
	protected $_auto = array (
			
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	protected $_validate =array(

			array('user','require','姓名不能为空！',1,'',1),
			array('phone','require','手机号码不能为空！',1,'',1),
			array('content','require','留言内容不能为空！',1,'',1),
			
			
		
	);
}