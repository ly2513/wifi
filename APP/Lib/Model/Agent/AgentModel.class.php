<?php
class AgentModel extends Model{
    protected $_auto = array (
			array('password','md5',1,'function'),
			array('add_time','time',1,'function'),
			array('update_time','time',3,'function'),
	);
    protected $_validate = array(
        array('account','require','请填写登录帐号',0,'',1),
        array('password','require','登录密码未填写！',0,'',1),
		array('account','','用户账号已经存在！',1,'unique',1), // 新增修改时候验证username字段是否唯一

        
    );

}
