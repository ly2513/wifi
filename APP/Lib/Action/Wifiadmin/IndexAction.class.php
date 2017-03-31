<?php
class IndexAction extends AdminAction {
	
	public function index() {
		$nav ['m'] = $this->getActionName ();
		$nav ['a'] = 'index';
		$this->assign ( 'nownav', $nav );
		// print_r($nav);
		$L = M ( 'Authlist' );
		$rs = $L->where()->select();
		$this->assign('authlist_count',count($rs));
		$this->display ();
	}
	public function pwd() {
		$nav ['m'] = $this->getActionName ();
		$nav ['a'] = 'index';

		$this->assign ( 'nownav', $nav );
		if (isset ( $_POST ) && !empty($_POST) ){
			$pwd = I ( 'post.password' );
			if ($pwd == "") {
				$data ['error'] = 1;
				$data ['msg'] = "新密码不能为空";
				return $this->ajaxReturn ( $data );
			}
			if (! validate_pwd ( $pwd )) {
				$data ['error'] = 1;
				$data ['msg'] = "密码由4-20个字符 ，数字，字母或下划线组成";
				return $this->ajaxReturn ( $data );
			}
			// 实例化一个admin模型
			$db = D ( 'Admin' );
			$info = $db->where ( array ('id' => $this->userid ) )->field ( 'id,user,password' )->find ();
			log::write ( $info ['password'] );
			if (md5 ( $_POST ['oldpwd'] ) != $info ['password']) {
				
				$data ['error'] = 1;
				$data ['msg'] = "旧密码不正确";
				return $this->ajaxReturn ( $data );
			}
			
			$_POST ['update_time'] = time ();
			$_POST ['password'] = md5 ( $_POST ['password'] );
			$where ['id'] = $this->userid;
			if ($db->where ( $where )->save ( $_POST )) {
				$data ['error'] = 0;
				$data ['msg'] = "更新成功";
				return $this->ajaxReturn ( $data );
			} else {
				$data ['error'] = 1;
				$data ['msg'] = $db->getError ();
				return $this->ajaxReturn ( $data );
			}
		} else {
			$this->display ();
		}
	}
	public function liences() {
		$liences = '';
		$L = M ( 'Liences' );
		$rs = $L->where ()->find ();
		if (! empty ( $rs )) {
			$liences = $rs ['liences'];
		}
		//print_r($_POST);exit;
		if(isset($_POST) && !empty($_POST) && empty($liences)){
			if($L->create()){
				$insertid=$L->add();
				$data ['error'] = 0;
				$data ['msg'] = "增加授权码成功";
				return $this->ajaxReturn ( $data );
			}else{
				$insertid=$L->add();
				$data ['error'] = 1;
				$data ['msg'] = "增加授权码失败";
				return $this->ajaxReturn ( $data );
			}
		}else{
			$this->assign('liences',$liences);
		}
		$this->display();
	}
	/**
	 * 数据
	 * Enter description here ...
	 */
	public function systemdata() {
		$L = M ( 'Authlist' );
		$rs = $L->where()->select();
		$this->assign('authlist_count',count($rs));
		$this->display();
	}
	/**
	 * 删除三天前数据
	 * Enter description here ...
	 */
	public function delete_auths() {
		$oldday = strtotime("-2 day");
		// 删除授权
		$db = D ( 'AuthlistModel' );
		$sql2 = "DELETE FROM `wifi_authlist` WHERE `last_time`< $oldday";//清除所有这台机器的授权
		$db->query ( $sql2 );
		$data ['error'] = 0;
		$data ['msg'] = "清理成功";
		return $this->ajaxReturn ( $data );
	}
}