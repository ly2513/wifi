<?php
class SmscfgModel extends  Model{
	protected $_auto = array (
			array('state','1',self::MODEL_INSERT,),
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	 protected $_validate = array(
        array('user','require','请填写短信帐号',1,'',3),
        array('password','require','短信帐号密码未填写！',1,'',3), 
    );
}