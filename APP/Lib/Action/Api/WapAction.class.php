<?php
class WapAction extends BaseApiAction {
	private $tpl;
	private $uid;
	private $classinfo;
	public function _initialize() {
		parent::_initialize ();
		$this->getShopInfo ();
	}
	/*
	 * 获取用户ID
	 */
	private function getShopInfo() {
		$id = $_GET ['sid'];
		if (! isNumber ( $id )) {
			$this->error ( "参数不正确" );
		}
		$db = D ( 'Wap' );
		$where ['uid'] = $id;
		$info = $db->where ( $where )->find ();
		if ($info) {
			$this->uid = $id;
			Cookie ( 'wapuid', $id );
			$this->tpl = $info;
			$this->assign ( 'siteinfo', $info );
			$catedb = D ( 'Wapcatelog' );
			$where ['uid'] = $this->uid;
			$catelog = $catedb->where ( $where )->select ();
			if (! empty ( $catelog )) {
				$catelog2 = array ();
				foreach ( $catelog as $tp ) {
					$tp ['titlepic'] = $this->downloadUrl ( $tp ['titlepic'] );
					$catelog2 [] = $tp;
				}
				$catelog = $catelog2;
			}
			$this->classinfo = $catelog;
			$this->assign ( 'classinfo', $catelog );
		} else {
			$this->error ( "参数不正确" );
		}
	}
	public function index() {
		
		$ad = D ( 'Ad' );
		$where ['uid'] = $this->uid;
		$where ['ad_pos'] = 0;
		$result = $ad->where ( $where )->select ();
		if (! empty ( $result )) {
			$result2 = array ();
			foreach ( $result as $tp ) {
				$tp ['ad_thumb'] = $this->downloadUrl ( $tp ['ad_thumb'] );
				$result2 [] = $tp;
			}
			$result = $result2;
		}
		
        
		$this->assign ( 'flash', $result );
		$this->display ( $this->tpl ['home_tpl_path'] );
	
	}
	
	public function lists() {
		import ( 'ORG.Util.Page' );
		
		$pg = $this->_get ( 'p', 'intval' );
		$pagesize = C ( 'WAP_List' );
		if (! $pg)
			$pg = 1;
		if ($pg < 1)
			$pg = 1;
		$where ['cid'] = $this->_get ( 'classid', 'intval' );
		$news = D ( 'Arts' );
		
		$count = $news->where ( $where )->count ();
		$page = new Page ( $count, $pagesize );
		
		$listinfo = $news->where ( $where )->limit ( $page->firstRow . "," . $page->listRows )->select ();
		
		foreach ( $this->classinfo as $k => $v ) {
			if ($v ['id'] == $where ['cid']) {
				$nowclass = $v;
				break;
			}
		}
		
		$maxpage = ceil ( $count / $pagesize );
		if ($maxpage == 0) {
			$maxpage = 1;
		}
		if ($pg > $maxpage) {
			$pg = $maxpage;
		}
		$this->assign ( 'nowclass', $nowclass );
		
		$this->assign ( 'info', $this->wxdata );
		
		$this->assign ( 'page', $maxpage ); //总页数
		$this->assign ( 'p', $pg ); //当前页
		

		$this->assign ( 'listinfo', $listinfo ); //当前页
		if ($this->tpl ['list_tpl_path'] == "") {
			
			$this->display ( 'list_t1' );
		} else {
			$this->display ( $this->tpl ['list_tpl_path'] );
		}
	
	}
	
	public function info() {
		$id = $this->_get ( 'id', 'intval' );
		$news = D ( 'Arts' );
		$where ['id'] = $id;
		$where ['uid'] = cookie ( 'wapuid' );
		$data = $news->where ( $where )->find ();
		
		$this->assign ( 'data', $data );
		if ($this->tpl ['info_tpl_path'] == "") {
			
			$this->display ( 'info_t1' );
		} else {
			$this->display ( $this->tpl ['info_tpl_path'] );
		}
	
	}
	public function weixin_tmp() {
		$this->display ();
	}
}