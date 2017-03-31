<?php
class MemberModel extends Model{
	protected $_auto = array (
			array('password','md5',1,'function'),	//add
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	protected $_validate =array(
			array('token','require','TOKEN不能为空',1),
			array('user','require','用户名未填写！',1,'',1),
			array('user','/^[a-zA-Z0-9]{3,20}/','用户名必须由3到20位数字或字母组成！',1,'regex',1), // 新增修改时候验证username字段是否唯一
			array('password','require','登录密码未填写！',1,'',1),
			array('token','','用户名已经存在！',1,'unique',1), // 新增修改时候验证username字段是否唯一
	);
}