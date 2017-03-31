<?php
// 推送广告管理模型
class PushadvModel extends  Model{
	// 自动处理
	protected $_auto = array (
			array('state','1',self::MODEL_INSERT,),
			array('add_time','time',self::MODEL_INSERT,'function'),
			array('update_time','time',self::MODEL_BOTH,'function'),
			
	);
	// 添加推送广告时，自动验证
	protected $_validate =array(
			array('title','require','请输入广告标题',1),
			array('pic','require','请选择广告图片',1),
			array('sort','require','请输入广告投放顺序',1),
			array('startdate','require','请输入广告投放时间',3),
			array('enddate','require','请输入广告投放结束时间',3),
	);
}