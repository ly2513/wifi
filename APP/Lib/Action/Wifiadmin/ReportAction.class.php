<?php
/**
 * 统计信息
 */
class ReportAction extends AdminAction{
	// 构造函数
	protected function _initialize(){
		// 执行父类中的构造函数
		parent::_initialize();
		$this->doLoadID(600);
	}

	/**
	 * [user 注册用户]
	 * @return [type] [description]
	 */
	public function user(){
		
		$sqljson="";
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			//查询
			$sqljson=json_encode($_POST);
			session('userwhere',$sqljson);
			
		}else{
			//首次进入或分页
			$sqljson=session('userwhere');
			if(empty($sqljson)||$sqljson==""||!isset($_GET['p'])){
				//默认为空
				$where['sdate']=date("Y-m-01");
				$where['edate']=date("Y-m-d");
				$where['uname']="";
				$where['uphone']="";
				$where['mode']="-1";
				$sqljson=json_encode($where);
			}else{
				//有参数条件
				
			}
		}
		
		//组装查询条件
		$js=json_decode($sqljson);
		$this->assign("qjson",$js);
		$sqlwhere=" 1=1 ";
		// 查询的开始和结束时间都不能为空
		if($js->sdate!=""&&$js->edate!=""){
			$sqlwhere.=" and a.add_date between '$js->sdate' and '$js->edate' ";
		}
		if($js->mode!="-1"){
			$sqlwhere.=" and a.mode=$js->mode";
		}else{
			$sqlwhere.=" and a.mode in(0,1) ";
		}
		// 用户名不能为空
		if($js->uname!=""){
			$sqlwhere.=" and a.user like '%$js->uname%'";
		}
		// 用户的手机不为空
		if($js->uphone!=""){
			$sqlwhere.=" and a.phone like '%$js->uphone%'";
		}
		// 应用页码类
		import('@.ORG.AdminPage');
		// 实例化一个对member表操作的对象
		$db=D('Member');
		// 认证模式
		$where['mode']=array('in','0,1');
		// 统计注册用户数量
		$rs=$db->query(" select count(*) as ct from ".C('DB_PREFIX')."member a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id where $sqlwhere ");
		$count=$rs[0]['ct'];
		// 实例化一个页码类
		$page=new AdminPage($count,C('ADMINPAGE'));
		// 获得注册用户信息
		$sql="select a.*,b.account as shopaccount,b.shopname from ".C('DB_PREFIX')."member a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id where $sqlwhere order by a.login_time desc,a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
		// 获得所有用户信息
		$result=$db->query($sql);
		// 分配页码
		$this->assign('page',$page->show());
		// 分配数据
        $this->assign('lists',$result);
		$this->display();
	}
	
	/**
	 * [downuser 导出用户数据]
	 * @return [type] [description]
	 */
	public function downuser(){
		$sqljson="";
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			//查询
			$sqljson=json_encode($_POST);
			session('userwhere',$sqljson);
			
		}else{
			//首次进入或分页
			$sqljson=session('userwhere');
			
			if(empty($sqljson)||$sqljson==""||!isset($_GET['p'])){
				//默认为空
				$where['sdate']=date("Y-m-01");
				$where['edate']=date("Y-m-d");
				$where['uname']="";
				$where['uphone']="";
				$where['mode']="-1";
				$sqljson=json_encode($where);
			}else{
				//有参数条件
				
			}
		}
		//组装查询条件
		$js=json_decode($sqljson);
		$this->assign("qjson",$js);
		$sqlwhere=" 1=1 ";
		if($js->sdate!=""&&$js->edate!=""){
			$sqlwhere.=" and a.add_date between '$js->sdate' and '$js->edate' ";
		}
		// 判断认证模式
		if($js->mode!="-1"){
			$sqlwhere.=" and a.mode=$js->mode";
		}else{
			$sqlwhere.=" and a.mode in(0,1) ";
		}
		// 用户名不能为空
		if($js->uname!=""){
			$sqlwhere.=" and a.user like '%$js->uname%'";
		}
		// 用户手机不能为空
		if($js->uphone!=""){
			$sqlwhere.=" and a.phone like '%$js->uphone%'";
		}
		// 实例化一个对member表操作对象
		$db=D('Member');
		// 获得用户名、手机号码、商店名称数据
		$sql="select a.user,a.phone,b.shopname from ".C('DB_PREFIX')."member a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id where $sqlwhere order by a.login_time desc,a.add_time desc ";
		$result=$db->query($sql);
		// 将数据导入到excel表中
		exportexcel($result,array('用户名','手机号码','所属商户'),'用户资料');
	}
	
	/**
	 * [online 上网记录]
	 * @return [type] [description]
	 */
	public function online(){
		// 应用页码类
		import('@.ORG.AdminPage');
		// 实例化一个对member表操作对象
		$db=D('Member');
		// 统计所有的上网记录
		$count=$db->count();
		// 实例化一个页码对象
		$page=new AdminPage($count,C('ADMINPAGE'));
		// 获得上网的记录的所有信息
		$sql="select a.*,b.account as shopaccount,b.shopname from ".C('DB_PREFIX')."member a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id order by a.login_time desc,a.add_time desc limit ".$page->firstRow.','.$page->listRows;
        // 查询结果
		$result=$db->query($sql);
		// 分配页码
		$this->assign('page',$page->show());
		// 分配上网记录数据
        $this->assign('lists',$result);
		$this->display();
	}
	/**
	 * [userchart 用户统计报表]
	 * @return [type] [description]
	 */
	public function userchart(){
		// 获得查询方式
		$way=I('get.mode');
		// 查询方式不为空
		if(!empty($way)){
			// 获得当前查询方式的结果
			$this->getuserchart($way);
			exit;
		}
		$this->display();
	}

	private  function getuserchart($way){
		// 查询条件是当前商家的广告
    	$where=" where shopid=".session('uid');
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
    			$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from ".C('DB_PREFIX')."member";
				$sql.=" where add_date='".date("Y-m-d")."' and ( mode=0 or mode=1 ) ";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		case "yestoday":
    			
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,id,mode from ".C('DB_PREFIX')."member";

				$sql.=" where add_date=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) and ( mode=0 or mode=1 )  ";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";
				
    			break;
    		case "week":
    			$sql="  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ".C('DB_PREFIX')."member ";
				$sql.=" where  add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ".C('DB_PREFIX')."member ";
				$sql.=" where    add_date >= '".date("Y-m-01")."' and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
		
    			break;
    		case "query":
    			// 开始时间
    			$sdate=I('get.sdate');
    			// 结束时间
    			$edate=I('get.edate');
    			// 引用date工具类
    			import("ORG.Util.Date");
    			// 实例化一个Date对象
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount  from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
				$sql.="( select add_date,count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount  from ".C('DB_PREFIX')."member ";
				$sql.=" where  add_date between '$sdate' and '$edate'  and ( mode=0 or mode=1 ) GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
    	// 实例化一个对member表操作对象
    	$db=D('Member');
    	// 获得查询结果
    	$rs=$db->query($sql);
    	// 异步返回数据
    	$this->ajaxReturn(json_encode($rs));
    }

    /**
     * [authchart 上网统计报表]
     * @return [type] [description]
     */
	public function authchart(){
		// 获得认证方式
		$way=I('get.mode');
		if(!empty($way)){
			$this->getauthrpt($way);
			exit;
		}
		$this->display();
	}
	
	/**
	 * [getauthrpt 获得上网统计数据]
	 * @return [type] [description]
	 */
	private function getauthrpt($way){
    	
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(ct,0)  as ct ,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct ,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log from ";
				$sql.="(select shopid,mode,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist ) a ";
				$sql.=" where d='".date("Y-m-d")."' ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    		case "yestoday":
    			$sql=" select t, CONCAT(CURDATE(),' ',t,'点') as showdate,COALESCE(ct,0)  as ct,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct ,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log from ";
				$sql.="(select shopid,mode,FROM_UNIXTIME(login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist ) a ";
				$sql.=" where d=DATE_ADD(CURDATE() ,INTERVAL -1 DAY) ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    		case "week":
    			$sql="  select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct ,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ";
    			$sql.=" ( select CURDATE() as td ";
    			for($i=1;$i<7;$i++){
    				$sql.="  UNION all select DATE_ADD(CURDATE() ,INTERVAL -$i DAY) ";
    			}
    			$sql.=" ORDER BY td ) a left join ";
    			$sql.="( select add_date,count(*) as ct ,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log from ".C('DB_PREFIX')."authlist ";
				$sql.=" where  add_date between DATE_ADD(CURDATE() ,INTERVAL -6 DAY) and CURDATE()  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
				
    			break;
    		case "month":
    			$t=date("t");
    			$sql=" select tname as showdate,tname as t, COALESCE(ct,0) as ct ,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ".C('DB_PREFIX')."day  a left JOIN";
				$sql.="( select right(add_date,2) as td ,count(*) as ct,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log  from ".C('DB_PREFIX')."authlist  ";
				$sql.=" where    add_date >= '".date("Y-m-01")."' GROUP BY  add_date";
				$sql.=" ) b on a.tname=b.td ";
				$sql.=" where a.id between 1 and  $t";
		
    			break;
    		case "query":
    			// 开始时间
    			$sdate=I('get.sdate');
    			// 结束时间
    			$edate=I('get.edate');
    			// 引用Date工具类
    			import("ORG.Util.Date");
    			// 实例化一个date类
    			$dt=new Date($sdate);
    			$leftday=$dt->dateDiff($edate,'d');
    			$sql=" select td as showdate,right(td,5) as td,datediff(td,CURDATE()) as t,COALESCE(ct,0)  as ct ,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ";
    			$sql.=" ( select '$sdate' as td ";
    			for($i=0;$i<=$leftday;$i++){
    				$sql.="  UNION all select DATE_ADD('$sdate' ,INTERVAL $i DAY) ";
    			}
    			$sql.=" ) a left join ";
				$sql.="( select add_date,count(*) as ct ,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log  from ".C('DB_PREFIX')."authlist ";
				$sql.=" where   add_date between '$sdate' and '$edate'  GROUP BY  add_date";
				$sql.=" ) b on a.td=b.add_date ";
    			break;
    	}
    	// 实例化一个对authlist表操作的对象
    	$db=D('Authlist');
    	// 获得查询数据结果
    	$rs=$db->query($sql);
    	// 异步返回查询数据
    	$this->ajaxReturn(json_encode($rs));
    }

	/**
	 * [routechart 在线路由统计]
	 * @return [type] [description]
	 */
    public function routechart(){
    	// 获得查询方式
    	$way=I('get.mode');
		if(!empty($way)){
			// 获得在线路由数据
			$this->getroutechart();
			exit;
		}
		// 获得
		$list=I('get.info');   
    	if(!empty($list)){
    		// 获得路由列表数据
			$this->getroutelist();
			exit;
		} 	
		$this->display();
    }
    
    /**
     * [getroutechart 获得在线路由数据]
     * @return [type] [description]
     */
    private function getroutechart(){
    	$sql=" select count(*) as total,count(case when last_heartbeat_time >= unix_timestamp(DATE_ADD(now(),INTERVAL -30 MINUTE) ) then 1 else null end)  as livecount,count(case when last_heartbeat_time <unix_timestamp(DATE_ADD(now(),INTERVAL -30 MINUTE) ) then 1 when last_heartbeat_time is null then 1  else null end)  as diecount  from ".C('DB_PREFIX')."routemap";
    	$db=D("Routemap");
    	$info=$db->query($sql);
    	
    	return $this->ajaxReturn($info);
    }
    
    /**
     * [getroutelist 获得路由列表数据]
     * @return [type] [description]
     */
	private function getroutelist(){
		// 引用页码类
		import('@.ORG.AdminAjaxPage');
		// 获得查询路由的状态
		$tp=I('get.flag');
		// 在线路由
		if($tp=="a"){
			// 查询条件
			$where="where last_heartbeat_time >= unix_timestamp(DATE_ADD(now(),INTERVAL -30 MINUTE) )";
		}else if($tp=='d'){
			//离线路由(查询条件)
			$where="where last_heartbeat_time < unix_timestamp(DATE_ADD(now(),INTERVAL -30 MINUTE) )";
		}else{
			exit;
		}
    	// 实例化一个对routmap表操作的对象
    	$db=D("Routemap");
    	// 获得路由id、提供路由商家id
    	$sql=" select a.id,a.shopid,FROM_UNIXTIME( last_heartbeat_time, '%Y-%m-%d %H:%i:%s' )  as last_heartbeat_time,b.shopname  from ".C('DB_PREFIX')."routemap a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id ";
    	// 查询条件
    	$sql.=$where;
    	// 统计路由数量
    	$sqlcount=" select count(*) as ct from ".C('DB_PREFIX')."routemap ";
    	$sqlcount.=$where;
    	$rs=$db->query($sqlcount);
    	$count=$rs[0]['ct'];
    	// 实例化一个页码对象，每页显示10条数据
    	$page=new AdminAjaxPage($count,10);
    	$sql.=" limit ".$page->firstRow.','.$page->listRows." ";
    	$info=$db->query($sql);
    	// 分配数据
    	$back['list']=$info;
    	//页码
    	$back['pg']=$page->show();
    	// 将页码异步传过去
    	return $this->ajaxReturn($back);
    }
    
}