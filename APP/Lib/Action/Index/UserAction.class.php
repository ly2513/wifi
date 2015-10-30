<?php
class UserAction extends BaseAction {
	
	public function __construct() {
		parent::__construct ();
		header ( "Content-type:text/html;charset=utf-8" );
	}
	// 获取商户信息
	public function info() {
		$this->isLogin ();
		// 获得当前商户信息
		$uid = session ( 'uid' );
		$info = D ( 'Shop' )->where ( "id = {$uid}" )->field ( 'shopname,province,logo,city,area,address,shopgroup,shoplevel,trade,linker,phone' )->find ();
		$info ['logo'] = $this->downloadUrl ( $info ['logo'] );
		include CONF_PATH . 'enum/enum.php'; //$enumdata
		// 分配商户消费水平及，行业类别
		$this->assign ( 'enumdata', $enumdata );
		// 分配当前商户信息
		$this->assign ( 'info', $info );
		$this->assign ( 'a', 'info' );
		$this->display ();
	}
	// 商户登录首页
	public function index() {
		$this->isLogin ();
		$dbnote = D ( 'Notice' );
		// 获得系统提示信息
		$notes = $dbnote->limit ( 5 )->order ( 'add_time desc' )->select ();
		$this->assign ( 'notice', $notes );
		$this->assign ( 'a', 'index' );
		$this->display ();
	}

	public function doindex() {
		$this->isLogin ();
		if (! is_null ( $_FILES ['img'] ['name'] ) && $_FILES ['img'] ['name'] != "") {
			list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'], $_FILES ['img'] ['tmp_name'] );
			if ($err !== null) {
				$this->error ( '上传失败' );
			} else {
				$_POST ['logo'] = $ret ['key'];
			}
		}
		// 实例化一个商业
		$user = D ( 'Shop' );
		$uid = session ( 'uid' );
		$lv = "";
		// 商业消费水平
		foreach ( $_POST ['shoplevel'] as $K => $v ) {
			$lv .= "#" . $v . "#";
		}
		$_POST ['shoplevel'] = $lv;
		$trade = "";
		// 行业类别
		foreach ( $_POST ['trade'] as $K => $v ) {
			$trade .= "#" . $v . "#";
		}
		$_POST ['trade'] = $trade;
		
		if ($_POST ['phone']) {
			if (! isPhone ( $_POST ['phone'] )) {
				$this->error ( '手机号码不正确' );
			}
		}
		
		$where ['id'] = $uid;
		if ($user->create ( $_POST, 2 )) {
			// 保存修改后的商户信息
			if ($user->where ( $where )->save ()) {
				
				$this->success ( '修改成功' );
			} else {
				// 记录错误日志信息
				Log::write ( $user->getError () );
				$this->error ( '操作出错，请重新操作' );
			}
		} else {
			$this->error ( $user->getError () );
		}
	}

	// 判断是否登录
	private function isLogin() {
		if (! session ( 'uid' )) {
			$this->redirect ( 'index/index/log' );
		}
	}

	// 显示商户登录界面
	public function login() {
		$this->display ();
	}

