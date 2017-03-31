<?php
class UserAction extends BaseApiAction {
	private $userinfo;
	private $tplkey = "";
	private function CheckUser() {
		if (cookie ( 'token' ) != "" && cookie ( 'mid' ) != "") {
			$db = D ( 'Member' );
			$where ['token'] = session ( 'token' );
			$info = $db->where ( $where )->find ();
			if ($info == false) {
				$this->userinfo = $info;
				$this->assign ( 'user', $info );
			} else {
				$this->redirect ( U ( 'Api/login', array ('gw_address' => cookie ( 'gw_address' ), 'gw_id' => cookie ( 'gw_id' ), 'gw_port' => cookie ( 'gw_port' ) ) ) );
			}
		} else {
			$this->redirect ( U ( 'Api/login', array ('gw_address' => cookie ( 'gw_address' ), 'gw_id' => cookie ( 'gw_id' ), 'gw_port' => cookie ( 'gw_port' ) ) ) );
		}
	}
	
	private  function load_shopinfo($gw_id){
		$db1=D('Routemap');
		$sql1 = "SELECT * FROM wifi_routemap WHERE `gw_id`='$gw_id'";
		$rs1=$db1->query($sql1);
		$shop_id = $rs1[0]['shopid'];
		$db2=D('shop');
		$sql2 = "SELECT * FROM wifi_shop WHERE `id`='$shop_id'";
		$rs2=$db2->query($sql2);
		return $rs2[0];
	}
	public function index() {
		$this->CheckUser ();
		$gw_id = cookie ( 'gw_id' );
		$shop = $this->load_shopinfo ($gw_id);
//		$isWX = cookie ( 'is_weixin' );
//		if ($isWX) {
//			$jump = U ( 'api/wap/weixin_tmp', array () );
//			cookie ( 'is_weixin',false );
//		} else {
			
			switch ($shop['authaction']) {
				case "1" :
					$jump = $shop['jumpurl'];
					
					break;
				case "0" :
					break;
				case "2" :
					if (cookie ( 'gw_url' ) != null) {
						$jump = cookie ( 'gw_url' );
					}
					break;
				case "3" :
					$jump = U ( 'api/wap/index', array ('sid' => $shop['id'] ) );
					break;
			}
//		}
//		print_r("已经成功认证了，下面是正在测试的数据");
//		print_r($shop);
//		exit;
//		$jump = $this->shop [0] ['jumpurl'].'a='.$this->shop [0] ['authaction'];
		$this->assign ( 'jumpurl', $jump );
		if (empty ( $this->tplkey ) || $this->tplkey == "" || strtolower ( $this->tplkey ) == "default") {
			$this->display ();
		} else {
			$this->display ( "index$" . $this->tplkey );
		}
	}
}