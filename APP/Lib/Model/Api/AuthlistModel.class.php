<?php
class AuthlistModel extends Model{
	protected $_auto = array (
		
			array('login_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			array('add_date','getNowDate',self::MODEL_INSERT,'function'),
			
	);
	protected $_validate =array(
			

	);
	
	
	
}