	// 执行登录检测
	public function dologin() {
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			// 获得商户的用户名和密码
			$user = isset ( $_POST ['user'] ) ? strval ( $_POST ['user'] ) : '';
			$pass = isset ( $_POST ['password'] ) ? strval ( $_POST ['password'] ) : '';
			// 实例化一个商户信息
			$userM = M ( 'Shop' );
			// 对当前商户的密码加密
			$pass = md5 ( $pass );
			// 获得当前商户的账号、商铺名称、代理商id
			$uid = $userM->where ( "account = '{$user}' AND password = '{$pass}'" )->field ( 'id,account,shopname,pid' )->find ();
			//log::write($userM->getLastSql());
			// 将当前的商户信息(账号、商铺名称、代理商id)存放到session中
			if ($uid) {
				session ( 'uid', $uid ['id'] );
				session ( 'user', $uid ['account'] );
				session ( 'shop_name', $uid ['shopname'] );
				session ( 'pid', $uid ['pid'] );
				// 当商户登陆成功后，异步返回登陆成功信息并且跳转到商户首页
				$data ['error'] = 0;
				$data ['msg'] = "";
				$data ['url'] = U ( 'user/index' );
				return $this->ajaxReturn ( $data );
			
		// $this->success('登录成功','index.php?m=User');
			} else {
				// 当商户登陆失败后，异步返回登陆失败信息
				$data ['error'] = 1;
				$data ['msg'] = "帐号信息不正确";
				return $this->ajaxReturn ( $data );
			
			}
		}else {
			// 没有post数据时，显示如下信息
			$data ['error'] = 1;
			$data ['msg'] = "服务器忙，请稍候再试";
			return $this->ajaxReturn ( $data );
		}
	
	}
	// 显示商户注册页面
	public function register() {
		$this->display ();
	}
	// 检查商户注册信息
	public function doregist() {
		// 存在post提交数据
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			
			C ( 'TOKEN_ON', false );
			$hid = isset ( $_POST ['doact'] ) ? strval ( $_POST ['doact'] ) : '';
			if ($hid == 'doreg') {
				$user = D ( 'Shop' );
				$_POST ['mode'] = 0; //注册用户
				$_POST ['authmode'] = '#0#'; //注册用户
				$_POST ['authaction'] = '1'; //认证后调整模式
				$_POST ['password'] = md5 ( $_POST ['password'] ); //对注册用户密码进行加密
				// 自动验证
				if ($user->create ()) {
					// 向shop表中添加商户注册信息
					$aid = $user->add ();
					// 并且将商户注册的信息存放到session中
					if ($aid) {
						session ( 'uid', $aid );
						session ( 'user', $_POST ['account'] );
						session ( 'shop_name', $_POST ['shopname'] );
						// 当商户注册成功后，异步返回注册成功信息并且跳转到商户首页 
						$data ['error'] = 0;
						$data ['msg'] = "OK";
						$data ['url'] = U ( 'user/index' );
						return $this->ajaxReturn ( $data );
					} else {
						// 商户注册信息添加失败
						$data ['error'] = 1;
						$data ['msg'] = "服务器忙，请稍候再试";
						return $this->ajaxReturn ( $data );
					}
				} else {
					// 当商户注册失败后，异步返回注册失败信息并且跳转到商户首页
					$data ['error'] = 1;
					$data ['msg'] = $user->getError ();
					return $this->ajaxReturn ( $data );
				}
			} else {
				// 不执行注册动作
				$data ['error'] = 1;
				$data ['msg'] = "服务器忙，请稍候再试";
				return $this->ajaxReturn ( $data );
			}
		} else {
			// 没有注册的post数据时，执行以下代码
			$data ['error'] = 1;
			$data ['msg'] = "服务器忙，请稍候再试";
			return $this->ajaxReturn ( $data );
		}
	
	}
	// 商户退出登录
	public function logout() {
		session ( null );
		// 跳转到商户登入界面
		$this->redirect ( 'index/index' );
	}

	// 显示当前商户修改密码界面
	public function account() {
		$this->isLogin ();
		$this->assign ( 'a', 'account' );
		$this->display ();
	}
	// 执行当前商户修改密码动作
	public function doaccount() {
		$this->isLogin ();
		$uid = session ( 'uid' );
		$pass = isset ( $_POST ['password'] ) ? $_POST ['password'] : '';
		if ($pass) {
			$user = D ( 'Shop' );
			if ($user->create ()) {
				if ($user->where ( "id = {$uid}" )->save ()) {
					$this->success ( '修改密码成功' );
				} else {
					$this->error ( '修改密码错误' );
				}
			}
		} else {
			$this->error ( '密码不允许为空' );
		}
	}

	// 应用设置
	public function application() {
		// 判断商户是否登录
		$this->isLogin ();
		include CONF_PATH . 'enum/enum.php'; 
		// 分配认证方式
		$this->assign ( 'authmode', $enumdata ['authmode'] );
		// 实例化一个对商户表操作对象
		$db = D ( 'Shop' );
		// 获得当前登录的用户的id
		$uid = session ( 'uid' );
		$where ['id'] = $uid;
		// 获得当前商户的信息
		$info = $db->where ( $where )->field ( 'authmode,wx,notice,authaction,jumpurl,timelimit,sh,eh,countflag,maxcount,weixin_id,weixin_token,smsstatus,companyname,authmsg,wxurl,codeimg' )->find ();

		$this->assign ( 'a', 'application' );
		// 分配当前登录的商户信息
		$this->assign ( 'info', $info );
		// P($uid);
		$this->display ();
	}
	// 向shop表中添加应用设置
	public function doapp() {
		// 判断商户是否登录
		$this->isLogin ();
		
		// 上传二维码
		if (! is_null ( $_FILES ['img'] ['name'] ) && $_FILES ['img'] ['name'] != "") {
			list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'], $_FILES ['img'] ['tmp_name'] );
			if ($err !== null) {
				$this->error ( '上传失败' );
			} else {
				$_POST ['codeimg'] = $ret ['key'];
			}
		}
		$uid = session ( 'uid' );
		// 实例化一个对商户表操作对象
		$db = D ( 'Shop' );
		$where ['id'] = $uid;
		// 获得当前商户信息
		$info = $db->where ( $where )->find ();
		// 认证模式
		$authmode = "";
		// 上网开始时间
		$sh = ( int ) $_POST ['sh'];
		// 上网结束时间
		$eh = ( int ) $_POST ['eh'];
		
		// 判断开始时间和结束时间的(开始时间一定要小于结束时间)
		if ($sh > $eh) {
			$this->error( '上网结束时段不能小于开始时段' );
		}
		// 认证模式
		foreach ( $_POST ['authmode'] as $K => $v ) {
			// 当认证模式为微信密码认证时
			if ($v == '3') {
				// 获得微信密码
				$pwd = $_POST ['wxauthpwd'];
				// 获得微信号
				$ac = $_POST ['wx'];
				$wx ['user'] = $ac;
				$wx ['pwd'] = $pwd;
				// 组合成(#3={"user":"123123","pwd":"123123"}#)
				$authmode .= "#" . $v . "=" . json_encode ( $wx ) . "#";
			} else {
				// 其他认证模式(例如：#1#)
				$authmode .= "#" . $v . "#";
			}
		}
		// print_r($_POST);exit;
		// 获得上网限制状态(0:停用，1:启用)
		$isCount = ( int ) $_POST ['countflag'];
		// 上网状态为启用
		if ($isCount > 0) {
			
			// 上网允许认证次数
			if (empty ( $_POST ['maxcount'] )) {
				$this->error ( '上网允许认证次数不能为空' );
			}
			// 上网时间限制
			if (! isNumber ( $_POST ['maxcount'] )) {
				$this->error ( '上网允许认证次数必须是数字' );
			}
			// 检测上网允许认证次数是否超出范围(1-299)
			$maxcount = ( int ) $_POST ['maxcount'];
			if ($maxcount < 0 || $maxcount > 600) {
				$this->error ( '上网允许认证次数范围在1-600' );
			}
		} else {
			// 默认上网允许认证次数为0次
			$_POST ['maxcount'] = 0;
		}

		//将验证方式压人到post中 
		$_POST ['authmode'] = $authmode;

		// 当没有认证方式时，默认的认证方式为注册验证
		if ($_POST ['authmode'] == null || $_POST ['authmode'] == '') {
			$_POST ['authmode'] = "#0#";
		}

		// 上网时间限制(允许用户上网的时间(单位:分钟)。注:不限制时间请填:0)
		if (! $_POST ['timelimit'] == "") {
			// is_numeric:检测变量是否为数字或数字字符串 
			if (! is_numeric ( $_POST ['timelimit'] )) {
				$this->error ( '输入的上网时间必须是数字类型' );
			}
		}
		// 当认证后行为是1时，跳转到网址为$_POST ['jumpurl'];
		if ($_POST ['authaction'] == 1 && $_POST ['jumpurl'] == "") {

			$this->error ( '请输入要跳转的网址' );
		}
		// 当认证行为后为跳转指定网页 
		if ($_POST ['authaction'] == 1){
			// 检测$_POST ['jumpurl']是不是完整的网址
			if (! isUrl ( $_POST ['jumpurl'] )) {
				$this->error ( '输入的网址必须以http://开始' );
			}
		}
		// 将更新时间压入到post中
		$_POST ['update_time'] = time ();
		// P($_POST);exit;
		// 设置短信模板(默认)
		if(trim($_POST['authmsg'])==''){
			$_POST['authmsg']="欢迎使用无线网络，你的认证码是【*】";
		}
		// 保存修改后的应用设置信息
		if ($db->where( $where )->save ( $_POST )){
			$this->success ( '操作成功' );
		} else{
			$this->error ( '操作失败' );
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
		return $result ;
	}

	public function adv() {
		$this->isLogin ();
		// 引用UserPage工具类
		import ( '@.ORG.UserPage' );
		
		$this->assign ( 'a', 'adv' );
		$uid = session ( 'uid' );
		$where ['uid'] = $uid;
		$ad = D ( 'Ad' );
		$count = $ad->where ( $where )->count ();
		// 实例化一个页码类
		$page = new UserPage ( $count, C ( 'ad_page' ) );
		// 获得当前用户的广告信息
		$result = $ad->where ( $where )->order('ad_sort desc')->field ( 'id,ad_pos,ad_thumb,ad_sort,mode' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		// 分配页码
		$this->assign ( 'page', $page->show () );
		$list = array ();

		foreach ( $result as $rs ) {
			$rs ['ad_thumb'] = $this->downloadUrl ( $rs ['ad_thumb'] );
			$list [] = $rs;
		}
		
		$len=count($list);
        $this->assign ( 'len', $len );
		$this->assign ( 'lists', $list );
		$this->display ();
	}
	
	// 添加广告
	public function addadv() {
		$this->isLogin ();
		// 引用页码工具类
		import ( '@.ORG.UserPage' );
		$uid = session ( 'uid' );
		$where ['uid'] = $uid;
		$where ['ad_pos'] = 1;
		$ad = D ( 'Ad' );
		// 统计当前商户的广告条数
		$count = $ad->where ( $where )->count ();
        $this->assign ( 'len', $count );
		$this->display ();
	}
	// 编辑广告
	public function editad() {
		$this->isLogin ();
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : 0;
		$uid = session ( 'uid' );
		$where ['id'] = $id;
		$where ['uid'] = $uid;
		if ($id) {
			$result = D ( 'Ad' )->where ( $where )->find ();
			$result ['ad_thumb'] = $this->downloadUrl ( $result ['ad_thumb'] );
			$this->assign ( 'info', $result );
			$this->display ();
		} else {
			$this->error ( '无此广告信息' );
		}
	}

	// 执行编辑动作
	public function doeditad() {

		$this->isLogin ();
		$uid = session ( 'uid' );
		$id = I ( 'post.id', '0', 'int' );
		$where ['id'] = $id;
		$where ['uid'] = $uid;
		$db = D ( 'Ad' );
		// 获得当前要修改的广告
		$result = $db->where ( $where )->field ( 'id' )->find ();
		if ($result == false) {
			$this->error ( '无此广告信息' );
			exit ();
		}
		
		//        import('ORG.Net.UploadFile');      
		//        
		//        $upload             = new UploadFile();
		//        $upload->maxSize    = C('AD_SIZE');
		//        $upload->allowExts  = C('AD_IMGEXT');
		//       $upload->savePath   =  C('AD_SAVE');
		

		if (! is_null ( $_FILES ['img'] ['name'] ) && $_FILES ['img'] ['name'] != "") {
			
			list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'], $_FILES ['img'] ['tmp_name'] );
			//7牛上传
			if ($err !== null) {
				$this->error ( '上传失败' );
			} else {
				$this->delete($result['ad_thumb']);//删除旧数据
				$_POST ['ad_thumb'] = $ret ['key'];
			}
		}
		
		if ($result) {
			$_POST ['uid'] = $uid;
			
			if ($db->create ()) {
				if ($db->where ( $where )->save ()) {
					
					$this->success ( '修改成功', U ( 'user/adv', '', true, true, true ) );
				} else {
					$this->error ( '操作出错' );
				}
			} else {
				$this->error ( $db->getError () );
			}
		}
	
	}
	// 删除广告
	public function delad() {
		$this->isLogin ();
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET [id] ) : 0;
		if ($id) {
			$thumb = D ( 'ad' )->where ( "id={$id}" )->field ( "ad_thumb" )->select ();
			if (D ( 'ad' )->delete ( $id )) {
				if (file_exists ( ".{$thumb[0]['ad_thumb']}" )) {
					unlink ( ".{$thumb[0]['ad_thumb']}" );
				}
				$this->success ( '删除成功', U ( 'user/adv' ) );
			} else {
				$this->error ( '操作出错' );
			}
		}
	}
	
	public function doadv() {
		$this->isLogin ();
		$uid = session ( 'uid' );
		list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'], $_FILES ['img'] ['tmp_name'] );
		//7牛上传
		if ($err !== null) {
			$this->error ( '上传失败' );
		} else {
			// $info           =  $upload->getUploadFileInfo();
			$ad = D ( 'Ad' );
			$_POST ['uid'] = $uid;
			
			$_POST ['ad_thumb'] = $ret ['key'];
			$_POST ['ad_sort'] = isset ( $_POST ['ad_sort'] ) ? $_POST ['ad_sort'] : 0;
			if ($ad->create ()) {
				if ($ad->add ()) {
					$this->success ( '添加广告成功', U ( 'User/adv', '', true, true, true ) );
				} else {
					$this->error ( '添加失败，请重新添加' );
				}
			} else {
				$this->error ( '添加失败，请重新添加' );
			}
		
		}
	}
	// 路由信息
	public function route() {
		$this->assign ( 'a', 'route' );
		$this->isLogin ();
		$db = D ( 'Routemap' );
		$uid = session ( 'uid' );
		$where ['shopid'] = $uid;
		$info = $db->where ( $where )->find ();
		$this->assign ( 'info', $info );
		$this->display ();
	}
	// 保存路由信息
	public function saveroute() {
		$this->isLogin ();
		$uid = session ( 'uid' );
		// 实例化一个路由对象
		$db = D ( 'Routemap' );
		// 商户id
		$where ['shopid'] = $uid;
		// 获得当前商户的路由信息
		$info = $db->where ( $where )->find ();
		// 存在当前商户的路由信息
		if (! $info) {
			
			$_POST ['shopid'] = $uid;
			$_POST ['sortid'] = 0;
			if ($db->create ()) {
				if ($db->add ()){
					$this->success ( '更新成功', U ( 'user/route' ,'',true,true,true ) );
				} else {
					$this->error ( "操作失败" );
				}
			} else {
				$this->error ( $db->getError () );
			}
		} else {
			$where ['id'] = $info ['id'];
			$_POST ['shopid'] = $uid;
			$_POST ['sortid'] = 0;
			if ($db->create ( $_POST, 2 )) {
				if ($db->where ( $where )->save ()) {
					$this->success ( '更新成功', U ( 'user/route','',true,true,true  ) );
				} else {
					$this->error ( "操作失败" );
				}
			} else {
				$this->error ( $db->getError () );
			}
		}
	}

	// 路由管理
	public function routemap() {
		$this->isLogin ();
		// 引用页码工具类
		import ( '@.ORG.UserPage' );
		$this->assign ( 'a', 'routemap' );
		$uid = session ( 'uid' );
		$where ['shopid'] = $uid;
		// 实例化一个对象
		$ad = D ( 'routemap' );
		$count = $ad->where ( $where )->count ();
		$page = new UserPage ( $count, C ( 'ad_page' ) );
		// 获得当前商户的路由信息
		$result = $ad->where ( $where )->field ( 'id,routename,sortid,gw_id,add_time,last_heartbeat_time' )->limit ( $page->firstRow . ',' . $page->listRows )->order ( 'sortid asc ,add_time asc' )->select ();
		// 分配页码
		$this->assign ( 'page', $page->show () );
		$this->assign ( 'lists', $result );
		
		$this->display ();
	
	}
	// 编辑路由信息
	public function editroute() {
		$this->isLogin ();
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			$db = D ( 'Routemap' );
			$_POST ['shopid'] = session ( 'uid' );
			$id = I ( 'post.id', '0', 'int' );
			$where ['id'] = $id;
			$where ['shopid'] = session ( 'uid' );
			$result = $db->where ( $where )->field ( 'id' )->find ();
			// print_r($result);exit;
			if ($result == false) {
				$this->error ( '没有此路由信息' );
				exit ();
			}
			
			if ($db->create()) {
				if ($db->where ( $where )->save ()) {
					$this->success ( '更新成功', U ( 'user/routemap','',true,true,true ) );
				} else {
					$this->error ( "操作失败" );
				}
			} else {
				$this->error ( $db->getError () );
			}
		
		} else {
			$id=I ( 'get.id', '0', 'int' );
			$uid = session ( 'uid' );
			$where ['id'] = $id;
			$where ['shopid'] = $uid;
			// 获得当前要编辑的路由信息
			$r = D ( 'Routemap' )->where ( $where )->field ( 'id,shopid,routename,gw_id,sortid,hotspotname,hotspotpass' )->find ();
			
			if ($r == false) {
				$this->error ( '没有此路由信息' );
			} else {
				// 分配当前要编辑的路由信息
				$this->assign ( 'info', $r );
				$this->display ();
			}
		}
	
	}
	// 添加路由信息
	public function addroute() {
		$this->isLogin ();
		$db = D ( 'Routemap' );
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			$_POST['add_time'] = time();
			$_POST['update_time'] = time();
			$_POST['last_heartbeat_time'] = time();
			
			$_POST ['shopid'] = session ( 'uid' );
			if ($db->create ()) {
				if ($db->add ()) {
					$this->success ( '添加成功', U ( 'user/routemap' ,'',true,true,true) );
				} else {
					$this->error ( "操作失败" );
				}
			} else {
				$this->error ( $db->getError () );
			}
		
		} else {
			$this->display ();
		}
	
	}
	// 删除路由信息
	public function delrout() {
		$this->isLogin ();
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : 0;
		
		$uid = session ( 'uid' );
		$where ['id'] = $id;
		$where ['shopid'] = $uid;
		
		$r = D ( 'Routemap' )->where ( $where )->find ();
		
		if ($r == false) {
			$this->error ( '没有此路由信息' );
		} else {
			if (D ( 'Routemap' )->where ( $where )->delete ()) {
				$this->success ( '删除成功' );
			} else {
				$this->error ( '删除失败' );
			}
		
		}
	}

	// 上网统计
	public function online() {
		$this->isLogin ();
		import ( '@.ORG.UserPage' );
		$this->assign ( 'a', 'online' );
		$uid = session ( 'uid' );
		$where ['shopid'] = $uid;
		// 实例化对Authlist表操作的一个对象
		$ad = D ( 'Authlist' );
		$count = $ad->where ( $where )->count ();
		
		$page = new UserPage ( $count, 10 );
		$pg = $page->show ();
		
		$result = $ad->where ( $where )->order ( 'login_time desc' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		
		$this->assign ( 'page', $pg );
		$this->assign ( 'lists', $result );
		
		$this->display ();
	}
	// 用户统计
	public function report() {
		$this->isLogin ();
		import ( '@.ORG.UserPage' );
		$this->assign ( 'a', 'report' );
		$uid = session ( 'uid' );
		$where ['shopid'] = $uid;
		$where ['mode'] = array ('in', '0,1' );
		
		$ad = D ( 'Member' );
		$count = $ad->where ( $where )->count ();
		$page = new UserPage ( $count, 10 );
		$result = $ad->where ( $where )->limit ( $page->firstRow . ',' . $page->listRows )->order ( 'login_time desc' )->select ();
		$this->assign ( 'page', $page->show () );
		$this->assign ( 'lists', $result );
		
		$this->display ();
	}
	// 上网统计
	public function rpt() {
		$this->isLogin ();
		$this->assign ( 'a', 'online' );
		$this->display ();
	}
	// 导出上网统计表
	public function downrpt() {
		$this->isLogin ();
		// 获得查询类型( 今日数据统计、昨日统计、最近七天、本月统计)
		$way = I ( 'get.mode' );
		$para ['mode'] = $way;
		switch (strtolower ( $way )) {
			case "query" :
				$sdate = I ( 'get.sdate' );
				$edate = I ( 'get.edate' );
				$para ['sdate'] = $sdate;
				$para ['edate'] = $edate;
				break;
		}
		$sql = $this->getrptsql ( $para );
		if ($sql != '') {
			$db = D ( 'Adcount' );
			$rs = $db->query ( $sql );
			switch (strtolower ( $way )) {
				case "today" :
					$fm = array (array ('统计时段', 't' ), array ('24小时上网流量', 'ct' ) );
					break;
				case "yestoday" :
					$fm = array (array ('统计时段', 't' ), array ('24小时上网流量', 'ct' ) );
					break;
				case "week" :
					$fm = array (array ('统计日期', 'td' ), array ('日上网流量', 'ct' ) );
					break;
				case "query" :
					$fm = array (array ('统计日期', 'td' ), array ('日上网流量', 'ct' ) );
					break;
			}
			
			exportexcelByKey ( $rs, $fm, date ( 'Y-m-d H:i:s', time () ) );
		} else {
			$this->error ( "参数不正确" );
		}
	}
	
	public function adrpt() {
		$this->isLogin ();
		$db = D ( 'Authlist' );
		//$sql=" select add_date ,count(*) as t from ".C('DB_PREFIX')."authlist where add_date BETWEEN '2014-03-07' and '2014-03-15' GROUP BY add_date ";
		$sql = "select add_date ,count(*) as t FROM(select FROM_UNIXTIME(add_time, '%Y-%m-%d' ) as add_date from " . C ( 'DB_PREFIX' ) . "test) t1 group by  add_date";
		$rs = $db->query ( $sql );
		$this->assign ( 'dt', $rs );
		
		$this->display ();
	}
	/*
     * 用户统计图表
     * 
     */
	public function userchart() {
		$this->isLogin ();
		$this->assign ( 'a', 'report' );
		$this->display ();
	}
	// 导出用户统计报表
	public function downchart() {
		$this->isLogin ();
		$way = I ( 'get.mode' );
		// 获得查询类型( 今日数据统计、昨日统计、最近七天、本月统计)
		$para ['mode'] = $way;
		switch (strtolower ( $way )) {
			case "query" :
				$sdate = I ( 'get.sdate' );
				$edate = I ( 'get.edate' );
				$para ['sdate'] = $sdate;//开始时间
				$para ['edate'] = $edate;//结束时间
				break;
		}
		$sql = $this->getuserchartsql ( $para );
		if ($sql != '') {
			$db = D ( 'Adcount' );
			$rs = $db->query ( $sql );
			switch (strtolower ( $way )) {
				case "today" :
					$fm = array (array ('统计时段', 'showdate' ), array ('认证总人数', 'totalcount' ), array ('注册认证', 'regcount' ), array ('手机认证', 'phonecount' ) );
					break;
				case "yestoday" :
					$fm = array (array ('统计时段', 'showdate' ), array ('认证总人数', 'totalcount' ), array ('注册认证', 'regcount' ), array ('手机认证', 'phonecount' ) );
					break;
				case "week" :
					$fm = array (array ('统计日期', 'showdate' ), array ('认证总人数', 'totalcount' ), array ('注册认证', 'regcount' ), array ('手机认证', 'phonecount' ) );
					break;
				case "query" :
					$fm = array (array ('统计日期', 'showdate' ), array ('认证总人数', 'totalcount' ), array ('注册认证', 'regcount' ), array ('手机认证', 'phonecount' ) );
					break;
			}
			
			exportexcelByKey ( $rs, $fm, date ( 'Y-m-d H:i:s', time () ) );
		} else {
			$this->error ( "参数不正确" );
		}
	}
	// 获得用户统计数据
	public function getuserchart() {
		$this->isLogin ();
		$way = I ( 'get.mode' );
		$where = " where shopid=" . session ( 'uid' );
		switch (strtolower ( $way )) {
			case "today" :
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from " . C ( 'DB_PREFIX' ) . "member";
				$sql .= " where add_date='" . date ( "Y-m-d" ) . "' and ( mode=0 or mode=1 ) and shopid=" . session ( 'uid' );
				$sql .= " )a group by thour ) c ";
				$sql .= "  on a.t=c.thour ";
				
				break;
			case "yestoday" :
				
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from " . C ( 'DB_PREFIX' ) . "member";
				
				$sql .= " where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and ( mode=0 or mode=1 ) and shopid=" . session ( 'uid' );
				$sql .= " )a group by thour ) c ";
				$sql .= "  on a.t=c.thour ";
				
				break;
			case "week" :
				$sql = "  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ";
				$sql .= " ( select CURDATE() as td ";
				for($i = 1; $i < 7; $i ++) {
					$sql .= "  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
				}
				$sql .= " ORDER BY td ) a left join ";
				$sql .= "( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from " . C ( 'DB_PREFIX' ) . "member ";
				$sql .= " where shopid=" . session ( 'uid' ) . " and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
			case "month" :
				$t = date ( "t" );
				$sql = " select tname as showdate,tname as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from " . C ( 'DB_PREFIX' ) . "day  a left JOIN";
				$sql .= "( select right(add_date,2) as td ,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from " . C ( 'DB_PREFIX' ) . "member ";
				$sql .= " where  shopid=" . session ( 'uid' ) . " and  add_date >= '" . date ( "Y-m-01" ) . "' and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql .= " ) b on a.tname=b.td ";
				$sql .= " where a.id between 1 and  $t";
				
				break;
			case "query" :
				$sdate = I ( 'get.sdate' );
				$edate = I ( 'get.edate' );
				import ( "ORG.Util.Date" );
				//$sdt=Date("Y-M-d",$sdate);
				//$edt=Date("Y-M-d",$edate);
				$dt = new Date ( $sdate );
				$leftday = $dt->dateDiff ( $edate, 'd' );
				$sql = " select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount  from ";
				$sql .= " ( select '$sdate' as td ";
				for($i = 0; $i <= $leftday; $i ++) {
					$sql .= "  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
				}
				$sql .= " ) a left join ";
				
				$sql .= "( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount  from " . C ( 'DB_PREFIX' ) . "member ";
				$sql .= " where  shopid=" . session ( 'uid' ) . " and add_date between '$sdate' and '$edate'  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
		}
		
		$db = D ( 'Adcount' );
		$rs = $db->query ( $sql );
		$this->ajaxReturn ( json_encode ( $rs ) );
	}
	// 获得用户统计数据sql语句
	private function getuserchartsql($para) {
		$way = $para ['mode'];
		$where = " where shopid=" . session ( 'uid' );
		switch (strtolower ( $way )) {
			case "today" :
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from " . C ( 'DB_PREFIX' ) . "member";
				$sql .= " where add_date='" . date ( "Y-m-d" ) . "' and ( mode=0 or mode=1 ) and shopid=" . session ( 'uid' );
				$sql .= " )a group by thour ) c ";
				$sql .= "  on a.t=c.thour ";
				
				break;
			case "yestoday" :
				
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from " . C ( 'DB_PREFIX' ) . "member";
				
				$sql .= " where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and ( mode=0 or mode=1 ) and shopid=" . session ( 'uid' );
				$sql .= " )a group by thour ) c ";
				$sql .= "  on a.t=c.thour ";
				
				break;
			case "week" :
				$sql = "  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ";
				$sql .= " ( select CURDATE() as td ";
				for($i = 1; $i < 7; $i ++) {
					$sql .= "  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
				}
				$sql .= " ORDER BY td ) a left join ";
				$sql .= "( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from " . C ( 'DB_PREFIX' ) . "member ";
				$sql .= " where shopid=" . session ( 'uid' ) . " and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
			case "month" :
				$t = date ( "t" );
				$sql = " select tname as showdate,tname as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from " . C ( 'DB_PREFIX' ) . "day  a left JOIN";
				$sql .= "( select right(add_date,2) as td ,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from " . C ( 'DB_PREFIX' ) . "member ";
				$sql .= " where  shopid=" . session ( 'uid' ) . " and  add_date >= '" . date ( "Y-m-01" ) . "' and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql .= " ) b on a.tname=b.td ";
				$sql .= " where a.id between 1 and  $t";
				
				break;
			case "query" :
				$sdate = $para ['sdate'];
				$edate = $para ['edate'];
				import ( "ORG.Util.Date" );
				//$sdt=Date("Y-M-d",$sdate);
				//$edt=Date("Y-M-d",$edate);
				$dt = new Date ( $sdate );
				$leftday = $dt->dateDiff ( $edate, 'd' );
				$sql = " select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount  from ";
				$sql .= " ( select '$sdate' as td ";
				for($i = 0; $i <= $leftday; $i ++) {
					$sql .= "  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
				}
				$sql .= " ) a left join ";
				
				$sql .= "( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount  from " . C ( 'DB_PREFIX' ) . "member ";
				$sql .= " where  shopid=" . session ( 'uid' ) . " and add_date between '$sdate' and '$edate'  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
		}
		return $sql;
	}
	
	/*
     * 获取报表对应的SQL语句
     */
	private function getrptsql($para) {
		$way = $para ['mode'];
		$where = " where shopid=" . session ( 'uid' );
		switch (strtolower ( $way )) {
			case "today" :
				$sql = " select t, COALESCE(ct,0)  as ct from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "( select thour ,count(*) as ct from ";
				$sql .= "(select shopid,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql .= " FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from " . C ( 'DB_PREFIX' ) . "authlist $where) a ";
				$sql .= " where d='" . date ( "Y-m-d" ) . "' ";
				$sql .= " group by thour ) ";
				$sql .= " b on a.t=b.thour ";
				
				break;
			case "yestoday" :
				$sql = " select t, COALESCE(ct,0)  as ct from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "( select thour ,count(*) as ct from ";
				$sql .= "(select shopid,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql .= " FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from " . C ( 'DB_PREFIX' ) . "authlist $where) a ";
				$sql .= " where d=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) ";
				$sql .= " group by thour ) ";
				$sql .= " b on a.t=b.thour ";
				
				break;
			case "week" :
				$sql = "  select right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct from ";
				$sql .= " ( select CURDATE() as td ";
				for($i = 1; $i < 7; $i ++) {
					$sql .= "  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
				}
				$sql .= " ORDER BY td ) a left join ";
				$sql .= "( select add_date,count(*) as ct  from " . C ( 'DB_PREFIX' ) . "authlist ";
				$sql .= " where shopid=" . session ( 'uid' ) . " and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
			case "month" :
				$t = date ( "t" );
				$sql = " select tname as t, COALESCE(ct,0) as ct from " . C ( 'DB_PREFIX' ) . "day  a left JOIN";
				$sql .= "( select right(add_date,2) as td ,count(*) as ct  from " . C ( 'DB_PREFIX' ) . "authlist  ";
				$sql .= " where  shopid=" . session ( 'uid' ) . " and  add_date >= '" . date ( "Y-m-01" ) . "' GROUP BY  add_date";
				$sql .= " ) b on a.tname=b.td ";
				$sql .= " where a.id between 1 and  $t";
				
				break;
			case "query" :
				$sdate = $para ['sdate'];
				$edate = $para ['edate'];
				import ( "ORG.Util.Date" );
				//$sdt=Date("Y-M-d",$sdate);
				//$edt=Date("Y-M-d",$edate);
				$dt = new Date ( $sdate );
				$leftday = $dt->dateDiff ( $edate, 'd' );
				$sql = " select right(td,5) as td,COALESCE(ct,0)  as ct from ";
				//$sql=" select right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct from ";
				$sql .= " ( select '$sdate' as td ";
				for($i = 0; $i <= $leftday; $i ++) {
					$sql .= "  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
				}
				$sql .= " ) a left join ";
				
				$sql .= "( select add_date,count(*) as ct  from " . C ( 'DB_PREFIX' ) . "authlist ";
				$sql .= " where  shopid=" . session ( 'uid' ) . " and add_date between '$sdate' and '$edate'  GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
		}
		return $sql;
	}
	
	/*
     * 上网统计
     */
	public function getrpt() {
		$this->isLogin ();
		$way = I ( 'get.mode' );
		$where = " where shopid=" . session ( 'uid' );
		switch (strtolower ( $way )) {
			case "today" :
				$sql = " select t, COALESCE(ct,0)  as ct from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "( select thour ,count(*) as ct from ";
				$sql .= "(select shopid,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql .= " FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from " . C ( 'DB_PREFIX' ) . "authlist $where) a ";
				$sql .= " where d='" . date ( "Y-m-d" ) . "' ";
				$sql .= " group by thour ) ";
				$sql .= " b on a.t=b.thour ";
				
				break;
			case "yestoday" :
				$sql = " select t, COALESCE(ct,0)  as ct from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "( select thour ,count(*) as ct from ";
				$sql .= "(select shopid,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql .= " FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from " . C ( 'DB_PREFIX' ) . "authlist $where) a ";
				$sql .= " where d=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) ";
				$sql .= " group by thour ) ";
				$sql .= " b on a.t=b.thour ";
				
				break;
			case "week" :
				$sql = "  select right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct from ";
				$sql .= " ( select CURDATE() as td ";
				for($i = 1; $i < 7; $i ++) {
					$sql .= "  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
				}
				$sql .= " ORDER BY td ) a left join ";
				$sql .= "( select add_date,count(*) as ct  from " . C ( 'DB_PREFIX' ) . "authlist ";
				$sql .= " where shopid=" . session ( 'uid' ) . " and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
			case "month" :
				$t = date ( "t" );
				$sql = " select tname as t, COALESCE(ct,0) as ct from " . C ( 'DB_PREFIX' ) . "day  a left JOIN";
				$sql .= "( select right(add_date,2) as td ,count(*) as ct  from " . C ( 'DB_PREFIX' ) . "authlist  ";
				$sql .= " where  shopid=" . session ( 'uid' ) . " and  add_date >= '" . date ( "Y-m-01" ) . "' GROUP BY  add_date";
				$sql .= " ) b on a.tname=b.td ";
				$sql .= " where a.id between 1 and  $t";
				
				break;
			case "query" :
				$sdate = I ( 'get.sdate' );
				$edate = I ( 'get.edate' );
				import ( "ORG.Util.Date" );
				//$sdt=Date("Y-M-d",$sdate);
				//$edt=Date("Y-M-d",$edate);
				$dt = new Date ( $sdate );
				$leftday = $dt->dateDiff ( $edate, 'd' );
				$sql = " select right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct from ";
				$sql .= " ( select '$sdate' as td ";
				for($i = 0; $i <= $leftday; $i ++) {
					$sql .= "  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
				}
				$sql .= " ) a left join ";
				
				$sql .= "( select add_date,count(*) as ct  from " . C ( 'DB_PREFIX' ) . "authlist ";
				$sql .= " where  shopid=" . session ( 'uid' ) . " and add_date between '$sdate' and '$edate'  GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
		}
		$db = D ( 'Authlist' );
		$rs = $db->query ( $sql );
		$this->ajaxReturn ( json_encode ( $rs ) );
	}
	// 显示广告统计界面
	public function advrpt() {
		$this->isLogin ();
		$this->assign ( 'a', 'adv' );
		$this->show ();
	}
	// 获得广告统计sql
	public function getadrpt() {
		$this->isLogin ();
		$way = I ( 'get.mode' );
		$where = " where shopid=" . session ( 'uid' );
		switch (strtolower ( $way )) {
			case "today" :
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from " . C ( 'DB_PREFIX' ) . "adcount";
				
				$sql .= " where add_date='" . date ( "Y-m-d" ) . "' and mode=1 and shopid=" . session ( 'uid' );
				$sql .= " )a group by thour ) c ";
				$sql .= "  on a.t=c.thour ";
				
				break;
			case "yestoday" :
				
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from " . C ( 'DB_PREFIX' ) . "adcount";
				
				$sql .= " where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and mode=1 and shopid=" . session ( 'uid' );
				$sql .= " )a group by thour ) c ";
				$sql .= "  on a.t=c.thour ";
				
				break;
			case "week" :
				$sql = "  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit ,COALESCE(hit/showup*100,0) as rt from ";
				$sql .= " ( select CURDATE() as td ";
				for($i = 1; $i < 7; $i ++) {
					$sql .= "  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
				}
				$sql .= " ORDER BY td ) a left join ";
				$sql .= "( select add_date,sum(showup) as showup ,sum(hit) as hit from " . C ( 'DB_PREFIX' ) . "adcount";
				$sql .= " where shopid=" . session ( 'uid' ) . " and mode=1 and add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
			case "month" :
				$t = date ( "t" );
				$sql = " select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "day  a left JOIN";
				$sql .= "( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from " . C ( 'DB_PREFIX' ) . "adcount  ";
				$sql .= " where  shopid=" . session ( 'uid' ) . " and mode=1 and  add_date >= '" . date ( "Y-m-01" ) . "' GROUP BY  add_date";
				$sql .= " ) b on a.tname=b.td ";
				$sql .= " where a.id between 1 and  $t";
				
				break;
			case "query" :
				$sdate = I ( 'get.sdate' );
				$edate = I ( 'get.edate' );
				import ( "ORG.Util.Date" );
				//$sdt=Date("Y-M-d",$sdate);
				//$edt=Date("Y-M-d",$edate);
				$dt = new Date ( $sdate );
				$leftday = $dt->dateDiff ( $edate, 'd' );
				$sql = " select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ";
				$sql .= " ( select '$sdate' as td ";
				for($i = 0; $i <= $leftday; $i ++) {
					$sql .= "  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
				}
				$sql .= " ) a left join ";
				
				$sql .= "( select add_date,sum(showup) as showup ,sum(hit) as hit  from " . C ( 'DB_PREFIX' ) . "adcount ";
				$sql .= " where  shopid=" . session ( 'uid' ) . " and mode=1 and add_date between '$sdate' and '$edate'  GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
		}
		
		$db = D ( 'Adcount' );
		$rs = $db->query ( $sql );
		$this->ajaxReturn ( json_encode ( $rs ) );
	}
	// 留言管理
	public function comment() {
		$this->isLogin ();
		import ( '@.ORG.UserPage' );
		
		$this->assign ( 'a', 'comment' );
		$uid = session ( 'uid' );
		$where ['shopid'] = $uid;
		// 实例化一个对comment表操作的对象
		$ad = D ( 'Comment' );
		$count = $ad->where ( $where )->count ();
		$page = new UserPage ( $count, C ( 'ad_page' ) );
		
		$result = $ad->where ( $where )->field ( 'id,user,phone,content,add_time' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		
		$this->assign ( 'page', $page->show () );
		$this->assign ( 'lists', $result );
		
		$this->display ();
	}
	// 删除选中的留言
	public function delcomment() {
		$id = I ( 'get.id' );
		$where ['id'] = $id;
		$uid = session ( 'uid' );
		$where ['shopid'] = $uid;
		$ad = D ( 'Comment' );
		$ad->where ( $where )->delete ();
		$this->success ( '操作完成', U ( 'user/comment' ) );
	}
	/**
	 *
	 * 微信动态认证
	 */
	public function three_p() {
		$this->assign ( 'a', 'three_p' );
		$userM = M ( 'Shop' );
		$uid = session ( 'uid' );
		$where ['id'] = $uid;
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			$url = $_POST ['t_wx_url'];
			$token = $_POST ['t_wx_token'];
			if ($url == "") {
				$this->error ( 'url不能为空' );
			}
			// if (isUrl ( $url )) {
			// 	$this->error ( 'url格式不正确' );
			// }
			if ($token == "") {
				$this->error ( 'token不能为空' );
			}
			$echostr = md5 ( $token . time () );
			$nonce = mt_rand ( 1, 1000 );
			$timestamp = time ();
			$tmpArr = array ($token, $timestamp, $nonce );
			sort ( $tmpArr, SORT_STRING );
			$tmpStr = implode ( $tmpArr );
			$signature = sha1 ( $tmpStr );
			$urls = explode ( "?", $url );
			$data = 'timestamp=' . $timestamp . '&signature=' . $signature . '&nonce=' . $nonce . '&echostr=' . $echostr;
			if (isset ( $urls [1] )) {
				$data = $data . '&' . $urls [1];
			}
			$url = $urls [0] . '?' . $data;
			//						print_r($url);exit;
			$statu = file_get_contents ( $url );
			if ($statu != $echostr) { //验证接口
				$this->error ( '验证失败' );
			}
			
			//			$where ['id'] = $this->userid;
			if ($userM->where ( $where )->save ( $_POST )) {
				$this->success ( '验证成功' );
			} else {
				$this->error ( '验证成功' );
			}
		} else {
			$info = $userM->where ( $where )->field ( 't_wx_url,t_wx_token,weixin_id,weixin_token' )->find ();
			if (empty ( $info ) || empty ( $info ['weixin_id'] )) {
				$info ['weixin_url'] = '';
			} else {
				$info ['weixin_url'] = 'http://' . $_SERVER ['SERVER_NAME'] . '/index.php/weichat/command/' . $info ['weixin_id'];
			}
			$this->assign ( 'info', $info );
			$this->display ();
		}
	}
	
	/**
	 *
	 * 微信接口
	 */
	public function wx_api() {
		// 实例化一个对商户表的对象
		$userM = M ( 'Shop' );
		$uid = session ( 'uid' );
		$where ['id'] = $uid;
		if (isset ( $_POST ) && ! empty ( $_POST )) {
			// 获得微信id
			$weixin_id = $_POST ['weixin_id'];
			$where2 ['weixin_id'] = $weixin_id;
			$info1 = $userM->where ( $where2 )->field ( 'id,weixin_id' )->find ();
			if (! empty ( $info1 ) && $info1 ['id'] != $uid) {
				$this->error ( '微信原始ID已存在' );
			}
			if ($weixin_id == "") {
				$this->error ( '微信原始ID不能为空' );
			}
			$_POST ['weixin_token'] = md5 ( $uid . time () );
			if ($userM->where ( $where )->save ( $_POST )) {
				$this->success ( '保存成功' );
			} else {
				$this->error ( '保存成功' );
			}
		} else {
			// 获得当前商户的微信url、微信钥密、微信id
			$info = $userM->where ( $where )->field ( 't_wx_url,t_wx_token,weixin_id,weixin_token' )->find ();
			if (empty ( $info ) || empty ( $info ['weixin_id'] )) {
				$info ['weixin_url'] = '';
			} else {
				$info ['weixin_url'] = 'http://' . $_SERVER ['SERVER_NAME'] . '/index.php/weichat/command/' . $info ['weixin_id'];
			}
			$this->assign ( 'info', $info );
			$this->display ();
		}
	}

	
}



