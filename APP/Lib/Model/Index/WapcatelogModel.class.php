<?php
class WapcatelogModel extends Model{
	 protected $_auto = array (
			
			array('add_time','time',1,'function'),
			array('update_time','time',3,'function'),
			array('state','1',1,'string'),
	);
    protected $_validate = array(
        array('uid','require','用户帐号丢失',1),
     	array('mode','require','请选择栏目类别',1),
		array('title','require','请输入栏目名称',1),
        array('sort','require','栏目排序不能为空',1),
    );
}