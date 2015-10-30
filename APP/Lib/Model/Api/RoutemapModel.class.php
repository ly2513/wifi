<?php
class RoutemapModel extends Model{
	protected $_auto = array (
			
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	protected $_validate =array(
			array('shopid','require','路由所属帐号不能为空',1),
			array('gw_id','require','TOKEN不能为空',1),
			array('gw_id','','网关ID不能重复！',1,'unique',1), // 新增修改时候验证username字段是否唯一
		
	);
}