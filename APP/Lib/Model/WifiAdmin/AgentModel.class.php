<?php
//代理商管理模型
class AgentModel extends  Model{
	protected $_auto = array (
		array('money','0',1),
			array('password','md5',1,'function'),
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	protected $_validate =array(
		 array('account','require','请填写登录帐号'),
         array('password','require','登录密码未填写！',0,'',1),
         array('name','require','请填写代理商名称'),
		 array('account','','用户账号已经存在！',1,'unique',1), // 新增修改时候验证username字段是否唯一
 		array('fee','require','代理费不能为空',3),
 		array('fee','currency','代理费必须是货币格式',1,'function',3),
	);
}