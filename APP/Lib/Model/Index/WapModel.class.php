<?php
class WapModel extends Model{
	 protected $_auto = array (
			
			array('add_time','time',1,'function'),
			array('update_time','time',3,'function'),
			
	);
    protected $_validate = array(
        array('uid','require','用户帐号丢失'),
     	array('shopname','require','网站名称不能为空'),

        
    );
}