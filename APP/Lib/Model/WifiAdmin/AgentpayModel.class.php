<?php
// 代理支付管理模型
class AgentpayModel extends  Model{
	protected $_auto = array (
		
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	protected $_validate =array(
		 array('aid','require','请选择代理商'),
      	 array('paymoney','require','操作金额不能为空',1),
      	 array('paymoney','currency','操作金额必须是货币格式',1,'function',1),
         array('desc','require','备注信息不能为空'),
	

 	
	);
}