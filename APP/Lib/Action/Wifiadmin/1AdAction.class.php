<?php
class AdAction extends AdminAction {
	protected function _initialize() {
		parent::_initialize ();
		$this->doLoadID ( 500 );
	}
	
	public function index() {
		import ( '@.ORG.AdminPage' );
		$db = D ( 'Ad' );
		if (isset($_POST) && !empty($_POST)) {
			if (isset ( $_POST ['sname'] ) && $_POST ['sname'] != "") {
				$map ['sname'] = $_POST ['sname'];
				$where .= " and b.shopname like '%%%s%%'";
			}
			if (isset ( $_POST ['slogin'] ) && $_POST ['slogin'] != "") {
				$map ['slogin'] = $_POST ['slogin'];
				$where .= " and b.account like '%%%s%%'";
			}
			
			$_GET ['p'] = 0;
		} else {
			if (isset ( $_GET ['sname'] ) && $_GET ['sname'] != "") {
				$map ['sname'] = $_GET ['sname'];
				$where .= " and b.shopname like '%%%s%%'";
			
			}
			if (isset ( $_GET ['slogin'] ) && $_GET ['slogin'] != "") {
				$map ['slogin'] = $_GET ['slogin'];
				$where .= " and b.account like '%%%s%%'";
			}
		
		}
		
		$sqlcount = "select a.id, a.ad_pos,ad_thumb,ad_sort,a.mode,a.add_time,a.update_time,b.shopname from " . C ( 'DB_PREFIX' ) . "ad a left join " . C ( 'DB_PREFIX' ) . "shop b on a.uid=b.id ";
		if (! empty ( $where )) {
			$sqlcount .= " where true " . $where;
		}
		$rs = $db->query ( $sqlcount, $map );
		$count = $rs [0] ['ct'];
		$page = new AdminPage ( $count, C ( 'ADMINPAGE' ) );
		foreach ( $map as $k => $v ) {
			$page->parameter .= " $k=" . urlencode ( $v ) . "&"; //赋值给Page";
		}
		
		$sql = "select a.id, a.ad_pos,ad_thumb,ad_sort,a.mode,a.add_time,a.update_time,b.shopname from " . C ( 'DB_PREFIX' ) . "ad a left join " . C ( 'DB_PREFIX' ) . "shop b on a.uid=b.id ";
		if (! empty ( $where )) {
			$sql .= " where true " . $where;
		}
		$sql .= " order by a.id desc limit " . $page->firstRow . ',' . $page->listRows;
		$result = $db->query ( $sql, $map );
		//dump($db->getLastSql());
//		print_r($result);exit;
		$list2 = array();
		foreach ( $result as $rs ) {
			$rs ['ad_thumb'] = $this->downloadUrl ( $rs ['ad_thumb'] );
			$list2 [] = $rs;
		}
		$this->assign ( 'page', $page->show () );
		$this->assign ( 'lists', $list2 );
		$this->display ();
	
	}
	public function addAd() {
		$this->display();
	}
	public function oddrpt() {
		if (isset($_POST) && !empty($_POST)) {
			$way = $_POST ['mode'];
			$aid = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : 0;
			
			switch (strtolower ( $way )) {
				case "today" :
					$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
					$sql .= "(select thour, sum(showup)as showup,sum(hit) as hit from ";
					$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from " . C ( 'DB_PREFIX' ) . "adcount";
					
					$sql .= " where add_date='" . date ( "Y-m-d" ) . "' and mode=1 and aid=" . $aid;
					$sql .= " )a group by thour ) c ";
					$sql .= "  on a.t=c.thour ";
					
					break;
				case "yestoday" :
					
					$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
					$sql .= "(select thour, sum(showup)as showup,sum(hit) as hit from ";
					$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from " . C ( 'DB_PREFIX' ) . "adcount";
					
					$sql .= " where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and mode=1 and aid=" . $aid;
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
					$sql .= " where   add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE() and mode=1 and aid=" . $aid . "  GROUP BY  add_date";
					$sql .= " ) b on a.td=b.add_date ";
					
					break;
				case "month" :
					$t = date ( "t" );
					$sql = " select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "day  a left JOIN";
					$sql .= "( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from " . C ( 'DB_PREFIX' ) . "adcount  ";
					$sql .= " where   add_date >= '" . date ( "Y-m-01" ) . "' and mode=1 and aid=" . $aid . " GROUP BY  add_date";
					$sql .= " ) b on a.tname=b.td ";
					$sql .= " where a.id between 1 and  $t";
					
					break;
				case "query" :
					$sdate = $_POST ['sdate'];
					$edate = $_POST ['edate'];
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
					$sql .= " where  add_date between '$sdate' and '$edate'  and mode=1 and aid=" . $aid . " GROUP BY  add_date";
					$sql .= " ) b on a.td=b.add_date ";
					
					break;
			}
			
			$db = D ( 'Adcount' );
			$rs = $db->query ( $sql );
			//log::write($sql);
			$this->ajaxReturn ( json_encode ( $rs ) );
		} else {
			$aid = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : 0;
			
			$where ['id'] = $aid;
			$result = D ( 'Ad' )->where ( $where )->find ();
			
			if ($result) {
				$this->assign ( 'info', $result );
				$this->display ();
			} else {
				$this->error ( '无此广告信息' );
			}
		}
	}
	
