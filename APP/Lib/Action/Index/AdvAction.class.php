<?php
/*
 * 高级收费功能 3G
 */
class AdvAction extends BaseAction {
	private function isLogin() {
		if (! session ( 'uid' )) {
			$this->redirect ( 'index/index/log' );
		}
		$this->assign ( 'a', 'advfun' );
	}
	public function index() {
	
	}

	// 短信账号管理
	public function set() {
		$this->isLogin ();
		$db = D ( 'Smscfg' );
		$uid = session ( 'uid' );
		$where ['uid'] = $uid;
		$where['action']=0;
		// 获得群发账号信息
		$info = $db->where ( $where )->find();
		// P($info);
		$where['action']=1;
		// 获得过滤短信账号信息
		$info1=$db->where($where)->find();
		$this->assign ( 'info', $info );
		$this->assign ( 'info1', $info1 );
		// 
		$this->display ();
	}
	// 添加和修改设置
	public function saveset() {
		$this->isLogin ();
		// P($_POST);exit;
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			$db = D ( 'Smscfg' );
			$uid = session ( 'uid' );
			$where ['uid'] = $uid;
			$info = $db->where ( $where )->find ();
			unset($_POST['sub']);
			unset($_POST['__hash__']);
			// print_r($_POST);exit;
			if ($info == false) {
				//没有就添加
				$_POST['date1']['action']=$_POST['action1'];
				$_POST['date1']['user']=$_POST['user1'];
				$_POST['date1']['password']=$_POST['password1'];
				$_POST['date']['action']=$_POST['action'];
				$_POST['date']['user']=$_POST['user'];
				$_POST['date']['password']=$_POST['password'];
				unset($_POST['action']);
				unset($_POST['user']);
				unset($_POST['password']);
				unset($_POST['action1']);
				unset($_POST['user1']);
				unset($_POST['password1']);
				unset($_POST['id']);
				unset($_POST['id1']);
				$_POST['date']['uid']= $uid;
				$_POST['date']['add_time']=time();
				$_POST['date']['update_time']=time();
				$_POST['date1']['uid']= $uid;
				$_POST['date1']['add_time']=time();
				$_POST['date1']['update_time']=time();
				foreach ($_POST as $value){
					if($db->add($value)){
						$this->success('保存成功');
					}
				}
			} else {
				//有就更新
				$_POST['date1']['action']=$_POST['action1'];
				$_POST['date1']['user']=$_POST['user1'];
				$_POST['date1']['password']=$_POST['password1'];
				$_POST['date']['action']=$_POST['action'];
				$_POST['date']['user']=$_POST['user'];
				$_POST['date']['password']=$_POST['password'];
				$_POST['date']['id']=$_POST['id'];
				$_POST['date1']['id']=$_POST['id1'];
				unset($_POST['action']);
				unset($_POST['user']);
				unset($_POST['password']);
				unset($_POST['action1']);
				unset($_POST['user1']);
				unset($_POST['password1']);
				unset($_POST['id']);
				unset($_POST['id1']);
				$_POST['date']['uid']= $uid;
				$_POST['date']['add_time']=time();
				$_POST['date']['update_time']=time();
				$_POST['date1']['uid']= $uid;
				$_POST['date1']['add_time']=time();
				$_POST['date1']['update_time']=time();
				foreach ($_POST as $value){
					if($db->save($value)){
						$this->success('保存成功');
					}
				}
				
				
			}
		}
	
	}
	
	
	// public function  test(){
	// 	import('@.ORG.XCSMS');
	// 	$server='http://221.122.112.136:8080/sms/mt.jsp?';

	// 	$u='76c5069c8921470d9605e516e9372cb7';
	// 	$p='PO7DJCVM';
	// 	$client=new XCSMS($server, $u, $p);
	// 	$client = new SoapClient('http://221.122.112.136:8080/sms/mt.jsp?'); 
	// 	$client->soap_defencoding='utf-8';
	// 	$client->decode_utf8=false;
	// 	echo $client->GetSmsAccount()."<br/>";
	// 	echo $client->GetSmsPrice()."<br/>";
	// 	echo $client->SendSms('15990531230', "您的验证码【1312da】，请勿泄漏【起讯科技】");
	// 	$this->assign('GetSmsAccount',$client->GetSmsAccount());
	// 	$this->assign('server',$server);
	// 	$this->display();

	// }

	
	/*
	 * 手机号码列表
	 */
	public function phonelist() {
		$this->isLogin ();
		import ( '@.ORG.UserPage' );
		$this->assign ( 'a', 'advfun' );
		$uid = session ( 'uid' );
		$where ['shopid'] = $uid;
		$where ['mode'] = 1;
		
		$ad = D ( 'Member' );
		$count = $ad->where ( $where )->count ();
		
		$page = new UserPage ( $count, C ( 'ad_page' ) );
		$result = $ad->where ( $where )->limit ( $page->firstRow . ',' . $page->listRows )->order ( 'login_time desc' )->select ();
		
		$this->assign ( 'page', $page->show () );
		$this->assign ( 'lists', $result );
		$this->display ();
	}
	/*
	 * 手机号码下载
	 */
	public function downphone() {
		$this->isLogin ();
		$uid = session ( 'uid' );
		$where ['uid'] = $uid;
		$where ['mode'] = array ('in', '1' );
		
		$ad = D ( 'Member' );
		$data = $ad->where ( $where )->field ( 'phone' )->select ();
		exportexcel ( $data, array ('手机号码' ), date ( 'Y-m-d H:i:s', time () ) );
	}
	// 发送信息界面
	public function sms() {
		$this->isLogin ();
		$this->assign ( 'a', 'advfun' );
		$this->display ();
	}
	
	public function doSend($phone, $msg) {
		
	}

	// 发送短信
	public function addsms() {
		header("content-type:text/html;charset=utf-8");
		$this->isLogin ();
		
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			$smsdb = D ( 'Smscfg' );
			$uid = session ( 'uid' );
			$where ['uid'] = $uid;
			// 获得当前商户的发送信息的配置信息
			$info = $smsdb->where ( $where )->find ();
			if ($info == false) {
				$back ['error'] = 1;
				$back ['msg'] = '请先配置好短信帐号信息';
				$this->ajaxReturn ( $back );
				exit();
			}
			// 获得手机号码
			$phones = I ( 'post.phones' );
			// 获得要发送的信息
			$msg = I ( 'post.info',null,'string' );
			$list = explode ( ',', $phones );
			$len = mb_strlen ( $msg, 'UTF-8' ); //短信长度
			
			$ut = ceil ( $len / 70 ); //计算短信数量（向上取整）
			
			$uid = session ( 'uid' );
			// 发送时间
			$time = time ();
			foreach ( $list as $v ) {
				if ($v != '') {
					if (! isPhone ( $v )) {
						$back ['error'] = 1;
						$back ['msg'] = '手机号码' . $v . '不正确';
						$this->ajaxReturn ( $back );
						exit ();
						break;
					
					} else {
						$datalist [] = array ('uid' => $uid, 'mode' => 0, 'phone' => $v, 'info' => $msg, 'lens' => $len, 'unit' => $ut, 'add_time' => $time, 'update_time' => $time, 'send_time' => $time, 'ready_time' => $time, 'state' => 1, 'lostcount' => 0);
					}
				}
			}

			// 实例化一个对sms表的操作对象
			$sms=D('Sms');
			// 向sms表中添加数据
			$sms->addAll($datalist);
			// P($codelength);exit;
			
			// 发送短信服务器端的url
			$server = C ( 'SMSURL' );
			// 获得账号
			$u = $info ['user'];
			// 获得密码
			$p = $info ['password'];
			// 获得发送连接
			$url = $server . "?cpName=" . $u . "&cpPwd=" . $p . "&phones=" . $phones . "&msg=" .rawurlencode(iconv("UTF-8","GB2312",$msg));
			// urlencode(iconv("UTF-8","GB2312//IGNORE",$msg));
			//自动重试5次，防止获取不正常(发送五次短信)
			// for($i = 0; $i < 5; $i ++) {
				// $rs = file_get_contents ( $url );
				if(function_exists('file_get_contents')){
					$file_contents = file_get_contents($url);
				}else{
					$ch = curl_init();
					$timeout = 5;
					curl_setopt ($ch, CURLOPT_URL, $url);
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
					$file_contents = curl_exec($ch);
					curl_close($ch);
				}
				if (! empty ( $file_contents )) { 
					$rsarray = json_decode ( $url, true );
					if (! empty ( $rsarray ['result'] ) && isset ( $rsarray ['result'] )) {
						
						if ($rsarray ['result'] == '01') {
							$rs = 1;
						} else {
							$rs = $rsarray ['result'];
						}
					}
					break;
				}
			// }
			import ( '@.ORG.XCSMS' );
			// 调用XCSMS发送类
			$client=new XCSMS($server, $u, $p);
			// 发送手机信息
			$rs=$client->SendSms($phones,$msg);

			P($rs);exit;

			// if ($rs == 1) {
			// 	$sms = D ( 'Sms' );
			// 	$sms->addAll ( $datalist );
			// 	$back ['error'] = 0;
			// 	$back ['msg'] = '操作成功';
			// 	$this->ajaxReturn ( $back );
			// } else if ($rs == '02') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = 'IP限制';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '03') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = '用户名密码错误';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '04') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = '剩余条数不足';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '05') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = '信息内容中含有限制词(违禁词)';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '06') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = '信息内容为黑内容';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '07') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = '该用户的该内容 受同天内内容不能重复发 限制';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '08') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = '批量下限不足';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '11') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = '已超出100条最大手机数量';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '12') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = 'bechtech防火墙无法处理这种短信';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '13') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = 'bechtech用户账户被冻结';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '14') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = '手机号码不正确或者为空';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '97') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = 'bechtech网关异常';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '98') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = '不符合bechtech的免审模板';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '99') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = 'bechtech系统异常';
			// 	$this->ajaxReturn ( $back );
			// }else if ($rs == '100') {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = 'bechtech系统例行维护（一般会在凌晨0点~凌晨1点期间进行5分钟左右的升级维护）';
			// 	$this->ajaxReturn ( $back );
			// } else {
			// 	$back ['error'] = 1;
			// 	$back ['msg'] = '发送失败,错误码:' . $rs;
			// 	$this->ajaxReturn ( $back );
			// }
		
		}
	}
	// 获得上网验证码
	private function getCode($length){
		// 验证码种子
		$seek="qwertyuiopasdfghjklzxcvbnm7418529630";
		$result='';
		for ($p = 0; $p < $length; $p++){
	        $result.= ($p%2) ? $seek[mt_rand(19, 35)] : $seek[mt_rand(0, 18)];
	    }
	    // P($result);exit;
		return $result ;
	}
	private function getsmsstate($rs) {
		//1 成功 -1 失败 -2 帐号密码不正确 -3 金额不足 -4 手机号码或其他参数不正确
		switch ($rs) {
			case - 1 :
				return "短信提交失败";
			case - 2 :
				return "发送短信的帐号密码不正确";
			case - 3 :
				return "短信帐号余额不足";
			case - 4 :
				return "提交的手机号码有错";
			default :
				return '短信提交成功';
		
		}
	}
	// 短信发送列表
	public function smslist() {
		import ( '@.ORG.UserPage' );
		$this->assign ( 'a', 'advfun' );
		$uid = session ( 'uid' );
		$where ['uid'] = $uid;
		
		$ad = D ( 'Sms' );
		$count = $ad->where ( $where )->count ();
		$page = new UserPage ( $count, C ( 'ad_page' ) );
		$result = $ad->where ( $where )->limit ( $page->firstRow . ',' . $page->listRows )->order ( 'add_time desc' )->select ();
		$this->assign ( 'page', $page->show () );
		$this->assign ( 'lists', $result );
		$this->display ();
	}
	

}