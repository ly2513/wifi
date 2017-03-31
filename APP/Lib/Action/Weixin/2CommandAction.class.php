<?php
// 命令处理类相关类
/**
 * https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
 */

class CommandAction extends BaseAction {
	public function command_post() {
		
		$this->responseMsg ();
	}
	/**
	 * 消息认证
	 * Enter description here ...
	 */
	public function sing_check_get() {
		$weiCode = $_GET ['wei_code'];
		error_log ( 'weiCode=' . $weiCode );
		if ($weiCode != null) {
			$echoStr = $_GET ["echostr"];
			if ($this->checkSignature ( $weiCode )) {
				echo $echoStr;
				exit ();
			}
		}
		exit ();
	}
	
	public function responseMsg() {
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		//		json_decode($_SERVER);
		//		print_r($_SERVER);
		if (! empty ( $postStr )) {
			
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			$fromUsername = $postObj->FromUserName;
			$weiCode = $toUsername = $postObj->ToUserName; //这个是微信后台中原始ID,可以用来做wei_code,
			$shop = $this->load_shopinfo ( $weiCode );
			
			$MsgType = $postObj->MsgType;
			$result_error = '';
			if ($MsgType == 'event') { //
				
				
				$three = $this->sendThreePlatform ( $shop, $postStr );
				$Event = $postObj->Event;
				if ($Event == 'subscribe') { //订阅，这里有可能是重复订阅，要注意
					// 转发到第三方
					$a = $this->getAuth ( $shop ['authmode'] );
					if ($a == false) {//如果关闭了微信认证，则直接转发到第三方
						if (! empty ( $three )) {
							echo $three;
						}
						exit ();
					}
					if (empty ( $shop )) {
						$result_error = '商家已经取消微信关注上网功能';
						$this->sendMsg ( $fromUsername, $toUsername, $result_error );
					} else { //检测是否设置通过微信认认证
						$a = $this->getAuth ( $shop ['authmode'] );
						if ($a == false) {
							echo $three;
							exit ();
						}
					
					}
					$shopname = $shop ['shopname'];
					$url = $this->getTokenUrl ( $shop, $fromUsername );
					$contentStr = "欢迎光临 " . $shopname . "，上网请直接点击：<a target=\"_blank\"  href=\"$url\">我要上网</a>,回复'wifi'或者'上网'可以再次获取上网权限,回复'帮助'获取更多信息";
					$this->sendMsg ( $fromUsername, $toUsername, $contentStr );
				
				} elseif ($Event == 'unsubscribe') { //取消订阅
					$user = $this->load_userinfo ( $fromUsername );
					
					$uid = $user ['id'];
					$routeid = $user ['routeid'];
					$mac = $user ['mac'];
					$tm = time ();
					// 删除授权
					$db = D ( 'AuthlistModel' );
					$sql2 = "UPDATE `wifi_authlist` SET `over_time` = '$tm' WHERE `mac`= '$mac'"; //授权时间取消
					//$sql2 = "DELETE FROM `wifi_authlist` WHERE `mac`= '$mac'";//清除所有这台机器的授权
					$db->query ( $sql2 );
					// 删除授权
					$db = D ( 'AuthlistModel' );
					$sql2 = "UPDATE `wifi_authlist` SET `over_time` = '$tm' WHERE `uid` = $uid"; //授权时间取消
					//$sql2 = "DELETE FROM `wifi_authlist` WHERE `uid` = $uid";
					$db->query ( $sql2 );
					// 删除用户
					$db3 = D ( 'Member' );
					$sql3 = "DELETE FROM `wifi_member` WHERE `id` = $uid";
					$db3->query ( $sql3 );
					// 删除授权
					$db = D ( 'Member' );
					$sql2 = "DELETE FROM `wifi_authlist` WHERE `weixin_openid`= '$fromUsername' and `shop_weixin_id`= '$toUsername'"; //清除所有这台机器的授权
					$db->query ( $sql2 );
				} else {
					echo $three;
				}
				exit ();
			
			}
			
			$keyword = trim ( $postObj->Content );
			
			if (! empty ( $keyword )) {
				//				$shop = $this->load_shopinfo ( $weiCode );
				if ($keyword == 'wifi' || $keyword == '上网') {
					if (empty ( $shop )) {
						$result_error = '商家已经取消微信关注上网功能';
						$this->sendMsg ( $fromUsername, $toUsername, $result_error );
					} else {
						$a = $this->getAuth ( $shop ['authmode'] );
						if ($a == false) {
							// 转发到第三方
							$rs = $this->sendThreePlatform ( $shop, $postStr );
							if (! empty ( $rs )) {
								echo $rs;
							}
							exit ();
						}
					}
					$shopname = $shop ['shopname'];
					$url = $this->getTokenUrl ( $shop, $fromUsername );
					$contentStr = "欢迎光临 " . $shopname . "，上网请直接点击：<a target=\"_blank\"  href=\"$url\">我要上网</a>,回复'wifi'或者'上网'可以再次获取上网权限,回复'帮助'获取更多信息";
					$this->sendMsg ( $fromUsername, $toUsername, $contentStr );
				} else {
					// 转发到第三方
					$rs = $this->sendThreePlatform ( $shop, $postStr );
					
					if (! empty ( $rs )) {
						$postObj2 = simplexml_load_string ( $rs, 'SimpleXMLElement', LIBXML_NOCDATA );
						$content = trim($postObj2->Content);
						if(empty($content)){
							$contentStr = "回复'wifi'或者'上网'可以获取上网权限";
							$this->sendMsg ( $fromUsername, $toUsername, $contentStr );
						}else{
							echo $rs;
						}
						exit ();
					}else if($keyword == '帮助'){
						$contentStr = "回复'wifi'或者'上网'可以获取上网权限";
						$this->sendMsg ( $fromUsername, $toUsername, $contentStr );
					}
					$contentStr = "发送'wifi'或'上网'即可获取上网权限";
					$this->sendMsg ( $fromUsername, $toUsername, $contentStr );
				}
			} else {
				$rs = $this->sendThreePlatform ( $shop, $postStr );
				if (! empty ( $rs )) {
					echo $rs;
					exit ();
				}
				echo '';
			}
			exit ();
		} else {
			echo "";
			exit ();
		}
	}
	private function getAuth($authmode) {
		$tmp = explode ( '#', $authmode );
		foreach ( $tmp as $v ) {
			if ($v != '#' && $v != '') {
				$arr [] = $v;
			}
		}
		foreach ( $arr as $v ) {
			$temp = explode ( '=', $v );
			if (count ( $temp ) > 1 && $temp [0] == '4') {
				return true;
			} else if ($v == '4') {
				return true;
			}
		}
		return false;
	}
	private function sendThreePlatform($shop, $postStr) {
		$token = $shop ['t_wx_token'];
		if (! empty ( $token )) {
			$nonce = mt_rand ( 1, 1000 );
			$timestamp = time ();
			$tmpArr = array ($token, $timestamp, $nonce );
			sort ( $tmpArr, SORT_STRING );
			$tmpStr = implode ( $tmpArr );
			$signature = sha1 ( $tmpStr );
			
			$remote_server = $shop ['t_wx_url'];
			$urls = explode ( "?", $remote_server );
			$data = 'timestamp=' . $timestamp . '&signature=' . $signature . '&nonce=' . $nonce;
			if (isset ( $urls [1] )) {
				$data = $data . '&' . $urls [1];
			}
			$remote_server = $urls [0] . '?' . $data;
			
			return $this->request_by_other ( $remote_server, $postStr );
		} else {
			return null;
		}
	}
	function request_by_other($url, $data) {
		$file_contents = "";
		if (function_exists ( 'file_get_contents' )) {
			$opts = array ('http' => array ('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencodedrn" . "Content-Length: " . strlen ( $data ) . "rn", 'content' => $data ) );
			$context = stream_context_create ( $opts );
			$html = null;
			for($i = 0; $i < 5; $i ++) {
				$file_contents = @file_get_contents ( $url, false, $context ); //php.ini中，有这样两个选项:allow_url_fopen =on(表示可以通过url打开远程文件)，user_agent="PHP"（表示通过哪种脚本访问网络，默认前面有个 " ; " 去掉即可。）重启服务器。
				if (! empty ( $file_contents )) {
					break;
				}
			}
		}
		if ($file_contents == "") {
			$ch = curl_init ();
			$timeout = 5;
			$header = "Content-type: application/x-www-form-urlencodedrn" . "Content-Length: " . strlen ( $data ) . "rn";
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header ); //设置HTTP头
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
			$file_contents = curl_exec ( $ch );
			if (curl_errno ( $ch )) { //出错则显示错误信息
				$file_contents = curl_error ( $ch );
			}
			curl_close ( $ch );
		}
		return $file_contents;
	}
	//	function request_by_other($remote_server, $data) {
	//		$opts = array ('http' => array ('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencodedrn" . "Content-Length: " . strlen ( $data ) . "rn", 'content' => $data ) );
	//		$context = stream_context_create ( $opts );
	//		$html = file_get_contents ( $remote_server, false, $context );
	//		return $html;
	//	}
	/**
	 * 发送信息
	 * Enter description here ...
	 * @param unknown_type $fromUsername
	 * @param unknown_type $toUsername
	 * @param unknown_type $contentStr
	 */
	private function sendMsg($fromUsername, $toUsername, $contentStr) {
		$time = time ();
		$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
		$resultStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, "text", $contentStr );
		echo $resultStr;
		exit ();
	}
	private function checkSignature($weiCode) {
		
		$shop = $this->load_shopinfo ( $weiCode );
		if (empty ( $shop )) {
			return false;
		}
		$token = $shop ['weixin_token'];
		if (empty ( $token )) { //没有设置token
			return false;
		}
		
		$signature = $_GET ["signature"];
		$timestamp = $_GET ["timestamp"];
		$nonce = $_GET ["nonce"];
		$tmpArr = array ($token, $timestamp, $nonce );
		sort ( $tmpArr, SORT_STRING );
		$tmpStr = implode ( $tmpArr );
		$tmpStr = sha1 ( $tmpStr );
		
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * 获取微信自动认证地址
	 * Enter description here ...
	 */
	private function getTokenUrl($shop, $openId) {
		$token = $this->checkOpenId ( $shop, $openId );
		//$a = decrypt_p();
		$shopname = rawurlencode ( $shop ['shopname'] );
		$url = "http://wx.ahwifi.com/weixin_auth_sucession.php?token=" . $token . "|" . $shopname; //
		return $url;
	}
	private function checkOpenId($shop, $openId) {
		$db = D ( 'Member' );
		$sql = "SELECT * FROM wifi_member WHERE `weixin_openid`='$openId'";
		$info = $db->query ( $sql );
		if (empty ( $info ) || ! isset ( $info [0] ) || empty ( $info [0] ['token'] )) {
			$token = md5 ( uniqid () );
			$now = time ();
			$password = md5 ( $openId );
			$nowdate = getNowDate ();
			$shopId = $shop ['id'];
			$shopWxId = $shop ['weixin_id'];
			$sql2 = "INSERT INTO wifi_member(token,user,password,shopid,browser,mode,add_time,update_time,login_time,add_date,weixin_openid,shop_weixin_id) VALUES('$token','$token','$password','$shopId','weixin','3','$now','$now','$now','$nowdate','$openId','$shopWxId')";
			$db->query ( $sql2 );


 $db = D ( 'Member' );
		$sql = "SELECT * FROM wifi_member WHERE `weixin_openid`='$openId'";
		$info = $db->query ( $sql );
       $tranDb=new Model();
			$tranDb->startTrans();
			$arrdata['uid']=$info[0]['id'];
			$arrdata['add_date']=getNowDate();
			$arrdata['over_time']=$this->getLimitTime($shop);
			$arrdata['update_time']=$now;//更新时间
			$arrdata['login_time']=$now;//首次登录时间
			$arrdata['last_time']=$now;//最后在线时间
			$arrdata['shopid']=$shop['id'];
			$arrdata['token']=$token;

             $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);  
			
			
			if($flagauth){
							$tranDb->commit();

                      }








		} else {
			$token = $info [0] ['token'];
			// 删除认证异常用户
			$db3 = D ( 'Member' );
			$sql3 = "DELETE FROM `wifi_member` WHERE `token` = '$token'";
			$db3->query ( $sql3 );
			// 删除认证异常用户
			$token = md5 ( uniqid () );
			$now = time ();
			$password = md5 ( $openId );
			$nowdate = getNowDate ();
			$shopId = $shop ['id'];
			$shopWxId = $shop ['weixin_id'];
			$sql2 = "INSERT INTO wifi_member(token,user,password,shopid,browser,mode,add_time,update_time,login_time,add_date,weixin_openid,shop_weixin_id) VALUES('$token','$token','$password','$shopId','weixin','3','$now','$now','$now','$nowdate','$openId','$shopWxId')";
			$db->query ( $sql2 );
		


 $db = D ( 'Member' );
		$sql = "SELECT * FROM wifi_member WHERE `weixin_openid`='$openId'";
		$info = $db->query ( $sql );
       $tranDb=new Model();
			$tranDb->startTrans();
			$arrdata['uid']=$info[0]['id'];
			$arrdata['add_date']=getNowDate();
			$arrdata['over_time']=$this->getLimitTime($shop);
			$arrdata['update_time']=$now;//更新时间
			$arrdata['login_time']=$now;//首次登录时间
			$arrdata['last_time']=$now;//最后在线时间
			$arrdata['shopid']=$shop['id'];
			$arrdata['token']=$token;

             $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);  
			
			
			if($flagauth){
							$tranDb->commit();

                      }




		}
		return $token;
	}
	private function getLimitTime($shop) {
		if ($shop ['timelimit'] != "" && $shop ['timelimit'] != "0") {
			import ( "ORG.Util.Date" );
			$dt = new Date ( time () );
			$date = $dt->dateAdd ( $shop ['timelimit'], 'n' ); //默认7天试用期
			return strtotime ( $date );
		}
		return "";
	
	}
	/*
	 * 加载商户信息
	 */
	private function load_shopinfo($weiCode) {
		$db = D ( 'shop' );
		$sql = "SELECT * FROM wifi_shop WHERE `weixin_id`='$weiCode'";
		$rs = $db->query ( $sql );
		return $rs [0];
	}
	private function load_userinfo($openId) {
		$db = D ( 'Member' );
		$sql = "SELECT * FROM wifi_member WHERE `weixin_openid`='$openId'";
		$rs = $db->query ( $sql );
		return $rs [0];
	}

}