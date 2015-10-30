<?php
class PubAction extends AdminAction{
	/*
     * 上网统计
     */
    public function getauthrpt(){
    	
    	$way=I('get.mode');
    	
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
    	}
    	$db=D('Authlist');
    	$rs=$db->query($sql);
    	
    	$this->ajaxReturn(json_encode($rs));
    }
	public  function getuserchart(){
    	
    	$way=I('get.mode');
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
    		
    	}
    
    	$db=D('Member');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
    
	public  function getadrpt(){
    	
    	$way=I('get.mode');

    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date='".date("Y-m-d")."' and mode=1 ";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    	
    	}
    
    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }

	public  function getpubadrpt(){
    	
    	$way=I('get.mode');
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(showup,0)  as showup, COALESCE(hit,0)  as hit,COALESCE(hit/showup*100,0) as rt from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, sum(showup)as showup,sum(hit) as hit from ";
				$sql.="(select  FROM_UNIXTIME(add_time,\"%H\") as thour,showup ,hit from ".C('DB_PREFIX')."adcount";

				$sql.=" where add_date='".date("Y-m-d")."' and mode=99 ";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    	}
    
    	$db=D('Adcount');
    	$rs=$db->query($sql);
    	$this->ajaxReturn(json_encode($rs));
    }
}