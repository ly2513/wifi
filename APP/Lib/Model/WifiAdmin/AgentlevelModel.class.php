<?php
//代理等级模型
class AgentlevelModel extends  Model{
	protected $_auto = array (
			
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	protected $_validate =array(
		array('title','require','名称不能为空',1),
		array('openpay','require','开户金额不能为空',1),
		array('openpay','currency','开户金额必须是货币格式',1,'function',1),
 
	);
}