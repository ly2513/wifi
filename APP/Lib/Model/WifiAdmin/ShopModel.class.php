<?php
// 商户管理模型
class ShopModel extends Model{
    protected $_auto = array (
			array('password','md5',1,'function'),
			array('add_time','time',1,'function'),
			array('update_time','time',3,'function'),
			array('end_time','setDefaultOvertime',self::MODEL_INSERT,'callback'),
			array('maxcount','setDefaultMax',self::MODEL_INSERT,'callback'),
		
	);
	// 商户添加信息时，自动验证信息
    protected $_validate = array(
        array('account','require','请填写登录帐号'),
        array('password','require','登录密码未填写！',0,'',1),
		array('account','','用户账号已经存在！',1,'unique',1), // 新增修改时候验证username字段是否唯一

        
    );
    // 设置默认超时
	public function setDefaultOvertime(){
		import("ORG.Util.Date");
		$dt=new Date(time());
		$date=$dt->dateAdd(C('EndDay'),'d');//默认7天试用期
		return strtotime($date);
		
	}
	
	public function setDefaultMax(){
		if(empty($_POST['maxcount'])){
					return C('OpenMaxCount');
		}else{
			return $_POST['maxcount'];
		}

	}
	
}
