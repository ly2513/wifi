<?php
//模板管理模型
class AuthtplModel extends  Model{
	protected $_auto = array (
			
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	protected $_validate =array(
		 array('id','require','模板ID不能为空',1),
		 array('id','','模板ID不能不能重复！',1,'unique',1), // 新增修改时候验证username字段是否唯一
      	 array('tpname','require','模板名称不能为空',1),
      	 array('keyname','require','模板关键字不能为空',1),
         array('group','require','请选择模板分组',1),

	);
}