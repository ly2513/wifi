<?php

class UserModel extends Model{
    
    protected $_validate = array(
        array('user','require','请填写登录帐号'),
        array('pass','require','请填写密码'),
        array('user','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('repassword','pass','确认密码与密码不一致',0,'confirm'), // 验证确认密码是否和密码一致
    );

}
