<?php
class WebAction extends BaseUserAction {
	
	protected function _initialize() {
		parent::_initialize ();
		$this->init ();
	
	}
	
	private function init() {
		$this->assign ( 'a', 'web3g' );
	}
	
	public function index() {
		$db = D ( 'Wap' );
		$where ['uid'] = session ( 'uid' );
		$wapinfo = $db->where ( $where )->find ();
		$this->assign ( 'wapinfo', $wapinfo );
		$this->display ();
	}
	
	public function doset() {
		if (isset($_POST) && !empty($_POST)) {
			$db = D ( 'Wap' );
			$where ['uid'] = session ( 'uid' );
			$wapinfo = $db->where ( $where )->find ();
			if ($wapinfo == false) {
				$_POST ['uid'] = session ( 'uid' );
				if ($db->create ()) {
					if ($db->add ()) {
						$this->success ( "操作成功", U ( 'web/index' ) );
					} else {
						$this->error ( "操作成功", U ( 'web/index' ) );
					}
				} else {
					$this->error ( $db->getError () );
				}
			} else {
				if ($db->create ()) {
					if ($db->where ( $where )->save ()) {
						$this->success ( "操作成功", U ( 'web/index' ) );
					} else {
						$this->error ( "操作成功", U ( 'web/index' ) );
					}
				} else {
					$this->error ( $db->getError () );
				}
			}
		}
	}
	
	public function catelog() {
		$db = D ( 'Wapcatelog' );
		$where ['uid'] = session ( 'uid' );
		$list = $db->where ( $where )->select ();
		$list2 = array ();
		foreach ( $list as $rs ) {
			$rs ['titlepic'] = $this->downloadUrl ( $rs ['titlepic'] );
			$list2 [] = $rs;
		}
		$this->assign ( 'lists', $list2 );
		$this->display ();
	}
	
	public function addcatelog() {
		if (isset($_POST) && !empty($_POST) ) {
			list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'] ,$_FILES['img']['tmp_name']);
//			print_r($err);exit;
			//7牛上传
			if ($err !== null) {
				$this->error ( '上传失败' );
			} else {
				//	        	 $info           =  $upload->getUploadFileInfo();
				$_POST ['uid'] = session ( 'uid' );
				$_POST ['titlepic'] = $ret ['key'];
				$catedb = D ( 'Wapcatelog' );
				if ($catedb->create ()) {
					if ($catedb->add ()) {
						$this->success ( "添加成功", U ( 'web/catelog' ) );
					} else {
						$this->error ( "添加失败" );
					}
				} else {
					$this->error ( $catedb->getError () );
				}
			}
		} else {
			$this->display ();
		}
	}
	public function editcatelog() {
		if (isset($_POST) && !empty($_POST)) {
			$id = $_POST ['id'];
			if (! isNumber ( $id )) {
				$this->error ( "参数不正确" );
			}
			$uid = session ( 'uid' );
			$where ['id'] = $id;
			$where ['uid'] = $uid;
			$result = D ( 'Wapcatelog' )->where ( $where )->field ( 'id' )->find ();
			if (! $result) {
				$this->error ( '无此栏目信息' );
			}
			//			import('ORG.Net.UploadFile');       
			//	        $upload             = new UploadFile();
			//	        $upload->maxSize    = C('AD_SIZE') ;
			//	        $upload->allowExts  = C('AD_IMGEXT');
			//	        $upload->savePath   =  C('AD_SAVE');
			

			if (! is_null ( $_FILES ['img'] ['name'] ) && $_FILES ['img'] ['name'] != "") {
				
				list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'],$_FILES['img']['tmp_name'] );
				
				//		print_r($ret);exit;
				//7牛上传
				if ($err !== null) {
					$this->error ( '上传失败' );
				} else {
					//					$info = $upload->getUploadFileInfo ();
					$_POST ['titlepic'] = $ret ['key'];
				}
			}
			
			$_POST ['uid'] = session ( 'uid' );
			
			$catedb = D ( 'Wapcatelog' );
			if ($catedb->create ()) {
				if ($catedb->where ( $where )->save ()) {
					
					$this->success ( "添加成功", U ( 'web/catelog' ) );
				} else {
					$this->error ( "添加失败" );
				}
			} else {
				$this->error ( $catedb->getError () );
			}
		
		} else {
			$id = $_GET ['id'];
			if (! isNumber ( $id )) {
				$this->error ( "参数不正确" );
			}
			$uid = session ( 'uid' );
			$where ['id'] = $id;
			$where ['uid'] = $uid;
			$result = D ( 'Wapcatelog' )->where ( $where )->find ();
			if ($result) {
				$result ['titlepic'] = $this->downloadUrl ( $result ['titlepic'] );
				$this->assign ( 'info', $result );
				$this->display ();
			} else {
				$this->error ( '无此栏目信息' );
			}
		}
	}
	
