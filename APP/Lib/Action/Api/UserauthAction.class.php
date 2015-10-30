		<?php
class UserAuthAction extends BaseApiAction {
	private $shop = false;
	// 模板路径
	private $tplkey = "";
	public function index() {
	
	}
	/*
	 * 加载商户信息
	 */
	private function load_shopinfo() {
		// 网关id不为空
		if (cookie ( 'gw_id' ) != null) {
			// 路由与商户表关联
			$sql = "select a.id,a.gw_id,a.shopid,a.routename,b.shopname,b.authmode,b.timelimit ,b.pid,b.tpl_path,a.hotspotname,a.hotspotpass,b.smsstatus,b.wxurl,b.codeimg from " . C( 'DB_PREFIX' ) . "routemap a left join " . C( 'DB_PREFIX' ) . "shop b on a.shopid=b.id ";
			$sql .= " where a.gw_id='" . cookie ( 'gw_id' ) . "' limit 1";
			$dbmap = D ( 'Routemap' );
			$info = $dbmap->query ( $sql );
			if ($info != false) {
				cookie ( 'shopid', $info [0] ['shopid'] ); //代理商
				cookie ( 'pid', $info [0] ['pid'] ); //代理商
				// 修改shop状态
				$this->shop = $info;
				$this->tplkey = $info [0] ['tpl_path'];
				// 分配商户信息
				$this->assign ( "shopinfo", $info );
			}
			$dbmap = null;
		}
	}
	/*
	 * 微信密码认证页面
	 */
	public function wxauth() {
		// 获得并分配商户信息
		$this->load_shopinfo ();
		// 认证模式
		$authmode = $this->shop [0] ['authmode'];

		$tmp = explode ( '#', $authmode );
		foreach ( $tmp as $v ) {
			if ( $v != '') {
				$arr [] = $v;
			}
		}
		
		foreach ( $arr as $v ) {
			
			$temp = explode ( '=', $v );
			
			if (count ( $temp ) > 1 && $temp [0] == '3') {
				
				$auth ['wx'] = $temp [1];
				break;
			}
		}
		$wx = json_decode ( $auth ['wx'] );
		// 分配微信号
		$this->assign ( 'wxname', $wx->user );
		if (empty ( $this->tplkey ) || $this->tplkey == "" || strtolower ( $this->tplkey ) == "default") {
			$this->display ();
		} else {
			$this->display ( "wxauth$" . $this->tplkey );
		}
	}
	/*
	 * 微信密码认证处理
	 */
	public function dowxauth() {
		// 获得并分配商户信息
		$this->load_shopinfo ();
		$pwd = I ( 'post.password' );
		if ($pwd == "") {
			$data ['msg'] = "请输入上网认证密码";
			$data ['error'] = 1;
			$this->ajaxReturn ( $data );
			exit ();
		}
		
		$authmode = $this->shop [0] ['authmode'];
		$tmp = explode ( '#', $authmode );
		foreach ( $tmp as $v ) {
			if ($v != '') {
				
				$arr [] = $v;
			}
		}
		foreach ( $arr as $v ) {
			
			$temp = explode ( '=', $v );
			if (count ( $temp ) > 1 && $temp [0] == '3') {
				
				$auth ['wx'] = $temp [1];
			}
		}
		
		if (! empty ( $auth ['wx'] )) {
			$wx = json_decode ( $auth ['wx'] );
			if ($pwd != $wx->pwd) {
				$data ['msg'] = "上网认证密码不正确";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			$token = md5 ( uniqid () );
			$now = time ();
			$tranDb = new Model ();
			
			$_POST ['user'] = uniqid ();
			$_POST ['password'] = md5 ( '123456' );
			$_POST ['phone'] = '';
			$_POST ['shopid'] = $this->shop [0] ['shopid'];
			$_POST ['routeid'] = $this->shop [0] ['id'];
			$_POST ['browser'] = $this->browser;
			$_POST ['add_time'] = $now;
			$_POST ['update_time'] = $now;
			$_POST ['login_time'] = $now;
			$_POST ['mac'] = cookie('mac');
			$_POST ['add_date'] = getNowDate ();
			unset ( $_POST ['__hash__'] );
			unset ( $_POST ['smscode'] );
			
			$_POST ['mode'] = '3'; //无需认证
			$_POST ['online_time'] = $this->getLimitTime ();
			C ( 'TOKEN_ON', false );
			
			$tranDb->startTrans ();
			$flag = $tranDb->table ( C ( 'DB_PREFIX' ) . 'member' )->add ( $_POST );
			$newid = $tranDb->getLastInsID ();
			$arrdata ['uid'] = $newid;
			$arrdata ['add_date'] = getNowDate ();
			$arrdata ['over_time'] = $this->getLimitTime ();
			$arrdata ['update_time'] = $now; //更新时间
			$arrdata ['login_time'] = $now; //首次登录时间
			$arrdata ['last_time'] = $now; //最后在线时间
			$arrdata ['shopid'] = $this->shop [0] ['shopid'];
			$arrdata ['routeid'] = $this->shop [0] ['id'];
			$arrdata ['browser'] = $this->browser;
			$arrdata ['mac'] = cookie('mac'); //无需认证
			$arrdata ['login_ip'] = $this->getIP(); //无需认证
			$arrdata ['token'] = $token;
			$arrdata ['mode'] = '3'; //微信
			$arrdata ['agent'] = $this->agent;
			$flagauth = $tranDb->table ( C ( 'DB_PREFIX' ) . 'authlist' )->add ( $arrdata );
			
			if ($flag & $flagauth) {
				$tranDb->commit ();
				cookie ( 'token', $token );
				cookie ( 'mid', $newid );
				$data ['error'] = 0;
				$data ['url'] = U ( "userauth/showtoken" );
				$this->ajaxReturn ( $data );
			} else {
				$tranDb->rollback ();
				Log::write ( "微信认证错误:" . $tranDb->getLastSql () );
				$data ['msg'] = "认证操作失败，请稍候再试";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
			}
		
		} else {
			
			$data ['error'] = 1;
			$data ['msg'] = '认证操作失败,请稍候再试';
			$this->ajaxReturn ( $data );
		
		}
	
	}
	// 通过手机认证验证上网
	public function smslogin() {
		// 判断是否有post数据提交过来
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			$this->load_shopinfo ();
			// 获得用户手机号码
			$user = I ( 'post.user' );
			// 获得认证码
			$pwd = I ( 'post.smscode' );
			// 检测用户登录信息
			if (empty ( $user ) || empty ( $pwd ) || $user == "" || $pwd == "") {
				$data ['msg'] = "提交参数不完整，登录失败";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			if (! isPhone ( $user )) {
				$data ['msg'] = "请填写有效的11位手机号码";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			if ($pwd == "" || ! isSmsCode ( $pwd )) {
				$data ['msg'] = "验证码不正确";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			$se = session ( 'smscode' );
			if (empty ( $se )) {
				$data ['msg'] = "验证码不能为空,请输入验证码";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			
			if ($se != $pwd) {
				$data ['msg'] = "验证码不正确,请重新输入";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			$userdb = D ( 'Member' );
			// 用户的手机号码
			$where ['user'] = $user;
			$where ['routeid'] = $this->shop [0] ['id'];
			// 获得当前商户信息
			$info = $userdb->where ( $where )->find ();
			// 当不存在当前用户信息时，执行添加动作
			if ($info == false) {
				//添加用户
				C ( 'TOKEN_ON', false );
				$token = md5 ( uniqid () );
				// 获得现在的添加时间
				$now = time ();
				// 账户密钥
				$_POST ['token'] = $token;
				// 账户密码
				$_POST ['password'] = md5 ( $user );
				// 用户手机号码
				$_POST ['phone'] = $user;
				// 使用的浏览器
				$_POST ['browser'] = $this->browser;
				// 认证模式0:注册认证;1:手机认证;2:无需认证;
				// 3:微信密码认证;4:微信关注认证 
				$_POST ['mode'] = '1'; //手机认证

				// 添加时间、更新时间、登录时间
				$_POST ['add_time'] = $now;
				$_POST ['update_time'] = $now;
				$_POST ['login_time'] = $now;
				// 当前的用户的物理地址
				$_POST ['mac'] = cookie('mac'); 
				//开始上网时间
				$_POST ['add_date'] = getNowDate ();
				unset ( $_POST ['__hash__'] );
				unset ( $_POST ['smscode'] );
				if ($this->shop != false) {
					$_POST ['shopid'] = $this->shop [0] ['shopid'];
					$_POST ['routeid'] = $this->shop [0] ['id'];
					// 上网在线有效期
					$_POST ['online_time'] = $this->getLimitTime ();
				}

				$tranDb = new Model ();
				// 启动事务
				$tranDb->startTrans ();
				// 向wifi_member表中添加数据
				$flag = $tranDb->table ( C ( 'DB_PREFIX' ) . 'member' )->add ( $_POST );
				// 返回最后插入的ID
				$newid = $tranDb->getLastInsID ();
				$arrdata ['uid'] = $newid;
				$arrdata ['add_date'] = getNowDate ();
				$arrdata ['over_time'] = $this->getLimitTime ();
				$arrdata ['update_time'] = $now; //更新时间
				$arrdata ['login_time'] = $now; //首次登录时间
				$arrdata ['last_time'] = $now; //最后在线时间
				$arrdata ['shopid'] = $this->shop [0] ['shopid'];
				$arrdata ['routeid'] = $this->shop [0] ['id'];
				$arrdata ['browser'] = $this->browser;
				$arrdata ['token'] = $token;
				$arrdata ['mac'] = cookie('mac'); //无需认证
				$arrdata ['login_ip'] = $this->getIP(); //无需认证
				$arrdata ['mode'] = '1'; //无需认证
				$arrdata ['agent'] = $this->agent;

				// 向wifi_authlist表中添加数据
				$flagauth = $tranDb->table ( C ( 'DB_PREFIX' ) . 'authlist' )->add ( $arrdata );
				
				if ($flag & $flagauth) {
					// 提交事务
					$tranDb->commit ();
					cookie ( 'token', $_POST ['token'] );
					cookie ( 'mid', $newid );
					$data ['error'] = 0;
					$data ['url'] = U ( "userauth/showtoken" );
					$this->ajaxReturn ( $data );
				} else {
					// 事务进行回滚
					$tranDb->rollback ();
					Log::write ( "手机认证错误:" . $tranDb->getLastSql () );
					$data ['msg'] = "验证失败，请稍候再试";
					$data ['error'] = 1;
					$this->ajaxReturn ( $data );
				}
			
			} else {
				//更新用户
				$token = md5 ( uniqid () );
				$now = time ();
				$tranDb = new Model ();
				
				$save ['token'] = $token;
				$save ['online_time'] = $this->getLimitTime ();
				$save ['update_time'] = $now;
				$save ['login_time'] = $now;
				$arrdata ['uid'] = $info ['id'];
				$arrdata ['add_date'] = getNowDate ();
				$arrdata ['over_time'] = $this->getLimitTime ();
				$arrdata ['update_time'] = $now; //更新时间
				$arrdata ['login_time'] = $now; //首次登录时间
				$arrdata ['last_time'] = $now; //最后在线时间
				$arrdata ['shopid'] = $this->shop [0] ['shopid'];
				$arrdata ['routeid'] = $this->shop [0] ['id'];
				$arrdata ['browser'] = $this->browser;
				$arrdata ['token'] = $token;
				$arrdata ['mode'] = '1'; //无需认证
				$arrdata ['agent'] = $this->agent;
				// 同时向member和authlist表中添加修改数据
				$flag = $tranDb->table ( C ( 'DB_PREFIX' ) . 'member' )->where ( $where )->save ( $save );
				$flagauth = $tranDb->table ( C ( 'DB_PREFIX' ) . 'authlist' )->add ( $arrdata );
				// 两表数据添加成功时
				if ($flag & $flagauth) {
					// 提交事务
					$tranDb->commit ();
					cookie ( 'token', $token );
					cookie ( 'mid', $info ['id'] );
					$data ['error'] = 0;
					// 
					$data ['url'] = U ( "userauth/showtoken" );
					$this->ajaxReturn ( $data );
				} else {
					$tranDb->rollback ();
					$data ['msg'] = "验证失败，请稍候再试";
					$data ['error'] = 1;
					$this->ajaxReturn ( $data );
				}
			
			}
		
		}
	}
	// 获取认证码
	public function smscode() {
		
		import ( "ORG.Util.String" );
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			// 加载商户信息
			// $this->load_shopinfo ();
			// 网关id不为空
			// P($_SERVER);
			if (cookie ( 'gw_id' ) != null) {
				// 路由与商户表关联
				$sql = "select a.id,a.gw_id,a.shopid,a.routename,b.shopname,b.authmode,b.timelimit ,b.pid,b.tpl_path,a.hotspotname,a.hotspotpass,b.smsstatus,b.wxurl from " . C ( 'DB_PREFIX' ) . "routemap a left join " . C ( 'DB_PREFIX' ) . "shop b on a.shopid=b.id ";
				$sql .= " where a.gw_id='" . cookie ( 'gw_id' ) . "' limit 1";
				
				$dbmap = D ( 'Routemap' );
				$info = $dbmap->query ( $sql );
				if ($info != false) {
					cookie ( 'shopid', $info [0] ['shopid'] ); //代理商
					cookie ( 'pid', $info [0] ['pid'] ); //代理商
					// 修改shop状态
					$this->shop = $info;
					$this->tplkey = $info [0] ['tpl_path'];
					// 分配商户信息
					$this->assign ( "shopinfo", $info );
					
				}
				$dbmap = null;
			}
			// 获得当前上网用户的手机号码
			$phone = I ( 'post.phone' );
			// 判断是否为手机号码
			if (isPhone ( $phone )) {
				// 实例化一个member对象
				$userdb = D ( 'Member' );
				$where ['phone']= $phone;
				$where ['routeid'] = $this->shop [0] ['id'];
				// 获得当前用户的信息
				$info = $userdb->where ( $where )->find ();
				// P($info);exit;
				// 获得6位数字的认证码
				$code = String::randString ( 6, '1' );
				// 如果没有当前用户信息就让用户进行注册
				if ($info == false){
					// 注册号码
					$_POST['phone']=$phone;
					// 注册用户名
					$_POST['user']=$phone;
					//路由id
					$_POST['routeid']=$this->shop[0]['id'];
					// 认证模式
					$_POST['mode']='1';
					$token = md5 ( uniqid () );
					$_POST ['token'] = $token;
					$_POST ['shopid'] = $this->shop [0] ['shopid'];
					$_POST ['browser'] = $this->browser;
					$_POST ['mac'] = cookie('mac');
					$now=time();
					$_POST ['add_time'] = $now;
					$_POST ['update_time'] = $now;
					$_POST ['login_time'] = $now;
					$_POST ['add_date'] = getNowDate ();
					$_POST ['online_time'] = $this->getLimitTime ();
					$userdb->add($_POST);	
				}
				// 将认证码存到$_SESSION['smscode']中
				session ( 'smscode', $code );
				$this->assign('smsstatus',$this->shop[0]['smsstatus']);
				// 删除之前的条件
				unset($where);
				// 1:短信认证;0:虚拟验证
				if($this->shop[0]['smsstatus']==1){
					// 获得商家的短信账号
					$where['uid']=$this->shop[0]['shopid'];
					// 获得当前商户的发送信息的配置信息
					$info =M('Smscfg','wifi_')->where ( $where )->find ();
					// 获得该商家的上网的短信模板
					$smsmsg=M('Shop','wifi_')->where($where)->field('authmsg,companyname')->find();
					// P($this->shop[0]['shopid']);
					// P($this->shop[0]['smsstatus']);
					$authmsg=explode('*', $smsmsg['authmsg']);
					// 组合短信模板
					$msg=$smsmsg['companyname'].$authmsg[0].$code.$authmsg[1];
					// 发送短信服务器端的url
					$server = C ( 'SMSURL' );
					// 获得账号
					$u = $info ['user'];
					// 获得密码
					$p = $info ['password'];
					// exit;
					// 获得发送连接
					$url = $server . "?cpName=" . $u . "&cpPwd=" . $p . "&phones=" . $phone . "&msg=" .rawurlencode(iconv("UTF-8","GB2312",$msg));
					// urlencode(iconv("UTF-8","GB2312//IGNORE",$msg));
					// 获得链接问题
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
					import ( '@.ORG.XCSMS' );
					// 调用XCSMS发送类
					$client=new XCSMS($server, $u, $p);
					// 发送手机信息
					$rs=$client->SendSms($phones,$msg);
					$data['status']=1;
					$data ['msg'] = "验证码短信已发送，请注意查收！";
					$data ['error'] = 0;
					$this->ajaxReturn ( $data );
					exit ();
				}else{
					// 虚拟认证
					$data['status']=0;
					$data ['msg'] = $code;
					$data ['error'] = 0;
					$this->ajaxReturn ( $data );
					exit;
				}
			} else {
				$data ['msg'] = "请填写有效的11位手机号码";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
		}
	}
	

	// 手机上网登录界面
	public function mobile() {
		$this->load_shopinfo ();
		import ( "ORG.Util.String" );
		// 将上网验证码存入到cookie中
		cookie ( 'smscode', String::randString ( 6, '1' ) );
		$this->assign ( 'smscode', cookie ( 'smscode' ) );
		if (empty ( $this->tplkey ) || $this->tplkey == "" || strtolower ( $this->tplkey ) == "default") {
			$this->display ();
		} else {
			// 显示不同的模板
			$this->display ( 'mobile$' . $this->tplkey );
		}
	}

	public function login() {
		$this->load_shopinfo ();
		if (empty ( $this->tplkey ) || $this->tplkey == "" || strtolower ( $this->tplkey ) == "default") {
			$this->display ();
		} else {
			$this->display ( "login$" . $this->tplkey );
		}
	}

	/**
	 * 登录
	 * Enter description here ...
	 */
	public function dologin() {
		// 引用String工具类
		import ( "ORG.Util.String" );
		// 加载商户信息
		$this->load_shopinfo ();
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			// 实例化一个Member对象
			$db = D ( 'Member' );
			// 获得登录的账户和密码
			$user = I ( 'post.user' );
			$pwd = I ( 'post.password' );
			// 检测登录信息
			if (empty ( $user ) || empty ( $pwd ) || $user == "" || $pwd == "") {
				$data ['msg'] = "提交参数不完整，登录失败";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			if (! validate_user ( $user )) {
				$data ['msg'] = "用户名不合法";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			$where ['user'] = $user;
			$info = $db->where ( $where )->find ();
			if ($info == false) {
				$data ['msg'] = "用户名不存在";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			} else {
				if ($info ['password'] != md5 ( $pwd )) {
					$data ['msg'] = "用户名密码不正确";
					$data ['error'] = 1;
					$this->ajaxReturn ( $data );
					exit ();
				}
				// 登录的时间
				$now = time();
				// 密钥
				$token = md5 ( uniqid () );
				// 实例化一个Model基类对象
				$tranDb = new Model ();
				// 将浏览器、在线时间、更新时间、认证方式、上网机器的物理地址存放到$save数组中
				$save ['token'] = $token;
				$save ['browser'] = $this->browser;
				$save ['online_time'] = $this->getLimitTime ();
				$save ['update_time'] = $now;
				$save ['login_time'] = $now;
				$save ['mode'] = '0'; //无需认证
				$save ['mac'] = cookie('mac');
				// 将用户id、添加时间、上网超时、更新时间、首次登录时间、最后登录时间、商户id、上网浏览器、密钥、物理地址、登录IP、代理
				$arrdata ['uid'] = $info ['id'];
				$arrdata ['add_date'] = getNowDate ();
				$arrdata ['over_time'] = $this->getLimitTime ();
				$arrdata ['update_time'] = $now; //更新时间
				$arrdata ['login_time'] = $now; //首次登录时间
				$arrdata ['last_time'] = $now; //最后在线时间
				$arrdata ['shopid'] = $this->shop [0] ['shopid'];
				$arrdata ['routeid'] = $this->shop [0] ['id'];
				$arrdata ['browser'] = $this->browser;
				$arrdata ['token'] = $token;
				$arrdata ['mac'] = cookie('mac'); //无需认证
				$arrdata ['login_ip'] = $this->getIP(); //无需认证
				$arrdata ['mode'] = '0'; //无需认证
				$arrdata ['agent'] = $this->agent;
				$flag = $tranDb->table ( C ( 'DB_PREFIX' ) . 'member' )->where ( $where )->save ( $save );
				$flagauth = $tranDb->table ( C ( 'DB_PREFIX' ) . 'authlist' )->add ( $arrdata );
				if ($flag & $flagauth) {
					$tranDb->commit ();
					cookie ( 'token', $token );
					cookie ( 'mid', $info ['id'] );
					$data ['error'] = 0;
					$data ['url'] = U ( "userauth/showtoken" );
					$this->ajaxReturn ( $data );
				} else {
					$tranDb->rollback ();
					$data ['msg'] = "登录失败，请稍候再试";
					$data ['error'] = 1;
					$this->ajaxReturn ( $data );
				}
			}
		
		}
	}

	/**
	 * [reg 用户注册]
	 * @return [type] [description]
	 */
	public function reg() {
		$this->load_shopinfo ();
		// 显示默认的注册页面
		if (empty ( $this->tplkey ) || $this->tplkey == "" || strtolower ( $this->tplkey ) == "default") {
			$this->display ();
		} else {	
			$this->display ( "reg$" . $this->tplkey );
		}
	}
	
	/**
	 * 注册
	 * Enter description here ...
	 */
	public function regu() {
		$this->load_shopinfo ();
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			$user = I ( 'post.user' );
			$pwd = I ( 'post.password' );
			$phone = I ( 'post.phone' );
			if (! validate_user ( $user )) {
				$data ['msg'] = "用户名必须是3到20位数字或字母组成";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			if (! isPhone ( $phone )) {
				$data ['msg'] = "请填写有效的11位手机号码";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			$db = D ( 'Member' );
			$where ['user'] = $user;
			$where ['routeid'] = $this->shop [0] ['id'];
			
			$info = $db->where ( $where )->find ();
			
			if ($info != false) {
				$data ['msg'] = "当前帐号已存在";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
			}
			$token = md5 ( uniqid () );
			$now = time ();
			$tranDb = new Model ();
			$_POST ['token'] = $token;
			unset ( $_POST ['__hash__'] );
			unset ( $_POST ['smscode'] );
			$_POST ['password'] = md5 ( $_POST ['password'] );
			$_POST ['shopid'] = $this->shop [0] ['shopid'];
			$_POST ['routeid'] = $this->shop [0] ['id'];
			$_POST ['browser'] = $this->browser;
			$_POST ['mode'] = '0'; //注册认证
			$_POST ['mac'] = cookie('mac');
			$_POST ['add_time'] = $now;
			$_POST ['update_time'] = $now;
			$_POST ['login_time'] = $now;
			$_POST ['add_date'] = getNowDate ();
			$_POST ['online_time'] = $this->getLimitTime ();
			$tranDb->startTrans ();
			$flag = $tranDb->table ( C ( 'DB_PREFIX' ) . 'member' )->add ( $_POST );
			$newid = $tranDb->getLastInsID ();
			$arrdata ['uid'] = $newid;
			$arrdata ['add_date'] = getNowDate ();
			$arrdata ['over_time'] = $this->getLimitTime ();
			$arrdata ['update_time'] = $now; //更新时间
			$arrdata ['login_time'] = $now; //首次登录时间
			$arrdata ['last_time'] = $now; //最后在线时间
			$arrdata ['shopid'] = $this->shop [0] ['shopid'];
			$arrdata ['routeid'] = $this->shop [0] ['id'];
			$arrdata ['browser'] = $this->browser;
			$arrdata ['token'] = $token;
			$arrdata ['mode'] = '0'; //认证方式
			$arrdata ['mac'] = cookie('mac'); //无需认证
			$arrdata ['login_ip'] = $this->getIP(); //无需认证
			$arrdata ['agent'] = $this->agent;

			$flagauth = $tranDb->table ( C ( 'DB_PREFIX' ) . 'authlist' )->add ( $arrdata );
			
			if ($flag & $flagauth) {
				$tranDb->commit ();
				cookie ( 'token', $_POST ['token'] );
				
				cookie ( 'mid', $newid );
				$data ['error'] = 0;
				$data ['url'] = U ( "userauth/showtoken");
				$this->ajaxReturn ( $data );
			} else {
				$tranDb->rollback ();
				Log::write ( "注册认证错误:" . $tranDb->getLastSql () );
				$data ['msg'] = "注册失败，请稍候再试";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
			}
		}
	}
	
	public function showtoken() {
		$this->load_shopinfo ();
		$url = "http://" . cookie ( 'gw_address' ) . ":" . cookie ( 'gw_port' ) . "/wifidog/auth?token=" . cookie ( 'token' );
		$jump = U ( 'User/index' );
		 if(cookie('gw_port')==null){
		  $url=cookie('gw_address')."?username=".$this->shop[0]['hotspotname']."&password=".$this->shop[0]['hotspotpass'];
		 }
		$db=D('adminpushset');
		// 获得广告推广设置信息
		$info=$db->where()->find();
		// P($info);exit;
		if(empty($info)){
			$wait = 5;
			$open = 0;
			$way = 0; //展示时间标准
			$info = array('waitseconds'=>5,'openpush'=>0,'showway'=>0);
		}else{
			// 等待时间10秒
			$wait = $info['waitseconds'];
			// 是否开启广告推广
			$open = $info['openpush'];
			// 
			$way = $info['showway']; //展示时间标准
		}
		$pid = $this->shop [0] ['pid'];
		$agentpush = false;
		
		if (empty ( $pid ) || $pid <= 0) {
			//无代理
//			$wait = C ( 'WAITSECONDS' );
		} else {
			//获取代理商广告信息
			$adset = D ( 'Agentpushset' );
			$awhere ['aid'] = $pid;
			$adinfo = $adset->where ( $awhere )->find ();
			if ($adinfo == false) {
				//无设定
			} else {
				if ($adinfo ['pushflag'] == 1) {
					$agentpush = true;
				}
				if ($way == 1) {
					$wait = $adinfo ['showtime']; //展示时间
				}
			}
		}
		// 推送营销商广告开启
		if ($open == 1) {
			$where ['state'] = 1;
			$where ['startdate'] = array ('elt', time () );
			$where ['enddate'] = array ('egt', time () );
			if ($agentpush) {
				$where ['aid'] = array ('in', '0,' . $pid );
			} else {
				$where ['aid'] = 0;
			}
			$ads = D ( 'Pushadv' )->where ( $where )->order('sort desc')->field ( "id,pic,aid" )->select ();
			/*统计展示*/
			///////////////////////////
			$tr = new Model ();
			$time = time ();
			$tr->startTrans ();
			$arrdata ['showup'] = 1;
			$arrdata ['hit'] = 0;
			$arrdata ['shopid'] = $this->shop [0] ['shopid'];
			$arrdata ['add_time'] = $time;
			$arrdata ['add_date'] = getNowDate ();
			$tmpad= array();
			foreach ( $ads as $k => $v ) {
				$v['pic']=$this->downloadUrl($v['pic']);
				if ($v ['aid'] > 0) {
					$arrdata ['mode'] = 50;
					$arrdata ['agent'] = $v ['aid'];
				} else {
					$arrdata ['mode'] = 99;
					$arrdata ['agent'] = 0;
				}
				$arrdata ['aid'] = $v ['id'];
				$tr->table ( C ( 'DB_PREFIX' ) . "adcount" )->add ( $arrdata );
				$tmpad[] = $v;
			}
			$ads = $tmpad;
			$tr->commit ();
			///////////////////////////
			// P($ads);
			$this->assign ( 'ad', $ads );
		
		}else{
			// 使用商家广告
			$map['uid']=cookie("shopid");
			// 广告位子
			$map['ad_pos']=1;
			$ads=M('ad')->where($map)->order("ad_sort desc ")->select();
			$this->assign("ad",$ads);
		}
		
		$this->assign ( 'info', $info );
		$this->assign ( 'waitsecond', $wait );
		$this->assign ( 'wifiurl', $url );
		$this->assign ( 'jumpurl', $jump );
		// P($this->tplkey);
		// P($ads);exit;
		if (empty ( $this->tplkey ) || $this->tplkey == "" || strtolower ( $this->tplkey ) == "default") {
			
			$this->display ();
		} else {
			
			$this->display ( "showtoken$" . $this->tplkey );
		}
	}
	
	/**
	 * [comments 用户留言]
	 * @return [type] [description]
	 */
	public function comments() {
		$this->load_shopinfo ();
		if (empty ( $this->tplkey ) || $this->tplkey == "" || strtolower ( $this->tplkey ) == "default") {
			$this->display ();
		} else {
			
			$this->display ( "comments$" . $this->tplkey );
		}
	}
	// 获得上网限制时间
	private function getLimitTime() {
		if ($this->shop [0] ['timelimit'] != "" && $this->shop [0] ['timelimit'] != "0") {
			import ( "ORG.Util.Date" );
			$dt = new Date ( time () );
			//默认7天试用期
			$date = $dt->dateAdd ( $this->shop [0] ['timelimit'], 'n' ); 
			// 返回时间戳
			return strtotime ( $date );
		}
		return "";
	
	}
	
	/**
	 * [noAuth 用户一键上网]
	 * @return [type] [description]
	 */
	public function noAuth() {
		$this->load_shopinfo ();
		$now = time ();
		$token = md5 ( uniqid () );
		$tranDb = new Model ();
		//$db=M('Member');
		$_POST ['user'] = uniqid ();
		$_POST ['password'] = md5 ( '123456' );
		$_POST ['phone'] = '';
		$_POST ['shopid'] = $this->shop [0] ['shopid'];
		$_POST ['routeid'] = $this->shop [0] ['id'];
		$_POST ['browser'] = $this->browser;
		$_POST ['token'] = $token;
		$_POST ['mode'] = '2'; //无需认证
		$_POST ['online_time'] = $this->getLimitTime ();
		$_POST ['add_time'] = $now;
		$_POST ['update_time'] = $now;
		$_POST ['login_time'] = $now;
		$_POST ['mac'] = cookie('mac');
		$_POST ['add_date'] = getNowDate ();
		C ( 'TOKEN_ON', false );
		$tranDb->startTrans ();
		
		$flag = $tranDb->table ( C ( 'DB_PREFIX' ) . 'member' )->add ( $_POST );
		$newid = $tranDb->getLastInsID ();
		$arrdata ['uid'] = $newid;
		$arrdata ['add_date'] = getNowDate ();
		$arrdata ['over_time'] = $this->getLimitTime ();
		$arrdata ['update_time'] = $now; //更新时间
		$arrdata ['login_time'] = $now; //首次登录时间
		$arrdata ['last_time'] = $now; //最后在线时间
		$arrdata ['shopid'] = $this->shop [0] ['shopid'];
		$arrdata ['routeid'] = $this->shop [0] ['id'];
		$arrdata ['browser'] = $this->browser;
		$arrdata ['token'] = $token;
		$arrdata ['mode'] = '2'; //无需认证
		$arrdata ['mac'] = cookie('mac'); //无需认证
		$arrdata ['login_ip'] = $this->getIP(); //无需认证
		$arrdata ['agent'] = $this->agent;
		$flagauth = $tranDb->table ( C ( 'DB_PREFIX' ) . 'authlist' )->add ( $arrdata );
		
		if ($flag & $flagauth) {
			$tranDb->commit ();
			cookie ( 'token', $_POST ['token'] );
			cookie ( 'mid', $newid );
			$this->redirect ( "index.php/api/userauth/showtoken" );
		} else {
			$tranDb->rollback ();
			Log::write ( "自动认证错误:" . $tranDb->getLastSql () );
			$this->error ( '服务器异常，请稍候再试' );
		}
	}

	public function wx_f_auth() {
		$this->load_shopinfo ();
		$now = time ();
		$token = md5 ( uniqid () );
		$tranDb = new Model ();
		$start = strtotime ( date ( 'Y-m-d H:i:s' ) );
		//$db=M('Member');
		$save ['user'] = uniqid ();
		//		$save['mac']=cookie('mac');
		$save ['password'] = md5 ( '123456' );
		$save ['phone'] = '';
		$save ['shopid'] = $this->shop [0] ['shopid'];
		$save ['routeid'] = $this->shop [0] ['id'];
		$save ['browser'] = 'weixin_tmp';
		$save ['token'] = $token;
		$save ['mode'] = '2'; //无需认证
		$save ['mac'] = cookie('mac');
		$save ['online_time'] = $start + (5 * 60);
		$save ['add_time'] = $now;
		$save ['update_time'] = $now;
		$save ['login_time'] = $now;
		$save ['add_date'] = getNowDate ();
		C ( 'TOKEN_ON', false );
		$tranDb->startTrans ();
		
		$flag = $tranDb->table ( C ( 'DB_PREFIX' ) . 'member' )->add ( $save );
		$newid = $tranDb->getLastInsID ();
		$arrdata ['uid'] = $newid;
		$arrdata ['add_date'] = getNowDate ();
		
		$arrdata ['over_time'] = $start + (5 * 60); //这里设置5分钟
		$arrdata ['update_time'] = $now; //更新时间
		$arrdata ['login_time'] = $now; //首次登录时间
		$arrdata ['last_time'] = $now; //最后在线时间
		$arrdata ['shopid'] = $this->shop [0] ['shopid'];
		$arrdata ['routeid'] = $this->shop [0] ['id'];
		$arrdata ['browser'] = 'weixin_tmp';
		$arrdata ['token'] = $token;
		$arrdata ['mode'] = '2'; //无需认证
		$arrdata ['mac'] = cookie('mac'); //无需认证
		$arrdata ['login_ip'] = $this->getIP(); //无需认证
		$arrdata ['agent'] = $this->agent;
		$flagauth = $tranDb->table ( C ( 'DB_PREFIX' ) . 'authlist' )->add ( $arrdata );
		
		if ($flag & $flagauth) {
			$tranDb->commit ();
			cookie ( 'token', $token );
			cookie ( 'is_weixin', true );
			cookie ( 'mid', $newid );
			$url = "http://" . cookie ( 'gw_address' ) . ":" . cookie ( 'gw_port' ) . "/wifidog/auth?token=" . $token;
			$this->redirect ( $url, 307 );
		} else {
			$tranDb->rollback ();
			Log::write ( "自动认证错误:" . $tranDb->getLastSql () );
			$this->error ( '服务器异常，请稍候再试' );
		}
	}
	/*
	 * 客户留言
	 */
	public function addmsg() {
		$this->load_shopinfo ();
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			$user = I ( 'post.user' );
			
			$phone = I ( 'post.phone' );
			$info = I ( 'post.content' );
			
			if (! isPhone ( $phone )) {
				$data ['msg'] = "请填写有效的11位手机号码";
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
				exit ();
			}
			
			$db = D ( 'Comment' );
			$_POST ['shopid'] = $this->shop [0] ['shopid'];
			$_POST ['routeid'] = $this->shop [0] ['id'];
			if ($db->create ()) {
				if ($db->add ()) {
					$newid = $db->getLastInsID ();
					$data ['error'] = 0;
					$this->ajaxReturn ( $data );
				} else {
					$data ['msg'] = "提交留言失败，请稍候再试";
					$data ['error'] = 1;
					$this->ajaxReturn ( $data );
				}
			} else {
				$data ['msg'] = $db->getError ();
				$data ['error'] = 1;
				$this->ajaxReturn ( $data );
			}
		}
	}
	
	/*
	 * 广告显示
	 */
	public function showad() {
		$this->load_shopinfo ();
		$id = I ( 'get.id', '0', 'int' );
		$db = D ( 'Ad' );
		$where ['id'] = $id;
		$where ['uid'] = $this->shop [0] ['shopid'];
		
		$info = $db->where ( $where )->find ();
		
		/*统计展示*/
		///////////////////////////
		$tr = new Model ();
		$time = time ();
		$tr->startTrans ();
		$arrdata ['showup'] = 0;
		$arrdata ['hit'] = 1;
		$arrdata ['shopid'] = $this->shop [0] ['shopid'];
		$arrdata ['add_time'] = $time;
		$arrdata ['add_date'] = getNowDate ();
		$arrdata ['mode'] = 1;
		
		$arrdata ['aid'] = $id;
		$tr->table ( C ( 'DB_PREFIX' ) . "adcount" )->add ( $arrdata );
		
		$tr->commit ();
		///////////////////////////
		$this->assign ( 'adinfo', $info );
		$this->display ();
	
	}
	/**
	 * 绑定微信
	 * Enter description here ...
	 */
	public function bindwx() {
		$token = $_GET ['token'];
		$mac = $_GET ['mac'];
		$db = D ( 'Member' );
		$where ['token'] = $token;
		$info = $db->where ( $where )->find ();
		if ($info) {
			$this->load_shopinfo ();
			$route = $this->shop [0];
			
			if (empty ( $route )) { //不在店内
				echo '请在商家店内使用wifi';
				exit ();
			}
			$shopId = $route ['shopid'];
			$where2 ['id'] = $shopId;
			$db2 = D ( 'Shop' );
			$shop2 = $db2->where ( $where2 )->find ();
			if (empty ( $shop2 )) {
				echo '商家不存在';
				exit ();
			}
			if (empty ( $shop2 ['weixin_id'] )) {
				echo '商家未设置微信关注';
				exit ();
			}
			if ($shop2 ['weixin_id'] != $info ['shop_weixin_id']) {
				echo '请先关注商家微信号';
				exit ();
			}
			//if (empty ( $mac )) {
			//	echo '微信认证失败,请重试';
			//	exit ();
			//}
			
			$tm = time();
			
			$start = strtotime ( date ( 'Y-m-d H:i:s' ) );
			$tmp_uid = cookie ( 'mid' );
			//TODO 删除临时授权
			$db = D ( 'AuthlistModel' );
			$sql2 = "UPDATE `wifi_authlist` SET `over_time` = '$tm' WHERE `uid` = $tmp_uid";//授权时间取消
			//$sql2 = "DELETE FROM `wifi_authlist` WHERE `uid` = $tmp_uid";
			$db->query ( $sql2 );
			//TODO 删除临时用户
			$db3 = D ( 'Member' );
			$sql3 = "DELETE FROM `wifi_member` WHERE `id` = $tmp_uid";
			$db3->query ( $sql3 );
			
			
			$now = time ();
			if(empty($mac)){
				$mac = cookie('mac');
			}
			if(empty($mac)){
				$mac = '';
			}
			if(!empty($mac)){
				// 删除授权
				$dbal = D ( 'AuthlistModel' );
				$sql4 = "UPDATE `wifi_authlist` SET `over_time` = '$tm' WHERE `mac`= '$mac'";//授权时间取消
				//$sql4 = "DELETE FROM `wifi_authlist` WHERE `mac`= '$mac'";//清除所有这台机器的授权
				$dbal->query ( $sql4 );
			}
			$token2 = md5 ( uniqid () );
			
			$tranDb = new Model ();
			$save ['token'] = $token2;
			$save ['browser'] = 'weixin_1';
			$save ['mac'] = $mac;
			$save ['routeid'] = $route ['id'];
			$save ['online_time'] = $start + (60 * 60 * 24 * 365); //一年有效期; //
			$save ['update_time'] = $now;
			$save ['login_time'] = $now;
			$save ['mode'] = '3'; //无需认证
			$flag = $tranDb->table ( C ( 'DB_PREFIX' ) . 'member' )->where ( $where )->save ( $save );
			
			$arrdata ['uid'] = $info ['id'];
			$arrdata ['add_date'] = getNowDate ();
			$arrdata ['over_time'] = $start + (60 * 60 * 24 * 365); //一年有效期		`; //
			$arrdata ['update_time'] = $now; //更新时间
			$arrdata ['login_time'] = $now; //首次登录时间
			$arrdata ['last_time'] = $now; //最后在线时间
			$arrdata ['shopid'] = $route ['shopid'];
			$arrdata ['routeid'] = $route ['id'];
			$arrdata ['browser'] = 'weixin_1';
			$arrdata ['token'] = $token2;
			$arrdata ['mode'] = 3; //无需认证
			$arrdata ['mac'] = $mac; //无需认证
			$arrdata ['login_ip'] = $this->getIP(); //无需认证
			$arrdata ['agent'] = $this->agent;
			
			$flagauth = $tranDb->table ( C ( 'DB_PREFIX' ) . 'authlist' )->add ( $arrdata );
			if ($flag && $flagauth) {
				$tranDb->commit ();
				cookie ( 'token', $token2 );
				cookie ( 'mid', $info ['id'] );
				cookie ( 'is_weixin', false );
				$this->redirect ( "index.php/api/userauth/showtoken" );
			} else {
				$tranDb->rollback ();
				if(!$flag){
					$this->error ( '服务器异常，请稍候再试1'.$tranDb->getLastSql () );
				}
				if(!$flagauth){
					$this->error ( '服务器异常，请稍候再试2' );
				}
				//Log::write ( "微信认证错误:" . $tranDb->getLastSql () );
				$this->error ( '服务器异常，请稍候再试3' );
			}
		} else {
			$this->error ( '链接失效，请重新向商家微信发送"wifi"获取授权链接' );
		}
	}

	public function getIP() {
		global $ip;
		if (getenv ( "HTTP_CLIENT_IP" ))
			$ip = getenv ( "HTTP_CLIENT_IP" );
		else if (getenv ( "HTTP_X_FORWARDED_FOR" ))
			$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
		else if (getenv ( "REMOTE_ADDR" ))
			$ip = getenv ( "REMOTE_ADDR" );
		else
			$ip = "Unknow";
		return $ip;
	}

	//$arrdata['uid']=$newid;
//		$arrdata['add_date']=getNowDate();
//		$arrdata['over_time']=$this->getLimitTime();
//		$arrdata['update_time']=$now;//更新时间
//		$arrdata['login_time']=$now;//首次登录时间
//		$arrdata['last_time']=$now;//最后在线时间
//		$arrdata['shopid']=$this->shop[0]['shopid'];
//		$arrdata['routeid']=$this->shop[0]['id'];
//		$arrdata['browser']=$this->browser;
//		$arrdata['token']=$token;
//		$arrdata['mode']='2';//无需认证
//		$arrdata['agent']=$this->agent;
//		$flagauth=$tranDb->table(C('DB_PREFIX').'authlist')->add($arrdata);
//			
//			
//		if($flag&$flagauth){
//			$tranDb->commit();
//			cookie('token',$_POST['token']);
//			cookie('mid',$newid);
//			$this->redirect("index.php/api/userauth/showtoken");
//		}else{
//			$tranDb->rollback();
//			Log::write("自动认证错误:".$tranDb->getLastSql());
//			$this->error('服务器异常，请稍候再试');
//		}


}