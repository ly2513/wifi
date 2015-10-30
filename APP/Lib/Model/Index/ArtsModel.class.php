<?php
class ArtsModel extends Model{
	 protected $_auto = array (
			
			array('add_time','time',1,'function'),
			array('update_time','time',3,'function'),
			array('state','1',1),
	);
    protected $_validate = array(
        array('uid','require','用户帐号丢失'),
     	array('cid','require','请选择栏目'),
		array('title','require','文章标题不能为空'),
        
    );
}