	public function delcatelog() {
		$id = $_GET ['id'];
		if (! isNumber ( $id )) {
			$this->error ( "参数不正确" );
		}
		$uid = session ( 'uid' );
		$where ['cid'] = $id;
		$where ['uid'] = $uid;
		$result = D ( 'Arts' )->where ( $where )->count ();
		if ($result > 0) {
			$this->error ( "请先删除该栏目下的文章内容" );
		} else {
			$delwhere ['id'] = $id;
			$delwhere ['uid'] = $uid;
			$result = D ( 'Wapcatelog' )->where ( $delwhere )->delete ();
			if ($result) {
				$this->success ( '操作成功' );
			} else {
				$this->error ( '无此栏目信息' );
			}
		}
	}
	public function arts() {
		$catedb = D ( 'Wapcatelog' );
		$where ['uid'] = session ( 'uid' );
		$where ['mode'] = 0;
		$list = $catedb->where ( $where )->field ( 'id,title' )->select ();
		
		$this->assign ( 'catelog', $list );
		import ( '@.ORG.UserPage' );
		$uid = session ( 'uid' );
		
		$where ['uid'] = $uid;
		$db = D ( 'Arts' );
		$count = $db->where ( $where )->count ();
		$page = new UserPage ( $count, C ( 'ad_page' ) );
		$result = $db->where ( $where )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$list2 = array ();
		foreach ( $result as $rs ) {
			$rs ['titlepic'] = $this->downloadUrl ( $rs ['titlepic'] );
			$list2 [] = $rs;
		}
		$this->assign ( 'lists', $list2 );
		$this->assign ( 'page', $page->show () );
		$this->display ();
	}
	
	public function addarts() {
		if (isset($_POST) && !empty($_POST)) {
			$cid = $_POST ['cid'];
			if ($cid < 1) {
				$this->error ( "请选择所属栏目" );
			}
			//			import ( 'ORG.Net.UploadFile' );
			//			$upload = new UploadFile ();
			//			$upload->maxSize = C ( 'AD_SIZE' );
			//			$upload->allowExts = C ( 'AD_IMGEXT' );
			//			$upload->savePath = C ( 'AD_SAVE' );
			if (! is_null ( $_FILES ['img'] ['name'] ) && $_FILES ['img'] ['name'] != "") {
				list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'] ,$_FILES['img']['tmp_name']);
				
				//7牛上传
				if ($err !== null) {
					$this->error ( '上传失败' );
				} else {
//					$info = $upload->getUploadFileInfo ();
					$_POST ['titlepic'] = $ret ['key'];
				}
			}
//			$info = $upload->getUploadFileInfo ();
			$_POST ['uid'] = session ( 'uid' );
			
			$catedb = D ( 'Arts' );
			if ($catedb->create ()) {
				if ($catedb->add ()) {
					$this->success ( "添加成功", U ( 'web/arts' ) );
				} else {
					$this->error ( "添加失败" );
				}
			} else {
				$this->error ( $catedb->getError () );
			}
		
		} else {
			$catedb = D ( 'Wapcatelog' );
			$where ['uid'] = session ( 'uid' );
			$where ['mode'] = 0;
			$list = $catedb->where ( $where )->field ( 'id,title' )->select ();
			
			$this->assign ( 'catelog', $list );
			
			$this->display ();
		}
	
	}
	
	public function editarts() {
		if (isset($_POST) && !empty($_POST)) {
			$id = $_POST ['id'];
			if (! isNumber ( $id )) {
				$this->error ( "参数不正确" );
			}
			$uid = session ( 'uid' );
			$where ['id'] = $id;
			$where ['uid'] = $uid;
			$result = D ( 'Arts' )->where ( $where )->field ( 'id' )->find ();
			if (! $result) {
				$this->error ( '无此文章信息' );
			}
//			import ( 'ORG.Net.UploadFile' );
//			$upload = new UploadFile ();
//			$upload->maxSize = C ( 'AD_SIZE' );
//			$upload->allowExts = C ( 'AD_IMGEXT' );
//			$upload->savePath = C ( 'AD_SAVE' );
			
			if (! is_null ( $_FILES ['img'] ['name'] ) && $_FILES ['img'] ['name'] != "") {
				
				list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'],$_FILES['img']['tmp_name'] );
				
				//7牛上传
				if ($err !== null) {
					$this->error ( '上传失败' );
				} else {
					$_POST ['titlepic'] = $ret ['key'];
				}
			}
			
			$_POST ['uid'] = session ( 'uid' );
			
			$catedb = D ( 'Arts' );
			if ($catedb->create ()) {
				if ($catedb->where ( $where )->save ()) {
					
					$this->success ( "添加成功", U ( 'web/arts' ) );
				} else {
					$this->error ( "添加失败" );
				}
			} else {
				$this->error ( $catedb->getError () );
			}
		} else {
			$id = $_GET ['id'];
			if (! isNumber ( $id )) {
				$this->error ( "参数不正确" );
			}
			$catedb = D ( 'Wapcatelog' );
			$where ['uid'] = session ( 'uid' );
			$where ['mode'] = 0;
			$list = $catedb->where ( $where )->field ( 'id,title' )->select ();
			
			$this->assign ( 'catelog', $list );
			$uid = session ( 'uid' );
			$where ['id'] = $id;
			$where ['uid'] = $uid;
			$result = D ( 'Arts' )->where ( $where )->find ();
			if ($result) {
				$result['titlepic'] = $this->downloadUrl($result['titlepic']);
				$this->assign ( 'info', $result );
				$this->display ();
			} else {
				$this->error ( '无此文章信息' );
			}
		}
	}
	