	public function editad() {
		if (isset($_POST) && !empty($_POST)) {
			
			$id = I ( 'post.id', '0', 'int' );
			$where ['id'] = $id;
			
			$db = D ( 'Ad' );
			$result = $db->where ( $where )->field ( 'id' )->find ();
			if ($result == false) {
				$this->error ( '无此广告信息' );
				exit ();
			}
			
			//	        import('ORG.Net.UploadFile');      
			//	       
			//	        $upload             = new UploadFile();
			//	        $upload->maxSize    = C('AD_SIZE');
			//	        $upload->allowExts  = C('AD_IMGEXT');
			//	         $upload->savePath   =  C('AD_SAVE');
			

			if (! is_null ( $_FILES ['img'] ['name'] ) && $_FILES ['img'] ['name'] != "") {
				list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'], $_FILES ['img'] ['tmp_name'] );
				if ($err !== null) {
					$this->error ( '上传失败' );
				} else {
					//					$info = $upload->getUploadFileInfo ();
					$_POST ['ad_thumb'] = $ret ['key'];
				}
			
			}
			$_POST ['update_time'] = time ();
			
			if ($result) {
				
				if ($db->create ()) {
					if ($db->where ( $where )->save ()) {
						
						$this->success ( '修改成功', U ( 'index' ) );
					} else {
						$this->error ( '操作出错' );
					}
				} else {
					$this->error ( $db->getError () );
				}
			
			}
		} else {
			$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : 0;
			
			$where ['id'] = $id;
			$result = D ( 'Ad' )->where ( $where )->find ();
			if ($id)
				if ($result) {
					$this->assign ( 'info', $result );
					$this->display ();
				} else {
					$this->error ( '无此广告信息' );
				}
		
		}
	}
	
	public function delad() {
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET [id] ) : 0;
		
		if ($id) {
			$thumb = D ( 'ad' )->where ( "id={$id}" )->field ( "ad_thumb" )->select ();
			if (D ( 'ad' )->delete ( $id )) {
				if (file_exists ( ".{$thumb[0]['ad_thumb']}" )) {
					unlink ( ".{$thumb[0]['ad_thumb']}" );
				}
				
				$this->success ( '删除成功', U ( 'index' ) );
			} else {
				$this->error ( '操作出错' );
			}
		}
	}
	public function rpt() {
		$way = I ( 'get.mode' );
		if (! empty ( $way )) {
			$this->getadrpt ();
			exit ();
		}
		$this->display ();
	}
	
	public function getadrpt() {
		
		$way = I ( 'get.mode' );
		$where = " where shopid=" . session ( 'uid' );
		switch (strtolower ( $way )) {
			case "today" :
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from " . C ( 'DB_PREFIX' ) . "adcount";
				
				$sql .= " where add_date='" . date ( "Y-m-d" ) . "' and mode=1 ";
				$sql .= " )a group by thour ) c ";
				$sql .= "  on a.t=c.thour ";
				
				break;
			case "yestoday" :
				
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from " . C ( 'DB_PREFIX' ) . "adcount";
				
				$sql .= " where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and mode=1 ";
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
				$sql .= " where   add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE() and mode=1 GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
			case "month" :
				$t = date ( "t" );
				$sql = " select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "day  a left JOIN";
				$sql .= "( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from " . C ( 'DB_PREFIX' ) . "adcount  ";
				$sql .= " where   add_date >= '" . date ( "Y-m-01" ) . "' and mode=1 GROUP BY  add_date";
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
				$sql .= " where  add_date between '$sdate' and '$edate'  and mode=1 GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
		}
		
		$db = D ( 'Adcount' );
		$rs = $db->query ( $sql );
		$this->ajaxReturn ( json_encode ( $rs ) );
	}
}