<?php
/**
 * 广告推送
 */
class PushadvAction extends AdminAction {
	/**
	 * [_initialize 构造函数]
	 * @return [type] [description]
	 */
	protected function _initialize() {
		parent::_initialize ();
		$this->doLoadID ( 800 );
	}

	/**
	 * [set 推送设置]
	 */
	public function set() {
		// 实例化一个对adminpushset表操作对象
		$db=D('adminpushset');
		// 判断是否有POST数据提交
		if (isset ( $_POST ) && !empty($_POST)) {
			// 广告显示时间
			$wt = $_POST ['waitseconds'];
			if (! isNumber ( $wt )) {
				$this->error ( "广告展示时间以秒为单位,请输入展示的时间" );
			}
			if ($wt < 3) {
				$this->error ( "最低展示时间不能小于3秒" );
			}
			// 获得推送设置信息
			$info=$db->find();
			// 有，就是更新
			if($info){
				//update
				// 自动验证POST数据
				if($db->create()){
					$where['id']=$info['id'];
					// 保存成功
					$db->where($where)->save();
					$this->success("操作成功");
				}else{
					$this->error($db->getError());
				}

			}else{
				// 没有，就是添加
				//add
				// 自动验证POST
				if($db->create()){
					// 添加成功
					$id=$db->add();
					$this->success("操作成功2");
				}else{
					$this->error($db->getError());
				}
			}
		} else {
			// 获得广告推送设置信息
			$info=$db->find();
			// 分配数据
			$this->assign('info',$info);
			$this->display ();
		}
	}

	/**
	 * [index 推送广告管理]
	 * @return [type] [description]
	 */
	public function index() {
		// 引用页码类
		import ( '@.ORG.AdminPage' );
		// 实例化一个对pushadv表操作对象
		$db = D ( 'Pushadv' );
		// 统计广告数量
		$count = $db->count ();
		// 实例化一个页码对象
		$page = new AdminPage ( $count, C ( 'ADMINPAGE' ) );
		// 获得广告信息
		$sql = "select a.*,b.name as agentname from " . C ( 'DB_PREFIX' ) . 'pushadv a left join ' . C ( 'DB_PREFIX' ) . 'agent b on a.aid=b.id order by sort desc,add_time desc limit ' . $page->firstRow . ',' . $page->listRows;
		$result = $db->query ( $sql );
		$list2 = array ();
		foreach ( $result as $rs ) {

			$rs ['pic'] = $this->downloadUrl ( $rs ['pic'] );
			$list2 [] = $rs;
		}
		// 分配页码
		$this->assign ( 'page', $page->show () );
		// 分配广告数据
		$this->assign ( 'lists', $list2 );
		$this->display ();
	}
	
	/**
	 * [add 添加推送广告]
	 */
	public function add() {
		// 判断是否有POST数据提交
		if (isset ( $_POST ) && !empty($_POST) ){
			// 设置添加时间
			$_POST['add_time'] = time();
			// 检测投送广告投放时间段
			if ($_POST ['startdate'] == "" || $_POST ['enddate'] == "") {
				$this->error ( '请选择广告投放时间段' );
			}
			// 分别给$ret，$err赋值
			list ( $ret, $err ) = $this->uploadFile ( session ( 'uid' ), $_FILES ['img'] ['name'], $_FILES ['img'] ['tmp_name'] );

			if ($err !== null) {
				$this->error ( '上传失败' );
			} else {
				$_POST ['pic'] = $ret ['key'];
				$ad = D ( 'Pushadv' );
				$_POST ['sort']=I('post.sort','0','int');
				// $_POST ['sort'] = isset ( $_POST ['sort'] ) ? $_POST ['sort'] : 0;
				$_POST ['startdate'] = strtotime ( $_POST ['startdate'] );
				$_POST ['enddate'] = strtotime ( $_POST ['enddate'] );
				// 自动验证POST数据
				if ($ad->create ()) {
					// 添加成功
					if ($ad->add ()) {
						$this->success ( '添加广告成功', U ( 'pushadv/index' ,'',true,true,true) );
					} else {
						$this->error ( '添加失败，请重新添加' );
					}
				} else {
					// 自动验证失败
					$this->error ( $ad->getError () );
				}
			}
		} else {
			// 没有POST数据提交就显示添加模板
			$this->display ();
		}
	}

