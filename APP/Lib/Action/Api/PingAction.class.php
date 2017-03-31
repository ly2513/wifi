<?php
class PingAction extends BaseApiAction{
	private $gw_address = null;
	private $gw_port = null;
	private $gw_id = null;
	private $mac = null;
	private $url = null;
	private $logout = null;
		
	public function index(){
		echo "Pong";
		
		if (isset($_REQUEST["gw_id"])) {
		    $this->gw_id = $_REQUEST['gw_id'];
		}
		if(!empty($this->gw_id)){
			//寻找网关ID
			$db=D('Routemap');
			$where['gw_id']=$this->gw_id;
			$info=$db->where($where)->find();
			if($info!=false){
				//更新心跳包	
				$time=time();
				
				$save['last_heartbeat_time']=$time;
				$save['user_agent']=getAgent();
				$save['sys_uptime']=$_GET['sys_uptime'];
				$save['sys_memfree']=$_GET['sys_memfree'];
				$save['sys_load']=$_GET['sys_load'];
				$save['wifidog_uptime']=$_GET['wifidog_uptime'];
				
				$db->where($where)->save($save);
				//log::write("时间:".Date("Y-m-d H:i:s")." ".$db->getLastSql());			
				
			}
		}
	}
}