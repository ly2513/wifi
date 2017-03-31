<?php
class AuthAction extends BaseApiAction {
	private $shop = false;
	private $tplkey = "";
	
	private function isonline($val) {
		
		import ( "ORG.Util.Date" );
		$dt = new Date ( time () );
		$left = $dt->dateDiff ( $val, 's' ); //默认7天试用期
		return $left;
	
	}
	
	private $token;
	private $mac;
	private $ip;
	private $gw_id;
	public function index() {
		
		if (! empty ( $_REQUEST ['ip'] )) {
			$this->ip = $_REQUEST ['ip'];
		
		}
		if (! empty ( $_REQUEST ['gw_id'] )) {
			$this->gw_id = $_REQUEST ['gw_id'];
		
		}
		$this->mac = '';
		if (! empty ( $_REQUEST ['mac'] )) {
			$this->mac = $_REQUEST ['mac'];
		}
		if (! empty ( $_REQUEST ['token'] )) {
			$tk = $_REQUEST ['token'];
			
//			$tks = explode ( "_", $tk );
			
			$db = new Model ();
			$authdb = D ( 'Authlist' );
			if(!empty($this->mac)){
				//$where ['mac'] = $this->mac;
			}
			
			$where ['token'] = $tk;
			$rs = $authdb->where ( $where )->field ()->find ();


			$mdb=D('Member');
			 $wherem['token']=$tk;
			 $user=$mdb->where($wherem)->find();
			 	
			 	
			 if($user!=false){

				$mdata['mac']=$this->mac;
				
				$mdb->where($wherem)->save($mdata);



			 }





			if ($rs) {
				
				//update time
				if (empty ( $rs ['over_time'] ) || $rs ['over_time'] == "") {
					//no limit
					$this->token = $tk;
					echo ("Auth: 1n");
					echo ("Messages: Allow Accessn");
					$data ['mac'] = $this->mac;
					$data ['login_ip'] = $this->ip;
					$data ['pingcount'] = $rs ['pingcount'] + 1;
					$data ['last_time'] = time (); //
					$data ['update_time'] = time (); //
					$authdb->where ( $where )->save ( $data );
					exit ();
				} else {
					//limit
					$lf = $rs ['over_time'] - time ();
					if ($lf < 0) {
						//log::write('超时了');
						echo ("Auth: 0n");
						echo ("Messages: No Accessn");
						exit ();
					} else {
						$this->token = $tk;
						echo ("Auth: 1n");
						echo ("Messages: Allow Accessn");
						$data ['mac'] = $this->mac;
						$data ['login_ip'] = $this->ip;
						$data ['pingcount'] = $rs ['pingcount'] + 1;
						$data ['last_time'] = time (); //
						$data ['update_time'] = time (); //
						$authdb->where ( $where )->save ( $data );
						exit ();
					}
				}
			} 
//			else if (count ( $tks ) == 4 && count($tks)>1) {//微信可上网5分钟
//				
//				$t = time () - $tks [2];
//				if ($t < 5 * 60) {
//					echo ("Auth: 1n");
//					echo ("Messages: Allow Accessn");
//				} else {
//					echo ("Auth: 0n");
//					echo ("Messages: No Accessn");
//				}
//				exit ();
//			} 
			else {
				echo ("Auth: 0n");
				echo ("Messages: No Accessn");
				exit ();
			}
			/*
			 $tk=$_REQUEST['token'];
			 $db=D('Member');
			 $where['token']=$tk;
			 $user=$db->where($where)->find();
			 	
			 	
			 if($user==false){
				//不存在，不允许上网

				echo ("Auth: 0n");
				echo ("Messages: No Accessn");
				}else{
				//存在,更新信息

				//log::write(($user['online_time']-time())."秒时间戳");
				if($user['online_time']!=""){
				$lf=$user['online_time']-time();
				if($lf<0){
				//log::write('超时了');
				echo ("Auth: 0n");
				echo ("Messages: No Accessn");
				exit;
				}else{
					
				echo ("Auth: 1n");
				echo ("Messages: Allow Accessn");
				$data['mac']=$this->mac;
				$data['login_ip']=$this->ip;
				$data['login_count']=$user['login_count']+1;
				$data['login_time']=time();
				$db->where($where)->save($data);
				exit;
				}
				}else{
				//log::write('不限制');
				$this->token=$tk;
				echo ("Auth: 1n");
				echo ("Messages: Allow Accessn");
				$data['mac']=$this->mac;
				$data['login_ip']=$this->ip;
				$data['login_count']=$user['login_count']+1;
				$data['login_time']=time();
				$db->where($where)->save($data);
				}


				}
				*/
		} else {
			echo ("Auth: 0n");
			echo ("Messages: No Accessn");
			exit ();
		}
	}

}