	/**
	 * [edit 编辑推送广告]
	 * @return [type] [description]
	 */
	public function edit() {
		// 获得要编辑的广告id
		$id = I('get.id','0','int');
		// 查找条件
		$where ['id'] = $id;
		// 获得当前要编辑的广告信息
		$result = D ( 'Pushadv' )->where ( $where )->find ();
		// 判断是否有POST数据提交
		if (isset ( $_POST ) && !empty($_POST)) {
			// 检测是否设置广告投放时间段
			if ($_POST ['startdate'] == "" || $_POST ['enddate'] == "") {
				$this->error ( '请选择广告投放时间段' );
			}
			$id = I ( 'post.id', '0', 'int' );
			$where ['id'] = $id;
			
			$db = D ( 'Pushadv' );
			// 引用上传类
			import ( 'ORG.Net.UploadFile' );
			// 实例化一个上传对象
			$upload = new UploadFile ();
			$upload->maxSize = C ( 'AD_SIZE' );
			$upload->allowExts = C ( 'AD_IMGEXT' );
			$upload->savePath = C ( 'AD_PUSHSAVE' );
			// 检测图片是否上传成功
			if (! is_null ( $_FILES ['img'] ['name'] ) && $_FILES ['img'] ['name'] != "") {
				
				if (! $upload->upload ()) {
					$this->error ( $upload->getErrorMsg () );
				} else {
					// 获得上传文件信息
					$info = $upload->getUploadFileInfo ();
					$_POST ['pic'] = trim ( $info [0] ['savepath'], '.' ) . $info [0] ['savename'];
				}
			} else {
				$_POST ['pic'] = $result ['pic'];
			}
			
			if ($result) {
				// 转换时间显示方式
				$_POST ['startdate'] = strtotime ( $_POST ['startdate'] );
				$_POST ['enddate'] = strtotime ( $_POST ['enddate'] );
				// 自动验证POST数据
				if ($db->create ()) {
					// 保存更新的数据
					if ($db->where ( $where )->save ()) {
						$this->success ( '修改成功',  U ( 'pushadv/index' ,'',true,true,true) );
					} else {
						$this->error ( '操作出错' );
					}
				} else {
					// 自动验证失败
					$this->error ( $db->getError () );
				}
			
			}
		}else{
			// // 获得要编辑的广告id
			// $id = I('get.id','0','int');
			// // 查找条件
			// $where ['id'] = $id;
			// // 获得当前要编辑的广告信息
			// $result = D ( 'Pushadv' )->where ( $where )->find ();
			if ($result) {
				$result ['pic'] = $this->downloadUrl ( $result ['pic'] );
				// 分配当前广告信息
				$this->assign ( 'info', $result );
				
			} else {
				$this->error ( '无此广告信息' );
			}
			$this->display();
		}
	}
	
