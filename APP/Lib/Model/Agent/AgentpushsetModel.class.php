<?php
class AgentpushsetModel extends Model{
    protected $_auto = array (
			
			array('add_time','time',3,'function'),
			array('update_time','time',3,'function'),
	);
    protected $_validate = array(
        array('aid','require','请重新登录配置',0,'',1),
        array('pushflag','require','请选择推送设置！',0,'',1),
    );

}
