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
				echo $echoStr;exit;
			}
		}
		exit;
	}
	/**
	 * [responseMsg 回复信息]
	 * @return [type] [description]
	 */
	public function responseMsg() {
		// 获得xml格式的字符串
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		// 解释XML字符串为一个对象
		$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
		// 信息的发送方
		$fromUsername = $postObj->FromUserName;
		//这个是微信后台中原始ID,可以用来做wei_code,信息的接收方
		$weiCode = $toUsername = $postObj->ToUserName; 
		// 加载当前要发送信息给哪家商户的信息
		$shop = $this->load_shopinfo( $weiCode );
		// 获得信息类型
		$MsgType = $postObj->MsgType;
		// 记录错误信息
		$result_error = '';

		if ($MsgType == 'event') {
			// 使用第三方接口
			$three = $this->sendThreePlatform ( $shop, $postStr );
			// 获得事件类型
			$Event = $postObj->Event;
			//订阅，这里有可能是重复订阅，要注意
			switch ($Event) {
			 	case 'subscribe':
				 	// 转发到第三方认证
					// $a = $this->getAuth ( $shop ['authmode'] );
					// if ($a == false) {//如果关闭了微信认证，则直接转发到第三方
					// 	if (! empty ( $three )) {
					// 		echo $three;
					// 	}
					// 	exit ();
					// }
					// // 关注的商家数据库中不存在
					// if (empty ( $shop )) {
					// 	$result_error = '商家已经取消微信关注上网功能';
					// 	// 将错误信息发送出去
					// 	$this->sendMsg ( $fromUsername, $toUsername, $result_error );
					// } else { 
					// 	//检测是否设置通过微信认认证
					// 	$a = $this->getAuth ( $shop ['authmode'] );
					// 	if ($a == false) {
					// 		echo $three;
					// 		exit;
					// 	}
					// }
					// // 获得商家的名字
					// $shopname = $shop ['shopname'];
					// // 获取微信自动认证地址
					// $url = $this->getTokenUrl ( $shop, $fromUsername );
					// // 认证通过显示的首条信息
					// $contentStr = "欢迎光临 " . $shopname . "，上网请直接点击：<a target=\"_blank\"  href=\"$url\">我要上网</a>,回复'wifi'或者'上网'可以再次获取上网权限,回复'帮助'获取更多信息";
					// $this->sendMsg ( $fromUsername, $toUsername, $contentStr );
			 	// 	break;
			 	case 'unsubscribe':
			 		//取消订阅
					// 获得发送方名称
					$user = $this->load_userinfo ( $fromUsername );
					// 获得发送方的id
					$uid = $user['id'];
					// 获得发送方的路由id
					$routeid = $user['routeid'];
					// 获得发送方的MAC地址
					$mac = $user['mac'];
					// 获得当前时间
					$tm = time ();
					// 删除授权
					$db = D ( 'AuthlistModel' );
					// $sql2 = "UPDATE wifi_authlist SET over_time = $tm WHERE  mac  = '"+$mac+"'"; //授权时间取消
					//$sql2 = "DELETE FROM `wifi_authlist` WHERE `mac`= '$mac'";//清除所有这台机器的授权
					// $db->query ( $sql2 );
					// 删除授权
					// $db = D ( 'AuthlistModel' );
					// $sql2 = "UPDATE `wifi_authlist` SET `over_time` = '$tm' WHERE `uid` = $uid"; //授权时间取消
					$sql2 = 'DELETE FROM wifi_authlist WHERE uid ='.$uid;
					$db->query( $sql2 );
					// 删除用户
					$db3 = D ( 'Member' );
					$sql3 = 'DELETE FROM wifi_member WHERE id ='.$uid;
					$db3->query( $sql3 );
					// 删除授权
					// $db = D ( 'Member' );
					// $sql2 = "DELETE FROM `wifi_authlist` WHERE `weixin_openid`= '$fromUsername' and `shop_weixin_id`= '$toUsername'"; //清除所有这台机器的授权
					// $db->query ( $sql2 );
			 		break; 			 	
			 	case 'CLICK':
			 		if (!empty ($three )) {
			 			// 获得关键字
						$key = trim($postObj->EventKey);
						// 关键字不为空
						if($key=="上网" || $key=="wifi"){
							// 发送信息给用户
							// 获得商家的名字
							$shopname = $shop ['shopname'];
							// 获取微信自动认证地址
							$url = $this->getTokenUrl ( $shop, $fromUsername );
							// 发送通过认证后的首条信息
							$contentStr = "欢迎光临 " . $shopname . "，上网请直接点击：<a target=\"_blank\"  href=\"$url\">我要上网</a>,回复'wifi'或者'上网'可以再次获取上网权限,回复'帮助'获取更多信息";
							$this->sendMsg ( $fromUsername, $toUsername, $contentStr );
						}
					}
			 		break;
			 	default:
			 		echo $three;
			 		break;
			 }
		}

		// 获得发送过来得文本内容
		$keyword = trim ( $postObj->Content );
		if (! empty ( $keyword )) {
			//$shop = $this->load_shopinfo ( $weiCode );
			// 关键字为wifi或上网
			if ($keyword == 'wifi' || $keyword == '上网'){
				// P($shop);exit;
				// 当数据库中不存在该商家
				if (empty( $shop )) {
					$result_error = '商家已经取消微信关注上网功能';
					$this->sendMsg ( $fromUsername, $toUsername, $result_error );
				} else {
					//获得认证模式
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
				// 获得商家的名字
				$shopname = $shop ['shopname'];
				// 获取微信自动认证地址
				$url = $this->getTokenUrl ( $shop, $fromUsername );
				// 发送通过认证后的首条信息
				$contentStr = "欢迎光临 " . $shopname . "，上网请直接点击：<a target=\"_blank\"  href=\"$url\">我要上网</a>,回复'wifi'或者'上网'可以再次获取上网权限,回复'帮助'获取更多信息";
				$this->sendMsg ( $fromUsername, $toUsername, $contentStr );
			}else if($keyword == '帮助'){
				$contentStr = "回复'wifi'或者'上网'可以获取上网权限";
				$this->sendMsg ( $fromUsername, $toUsername, $contentStr );
			}
			else{
				// 转发到第三方
				$rs = $this->sendThreePlatform ( $shop, $postStr );
				if (!empty ( $rs )) {
					// 输出第三方的内容
					echo $rs;
					exit ();
				}
				echo '';
			}
		}
		 else {
			// 通过第三方
			$rs = $this->sendThreePlatform ( $shop, $postStr );
			if (! empty ( $rs )) {
				echo $rs;
				exit ();
			}
			echo '';
		}
	}

	/**
	 * [getAuth 获得上网认证方式]
	 * @param  [type] $authmode [description]
	 * @return [type]           [description]
	 */
	private function getAuth($authmode) {
		// 获得当前商家上网的认证方式
		$tmp = explode ( '#', $authmode );
		foreach ( $tmp as $v ) {
			if ($v != '#' && $v != '') {
				$arr [] = $v;
			}
		}
		foreach ( $arr as $v ) {
			$temp = explode ( '=', $v );
			// 微信密码认证
			if (count ( $temp ) > 1 && $temp [0] == '3') {
				return true;
			//微信关注认证 
			} else if ($v == '4') {
				return true;
			}
		}
		return false;
	}

	/**
	 * [sendThreePlatform 第三方微信平台接入]
	 * @param  [type] $shop    [关注的商家]
	 * @param  [type] $postStr [发送的信息]
	 * @return [type]          [description]
	 */
	private function sendThreePlatform($shop, $postStr) {
		$token = $shop ['t_wx_token'];
		if (! empty ( $token )) {
			$nonce = mt_rand ( 1, 1000 );
			$timestamp = time ();
			$tmpArr = array ($token, $timestamp, $nonce );
			sort ( $tmpArr, SORT_STRING );
			$tmpStr = implode ( $tmpArr );
			$signature = sha1 ( $tmpStr );
			// 路由服务地址
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

	/**
	 * [request_by_other description]
	 * @param  [type] $url  [description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function request_by_other($url, $data) {
		$file_contents = "";
		// 判断函数file_get_contents是否已开通
		if (function_exists ( 'file_get_contents' )) {
			$opts = array ('http' => array ('method' => 'POST', 'header' => "Content-type: application/x-www-form-urlencodedrn" . "Content-Length: " . strlen ( $data ) . "rn", 'content' => $data ) );
			$context = stream_context_create ( $opts );
			$html = null;
			for($i = 0; $i < 5; $i ++) {
				$file_contents = @file_get_contents ( $url, false, $context ); 
				//php.ini中，有这样两个选项:allow_url_fopen =on(表示可以通过url打开远程文件)，user_agent="PHP"（表示通过哪种脚本访问网络，默认前面有个 " ; " 去掉即可。）重启服务器。
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
	
	/**
	 * [sendMsg 发送信息]
	 * @param  [type] $fromUsername [发送方]
	 * @param  [type] $toUsername   [接收方]
	 * @param  [type] $contentStr   [description]
	 * @return [type]               [description]
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

	/**
	 * [checkSignature 检测签名]
	 * @param  [type] $weiCode [已经加密的签名]
	 * @return [type]          [description]
	 */
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
		// $a = decrypt_p();
		$shopname = rawurlencode ( $shop ['shopname'] );
		$url = "http://www.baidu.com/?token=" . $token; //
		return $url;
	}

	/**
	 * [checkOpenId description]
	 * @param  [type] $shop   [商户信息]
	 * @param  [type] $openId [公众平台账号]
	 * @return [type]         [description]
	 */
	private function checkOpenId($shop, $openId) {
		$db = D ( 'Member' );
		// 获得所有关注某商家公众平台的用户
		$sql = "SELECT * FROM wifi_member WHERE `weixin_openid`='$openId'";
		$info = $db->query ( $sql );
		// 不存在关注当前商家公众平台的用户
		if (empty ( $info ) || ! isset ( $info [0] ) || empty ( $info [0] ['token'] )) {
			$token = md5 ( uniqid () );
			$now = time ();
			// 对公众哈号进行加密
			$password = md5 ( $openId );
			// 获得当前的时间(2015-01-02)
			$nowdate = getNowDate ();
			//商品id
			$shopId = $shop ['id'];
			// 
			$shopWxId = $shop ['weixin_id'];
			$sql2 = "INSERT INTO wifi_member(token,user,password,shopid,browser,mode,add_time,update_time,login_time,add_date,weixin_openid,shop_weixin_id) VALUES('$token','$token','$password','$shopId','weixin','3','$now','$now','$now','$nowdate','$openId','$shopWxId')";
			$db->query ( $sql2 );
	 		$db = D ( 'Member' );
			$sql = "SELECT * FROM wifi_member WHERE `weixin_openid`='$openId'";
			$info = $db->query ( $sql );
	       	$tranDb=new Model();
				$arrdata['uid']=$info[0]['id'];
				$arrdata['add_date']=getNowDate();
				$arrdata['over_time']=$this->getLimitTime($shop);
				$arrdata['update_time']=$now;//更新时间
				$arrdata['login_time']=$now;//首次登录时间
				$arrdata['last_time']=$now;//最后在线时间
				$arrdata['shopid']=$shop['id'];
				$arrdata['token']=$token;
	            $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);  
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
			$arrdata['uid']=$info[0]['id'];
			$arrdata['add_date']=getNowDate();
			$arrdata['over_time']=$this->getLimitTime($shop);
			$arrdata['update_time']=$now;//更新时间
			$arrdata['login_time']=$now;//首次登录时间
			$arrdata['last_time']=$now;//最后在线时间
			$arrdata['shopid']=$shop['id'];
			$arrdata['token']=$token;

             $flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);  
		}
		return $token;
	}

	/**
	 * [getLimitTime 获得上网限制时间]
	 * @param  [type] $shop 商家信息
	 * @return [type]       [description]
	 */
	private function getLimitTime($shop) {
		if ($shop ['timelimit'] != "" && $shop ['timelimit'] != "0") {
			import ( "ORG.Util.Date" );
			$dt = new Date ( time () );
			$date = $dt->dateAdd ( $shop ['timelimit'], 'n' ); //默认7天试用期
			return strtotime ( $date );
		}
		return "";
	
	}

	/**
	 * [load_shopinfo 加载商家信息]
	 * @param  [type] $weiCode 商家微信号
	 * @return [type]          [description]
	 */
	private function load_shopinfo($weiCode) {
		$db = D ( 'shop' );
		$sql = "SELECT * FROM wifi_shop WHERE `weixin_id`='$weiCode'";
		$rs = $db->query ( $sql );
		return $rs [0];
	}

	/**
	 * [load_userinfo 加载用户信息]
	 * @param  [type] $openId [description]
	 * @return [type]         [description]
	 */
	private function load_userinfo($openId) {
		$db = D ( 'Member' );
		$sql = "SELECT * FROM wifi_member WHERE `weixin_openid`='$openId'";
		$rs = $db->query ( $sql );
		return $rs [0];
	}

}