	/**
	 * [del 删除广告]
	 * @return [type] [description]
	 */
	public function del() {
		// 获得当前要删除广告的id
		$id = I('get.id','0','int');
		if ($id) {
			// 获得当前广告的图片信息
			$thumb = D ( 'Pushadv' )->where ( "id={$id}" )->field ( "id,pic" )->select ();
			// 删除当前广告
			if (D ( 'Pushadv' )->delete ( $id )) {
				// 判断上传图片的所在文件是否存在
				if (file_exists ( ".{$thumb[0]['pic']}" )) {
					// 删除当前广告的图片
					unlink ( ".{$thumb[0]['pic']}" );
				}
				$this->success ( '删除成功', U ( 'index' ) );
			} else {
				$this->error ( '操作出错' );
			}
		}
	}
	

//	private function configsave() {
//		$act = $this->_post ( 'action' );
//		unset ( $_POST ['files'] );
//		unset ( $_POST ['action'] );
//		unset ( $_POST [C ( 'TOKEN_NAME' )] );
//		if (update_config ( $_POST, CONF_PATH . "adv.php" )) {	
//			$this->success ( '操作成功', U ( 'Pushadv/' . $act ) );
//		} else {
//			$this->success ( '操作失败', U ( 'Pushadv/' . $act ) );
//		}
//	}

	/**
	 * [rpt 广告推送统计]
	 * @return [type] [description]
	 */
	public function rpt() {
		// 获得查询的方式
		$way = I ( 'get.mode' );
		if (! empty ( $way )) {
			// 获得相应查询下的结果
			$this->getadrpt ($way);
			exit;
		}
		$this->display ();
	}

	/**
	 * [getadrpt description]
	 * @param  [type] $way [description]
	 * @return [type]      [description]
	 */
	private function getadrpt($way) {
		// 获得当前商户的广告id
		$where = " where shopid=" . session ( 'uid' );
		switch (strtolower ( $way )) {
			case "today" :
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, sum(showup)as showup,sum(hit) as hit,  ";
				$sql .= "sum(case when mode=99 then showup else 0 end) as pshowup, ";
				$sql .= "sum(case when mode=50 then showup else 0 end) as ashowup, ";
				$sql .= "sum(case when mode=99 then hit else 0 end) as phit, ";
				$sql .= "sum(case when mode=99 then showup else 0 end) as ahit from";
				$sql .= "  (select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit,mode from " . C ( 'DB_PREFIX' ) . "adcount";
				
				$sql .= " where add_date='" . date ( "Y-m-d" ) . "' and (mode=99 or mode=50) ";
				$sql .= " )a group by thour ) c ";
				$sql .= "  on a.t=c.thour ";
				
				break;
			case "yestoday" :
				
				$sql = " select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "hours a left JOIN ";
				$sql .= "(select thour, sum(showup)as showup,sum(hit) as hit,  ";
				$sql .= "sum(case when mode=99 then showup else 0 end) as pshowup, ";
				$sql .= "sum(case when mode=50 then showup else 0 end) as ashowup, ";
				$sql .= "sum(case when mode=99 then hit else 0 end) as phit, ";
				$sql .= "sum(case when mode=99 then showup else 0 end) as ahit from";
				$sql .= "(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from " . C ( 'DB_PREFIX' ) . "adcount";
				
				$sql .= " where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and (mode=99 or mode=50) ";
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
				$sql .= " where   add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE() and (mode=99 or mode=50) GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
			case "month" :
				$t = date ( "t" );
				$sql = " select tname as showdate,tname as t, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from " . C ( 'DB_PREFIX' ) . "day  a left JOIN";
				$sql .= "( select right(add_date,2) as td ,sum(showup) as showup ,sum(hit) as hit  from " . C ( 'DB_PREFIX' ) . "adcount  ";
				$sql .= " where   add_date >= '" . date ( "Y-m-01" ) . "' and (mode=99 or mode=50) GROUP BY  add_date";
				$sql .= " ) b on a.tname=b.td ";
				$sql .= " where a.id between 1 and  $t";
				
				break;
			case "query" :
				$sdate = I ( 'get.sdate' );
				$edate = I ( 'get.edate' );
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
				$sql .= " where  add_date between '$sdate' and '$edate'  and (mode=99 or mode=50) GROUP BY  add_date";
				$sql .= " ) b on a.td=b.add_date ";
				
				break;
		}
		$db = D ( 'Adcount' );
		$rs = $db->query ( $sql );
		$this->ajaxReturn ( json_encode ( $rs ) );
	}

}