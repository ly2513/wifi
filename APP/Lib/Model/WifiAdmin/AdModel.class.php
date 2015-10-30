<?php
//广告管理模型
class AdModel extends  Model{
	protected $_auto = array (
			array('state','1',self::MODEL_INSERT,),
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	protected $_validate =array(
		

	);
}