<?php
// 管理员登入管理
class LoginAction extends AdminAction{
	public function index(){
		$this->display();
	}
	public function verify(){
		import("ORG.Util.Image");
		Image::buildImageVerify();
	}
	
	public function dologin(){
		import("@.ORG.WIFIRBAC");
		import("ORG.Util.Net.IpLocation");
		
		$username = $this->_post('user');
		$password =  $this->_post('password','md5');
		if(empty($username)||empty($password)){
			$data['error']=1;
			$data['msg']="请输入帐号密码";
			$this->ajaxReturn($data);
			
		}
		/*
		$code=$this->_post('vcode','intval,md5',0);
	
		if($code != $_SESSION['verify']){
			$data['error']=1;
			$data['msg']="验证码错误";
			$this->ajaxReturn($data);
			
		}
		*/
		//生成认证条件
		$map            =   array();
		// 支持使用绑定帐号登录
		$map['user'] = $username;
		$map['state'] = 1;
		$authInfo = WIFIRBAC::authenticate($map);
		// 验证管理员登入信息
		if($authInfo){
			
			if($authInfo['password']!=$password){
					$data['error']=1;
					$data['msg']='帐号密码不正确';
					$this->ajaxReturn($data);
			}
			session(C('USER_AUTH_KEY'),$authInfo['id']);
			session('adminid',$authInfo['id']);  //用户ID
			session('adminmame',$authInfo['user']);   //用户名
			session('roleid',$authInfo['role']);    //角色ID
			if($authInfo['user']==C('SPECIAL_USER')) {
				session(C('ADMIN_AUTH_KEY'), true);
			}
			$User	=	M(C('USER_AUTH_MODEL'));
			$ip		=	get_client_ip();
			$data = array();
			
			
			$data['last_logintime']=time();
			$data['last_loginip']	=get_client_ip();
			$User->where(array('id'=>$authInfo['id']))->save($data);
		
			
			$databack['error']=0;
			$databack['url']=U('Index/index');

			$this->ajaxReturn($databack);

		}else{
			$data['error']=1;
			$data['msg']='帐号不存在或者被禁用';
			$this->ajaxReturn($data);

		}
	}
	// 退出
	public function loginout(){
		session(null);
		session_destroy();
		unset($_SESSION);
		if(session('?'.C('USER_AUTH_KEY'))) {
			session(C('USER_AUTH_KEY'),null);
			redirect(U('Login/index'));
		}else {
			$this->error('已经退出！',U('Login/index','',true,true,true));
		}
	}
	
	
	
}