<?php
/**
 * 广告管理
 */
class AdAction extends AdminAction {
	/**
	 * [_initialize 构造函数]
	 * @return [type] [description]
	 */
	protected function _initialize() {
		parent::_initialize ();
		$this->doLoadID ( 500 );
	}
	
	/**
	 * [index 广告列表]
	 * @return [type] [description]
	 */
	public function index() {
		// 引用page类
		import ( '@.ORG.AdminPage' );
		// 实例化一个对ad表操作对象
		$db = D ( 'Ad' );
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)) {
			// 商家名称
			if (isset ( $_POST ['sname'] ) && $_POST ['sname'] != "") {
				$map ['sname'] = $_POST ['sname'];
				$where .= " and b.shopname like '%%%s%%'";
			}
			// 商家账号
			if (isset ( $_POST ['slogin'] ) && $_POST ['slogin'] != "") {
				$map ['slogin'] = $_POST ['slogin'];
				$where .= " and b.account like '%%%s%%'";
			}
			
			$_GET ['p'] = 0;
		} else {
			// 商家名称
			if (isset ( $_GET ['sname'] ) && $_GET ['sname'] != "") {
				$map ['sname'] = $_GET ['sname'];
				$where .= " and b.shopname like '%%%s%%'";
			}
			// 商家账号
			if (isset ( $_GET ['slogin'] ) && $_GET ['slogin'] != "") {
				$map ['slogin'] = $_GET ['slogin'];
				$where .= " and b.account like '%%%s%%'";
			}
		
		}
		// 统计商家投放的广告
		$sqlcount = "select a.id, a.ad_pos,ad_thumb,ad_sort,a.mode,a.add_time,a.update_time,b.shopname from " . C ( 'DB_PREFIX' ) . "ad a left join " . C ( 'DB_PREFIX' ) . "shop b on a.uid=b.id ";
		// 条件不能为空
		if (! empty ( $where )) {
			$sqlcount .= " where true " . $where;
		}
		$rs = $db->query ( $sqlcount, $map );
		$count = $rs [0] ['ct'];
		$page = new AdminPage ( $count, C ( 'ADMINPAGE' ) );
		foreach ( $map as $k => $v ) {
			$page->parameter .= " $k=" . urlencode ( $v ) . "&"; //赋值给Page";
		}
		// 获得商家投放的广告数据
		$sql = "select a.id, a.ad_pos,ad_thumb,ad_sort,a.mode,a.add_time,a.update_time,b.shopname from " . C ( 'DB_PREFIX' ) . "ad a left join " . C ( 'DB_PREFIX' ) . "shop b on a.uid=b.id ";
		if (! empty ( $where )) {
			$sql .= " where true " . $where;
		}
		$sql .= " order by a.id desc limit " . $page->firstRow . ',' . $page->listRows;
		$result = $db->query ( $sql, $map );
		// 存放广告信息
		$list2 = array();
		foreach ( $result as $rs ) {
			// 存放广告图片下载地址
			$rs ['ad_thumb'] = $this->downloadUrl ( $rs ['ad_thumb'] );
			$list2 [] = $rs;
		}
		// 分配页码
		$this->assign ( 'page', $page->show () );
		// 分配广告数据
		$this->assign ( 'lists', $list2 );
		$this->display ();
	
	}

	public function addAd() {
		$this->display();
	}

	/**
	 * [editad 编辑广告]
	 * @return [type] [description]
	 */
	public function editad() {
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)) {
			// 检测上传广告是否上传成功
			if (! is_null ( $_FILES ['img'] ['name'] ) && $_FILES ['img'] ['name'] != "") {
				list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'], $_FILES ['img'] ['tmp_name'] );
				if ($err !== null) {
					$this->error ( '上传失败' );
				} else {
					// 上传广告图片路径
					$_POST ['ad_thumb'] = $ret ['key'];
				}
			}
			// 设置广告更新的时间
			$_POST ['update_time'] = time ();
			// 自动验证POST数据
			if ($db->create ()) {
				// 保存修改后的广告数据
				if ($db->where ( $where )->save ()) {
					$this->success ( '修改成功', U ( 'index' ));
				} else {
					$this->error ( '修改失败' );
				}
			} else {
				// 自动验证失败
				$this->error ( $db->getError () );
			}
		} else {
			// 获得当前要编辑的广告id
			$id = I('get.id','0','int');
			// 获得当前要编辑的广告信息
			$where ['id'] = $id;
			$result = D ( 'Ad' )->where ( $where )->find ();
			if ($result) {
				// 分配当前要修改的广告信息
				$this->assign ( 'info', $result );
				$this->display ();
			} else {
				$this->error ( '无此广告信息' );
			}
		
		}
	}

	/**
	 * [delad 删除广告]
	 * @return [type] [description]
	 */
	public function delad() {
		// 获得要删除广告的id
		$id = I('get.id','0','int');
		if ($id) {
			// 获得当前要删除广告的信息
			$thumb = D ( 'ad' )->where ( "id=$id" )->field ( "ad_thumb" )->select ();
			// 删除广告信息
			if (D ( 'ad' )->delete ( $id )) {
				// 判断缩略图的文件是否存在
				if (file_exists ( ".{$thumb[0]['ad_thumb']}" )) {
					// 删除广告缩略图
					unlink ( ".{$thumb[0]['ad_thumb']}" );
				}
				$this->success ( '删除成功', U ( 'index' ) );
			} else {
				$this->error ( '操作出错' );
			}
		}
	}

	/**
	 * [oddrpt 获得当前广告的投放信息]
	 * @return [type] [description]
	 */
	public function oddrpt() {
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)) {
			// 获得查询方式
			$way = $_POST ['mode'];
			// 获得当前要统计的广告id
			$aid=I('post.id','0'.'int');
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
			// 实例化一个对adcount表操作对象
			$db = D ( 'Adcount' );
			// 执行sql语句，获得查询结果
			$rs = $db->query ( $sql );
			$this->ajaxReturn ( json_encode ( $rs ) );
		} else {
			// 没有post数据提交
			// 获得显示当前广告id
			$aid=I('get.id','0','int');
			// 查询条件
			$where ['id'] = $aid;
			// 获得当前广告信息
			$result = D ( 'Ad' )->where ( $where )->find ();
			// 有查询结果，就显示出来
			if ($result) {
				$this->assign ( 'info', $result );
				$this->display ();
			} else {
				$this->error ( '无此广告信息' );
			}
		}
	}

	/**
	 * [rpt 广告统计]
	 * @return [type] [description]
	 */
	public function rpt() {
		// $this->assign ( 'a', 'ad' );
		// $this->show ();
		$this->display();
	}

	/**
	 * [getadrpt 获得广告投放查询结果]
	 * @param  [type] $way [查询的方式]
	 * @return [type]      [description]
	 */
	public function getadrpt() {
		// 获得查询的方式
		$way = I ( 'get.mode','query','string' );
		// 查询的条件
		$where = " where shopid=" . session ( 'uid' );
		// 根据查询的方式，获得不同的sql语句
		switch (strtolower ( $way )) {
			case "today" ://查询今天
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from " . C ( 'DB_PREFIX' ) . "adcount";
				
				$sql .= " where add_date='" . date ( "Y-m-d" ) . "' and mode=1 ";
				$sql .= " )a group by thour ) c ";
				$sql .= "  on a.t=c.thour ";
				
				break;
			case "yestoday" ://查询昨天的
				
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from " . C ( 'DB_PREFIX' ) . "adcount";
				
				$sql .= " where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and mode=1 ";
				$sql .= " )a group by thour ) c ";
				$sql .= "  on a.t=c.thour ";
				
				break;
			case "week" ://查询上一周
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
			case "month" ://查询一个月的
				$t = date ( "t" );
				$sql = " select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "day  a left JOIN";
				$sql .= "( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from " . C ( 'DB_PREFIX' ) . "adcount  ";
				$sql .= " where   add_date >= '" . date ( "Y-m-01" ) . "' and mode=1 GROUP BY  add_date";
				$sql .= " ) b on a.tname=b.td ";
				$sql .= " where a.id between 1 and  $t";
				
				break;
			case "query" ://查询所有的广告
				// 开始时间
				$sdate = I ( 'get.sdate' );
				// 结束时间
				$edate = I ( 'get.edate' );
				// 引用时间处理工具类
				import ( "ORG.Util.Date" );
				$dt = new Date ( $sdate );
				// 转换时间格式(2015-01-19)
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
		// 实例化一个对adcount表操作对象
		$db = D ( 'Adcount' );
		// 执行sql语句
		$rs = $db->query ( $sql );
		// 将结果异步json返回
		$this->ajaxReturn ( json_encode ( $rs ) );
		exit;
		// echo json_encode($rs);

	}
}