	public function delarts() {
		$id = $_GET ['id'];
		if (! isNumber ( $id )) {
			$this->error ( "参数不正确" );
		}
		$uid = session ( 'uid' );
		$where ['id'] = $id;
		$where ['uid'] = $uid;
		$result = D ( 'Arts' )->where ( $where )->delete ();
		if ($result) {
			$this->success ( '操作成功' );
		} else {
			$this->error ( '无此文章信息' );
		}
	}
	
	public function tempset() {
		$list = D ( 'Waptpl' )->cache ( 'tplcfg', '60' )->where ( 'state' )->order ( 'sort asc' )->select ();
		$this->assign ( 'tpl', $list );
		$db = D ( 'Wap' );
		$where ['uid'] = session ( 'uid' );
		$wapinfo = $db->where ( $where )->find ();
		$this->assign ( 'info', $wapinfo );
		$this->display ();
	}
	
	public function home() {
		
		$db = D ( 'Wap' );
		$where ['uid'] = session ( 'uid' );
		$info = $db->where ( $where )->find ();
		
		$tplid = I ( 'get.tpl', 'int' );
		$tpl = D ( 'waptpl' )->where ( array ('id' => $tplid ) )->find ();
		if ($tpl == false) {
			exit ();
		}
		
		if ($info) {
			//更新
			D ( 'Wap' )->where ( $where )->save ( array ('home_tpl' => $tplid, 'home_tpl_path' => $tpl ['tplpath'] ) );
		} else {
			//添加
			$time = time ();
			
			$data ['uid'] = session ( 'uid' );
			$data ['home_tpl'] = $tplid;
			$data ['home_tpl_path'] = $tpl ['tplpath'];
			$data ['add_time'] = $time;
			$data ['update_time'] = $time;
			$data ['state'] = 1;
			D ( 'Wap' )->add ( $data );
		}
	}
	
	public function lists() {
		$db = D ( 'Wap' );
		$where ['uid'] = session ( 'uid' );
		$info = $db->where ( $where )->find ();
		$tplid = I ( 'get.tpl', 'int' );
		$tpl = D ( 'waptpl' )->where ( array ('id' => $tplid ) )->find ();
		if ($tpl == false) {
			exit ();
		}
		
		if ($info) {
			//更新
			D ( 'Wap' )->where ( $where )->save ( array ('list_tpl' => $tplid, 'list_tpl_path' => $tpl ['tplpath'] ) );
		} else {
			//添加
			$time = time ();
			$data ['uid'] = session ( 'uid' );
			
			$data ['list_tpl'] = $tplid;
			$data ['list_tpl_path'] = $tpl ['tplpath'];
			$data ['add_time'] = $time;
			$data ['update_time'] = $time;
			$data ['state'] = 1;
			D ( 'Wap' )->add ( $data );
		
		}
	}
	public function info() {
		$db = D ( 'Wap' );
		$where ['uid'] = session ( 'uid' );
		$info = $db->where ( $where )->find ();
		$tplid = I ( 'get.tpl', 'int' );
		$tpl = D ( 'waptpl' )->where ( array ('id' => $tplid ) )->find ();
		if ($tpl == false) {
			exit ();
		}
		
		if ($info) {
			//更新
			D ( 'Wap' )->where ( $where )->save ( array ('info_tpl' => $tplid, 'info_tpl_path' => $tpl ['tplpath'] ) );
		} else {
			//添加
			$time = time ();
			
			$data ['uid'] = session ( 'uid' );
			$data ['info_tpl'] = $tplid;
			$data ['info_tpl_path'] = $tpl ['tplpath'];
			$data ['add_time'] = $time;
			$data ['update_time'] = $time;
			$data ['state'] = 1;
			
			D ( 'Wap' )->add ( $data );
		
		}
	}

}