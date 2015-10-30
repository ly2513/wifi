<?php
// 路由器管理模型
class RoutemapModel extends Model{
    // 添加路由时，自动验证添加信息
    protected $_validate = array(
   
        array('shopid','require','路由所属帐号不能为空',1,'regex',Model:: MODEL_INSERT),
        array('routename','require','路由名称不能为空',1),
        array('gw_id','require','MAC地址不能为空',1),
		array('gw_id','/^[0-9a-zA-Z]{12}/','MAC必须是12位数字或字母','regex',3),
        array('gw_id','','MAC不能重复',1,'unique',1),
       
        
		array('sortid','require','排序不能为空',1),
		array('sortid','number','排序必须是数字类型'),
    );
    // 自动处理机制
    protected $_auto = array(
        array('add_time','time',self::MODEL_INSERT,'function'),
        array('update_time','time',self::MODEL_BOTH,'function'),
        array('gw_id','ToUp',self::MODEL_BOTH,'callback '),
        array('state','1',self::MODEL_INSERT),
    );
    
    protected  function ToUp($rs){
    	return strtoupper($rs);
    }
}