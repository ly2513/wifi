<?php
// 角色管理模型
class RoleModel extends Model{
	protected $_auto = array (

			array('pid','0'),
			
	);
	protected $_validate =array(
			array('name','require','角色名称不能为空',1),
	);
	

}