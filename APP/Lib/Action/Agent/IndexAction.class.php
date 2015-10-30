<?php
class IndexAction extends  BaseAction{
	
	private  $aid;//代理商用户ID
	protected  function _initialize(){
		parent::_initialize();
		if(!session('aid')||session('aid')==null||session('aid')==''){
			$this->redirect('index/index/alog');
		}else{
			$this->aid=session('aid');
			$this->loadMenu();
		}
	}
	public  function getuserchart(){
    	
    	$way=I('get.mode');
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(totalcount,0)  as totalcount, COALESCE(regcount,0)  as regcount ,COALESCE(phonecount,0) as phonecount from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="(select thour, count(id) as totalcount , count(CASE when mode=0 then 1 else null end) as regcount, count(CASE when mode=1 then 1 else null end) as phonecount from ";
    			$sql.="(select  FROM_UNIXTIME(tt.add_time,\"%H\") as thour,tt.id,tt.mode from ".C('DB_PREFIX')."member tt left join ".C('DB_PREFIX')."shop ss on tt.shopid=ss.id where ss.pid=".$this->aid."";
				$sql.=" and tt.add_date='".date("Y-m-d")."' and ( tt.mode=0 or tt.mode=1 ) ";
				$sql.=" )a group by thour ) c ";
				$sql.="  on a.t=c.thour ";

    			break;
    		
    	}
    
    	$db=D('Member');
    	$rs=$db->query($sql);
    	
    	$this->ajaxReturn(json_encode($rs));
    }
	
	/*
     * 上网统计
     */
    public function getauthrpt(){
    	
    	$way=I('get.mode');
    	
    	switch(strtolower($way)){
    		case "today":
    			$sql=" select t,CONCAT(CURDATE(),' ',t,'点') as showdate, COALESCE(ct,0)  as ct ,COALESCE(ct_reg,0)  as ct_reg,COALESCE(ct_phone,0)  as ct_phone,COALESCE(ct_key,0)  as ct_key,COALESCE(ct_log,0)  as ct_log from ".C('DB_PREFIX')."hours a left JOIN ";
				$sql.="( select thour ,count(*) as ct ,count(case when mode=0 then 1 else null end) as ct_reg,count(case when mode=1 then 1 else null end) as ct_phone,count(case when mode=2 then 1 else null end) as ct_key,count(case when mode=3 then 1 else null end) as ct_log from ";
				$sql.="(select tt.shopid,tt.mode,FROM_UNIXTIME(tt.login_time,\"%H\") as thour,";
				$sql.=" FROM_UNIXTIME(tt.login_time,\"%Y-%m-%d\") as d from ".C('DB_PREFIX')."authlist tt left join ".C('DB_PREFIX')."shop ss on tt.shopid=ss.id where ss.pid=".$this->aid.") a ";
				$sql.=" where d='".date("Y-m-d")."' ";
				$sql.=" group by thour ) ";
				$sql.=" b on a.t=b.thour ";

    			break;
    	}
    	$db=D('Authlist');
    	$rs=$db->query($sql);
    	
    	$this->ajaxReturn(json_encode($rs));
    }
	
	
	private  function  loadMenu(){
		$path=CONF_PATH.GROUP_NAME."/Menu.php";
		if(is_file($path)){
			$config = require $path;
		}
		$this->assign("menu",$config);
	}
	public function index(){
		$nav['m']=$this->getActionName();
		$nav['a']='index';
		$this->assign('nav',$nav);
		$this->display();
	}
	
	public function shoplist(){
		$nav['m']=$this->getActionName();
		$nav['a']='shop';
		$this->assign('nav',$nav);
		
		import('@.ORG.AdminPage');
		$db=D('Shop');
		$where['pid']=$this->aid;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ag_page'));
        $result = $db->where($where)->field('id,shopname,add_time,linker,phone,account,maxcount,linkflag')->limit($page->firstRow.','.$page->listRows)-> select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
        $this->display();        

	}
	
	public function account(){
		$nav['m']=$this->getActionName();
		$nav['a']='account';
		$this->assign('nav',$nav);
		$db=D('Agent');
		$sql="select a.id,a.name, a.money,a.linker,a.phone,a.level,a.province,a.city,a.area, b.openpay from ".C('DB_PREFIX')."agent a left join ".C('DB_PREFIX')."agentlevel b on a.level=b.id ";
		$sql.=" where a.id=".$this->aid;
		$info=$db->query($sql);
		$this->assign('info',$info[0]);
		
		
		$this->display();
	}
	
	public function saveaccount(){
		$nav['m']=$this->getActionName();
		$nav['a']='account';
		$this->assign('nav',$nav);
		
		$db=D('Agent');
		$where['id']=$this->aid;
		
		C('TOKEN_ON',false);
		if($db->create($_POST,2)){
			if($db->where($where)->save()){
				$data['error']=0;
	    		$data['msg']="更新成功";
	    		return $this->ajaxReturn($data);
			}else{
				$data['error']=1;
	    		$data['msg']=$db->getError();
	    		return $this->ajaxReturn($data);
			}
		}else{
				$data['error']=1;
	    		$data['msg']=$db->getError();
	    		return $this->ajaxReturn($data);
		}
		
	}
	
	public function shopedit(){
		$nav['m']=$this->getActionName();
		$nav['a']='shop';
		$this->assign('nav',$nav);
		$id=I('get.id','0','int');
		$where['pid']=$this->aid;
		$where['id']=$id;
		$db=D('Shop');
		$info=$db->where($where)->find();
		if(!$info){
			$this->error("参数不正确");
		}
		$this->assign("shop",$info);

		$nav['m']=$this->getActionName();
		$nav['a']='shoplist';
		$this->assign('nav',$nav);
		
		include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);
     
        
        
        $this->display();        

	}
	public function shopadd(){

		$nav['m']=$this->getActionName();
		$nav['a']='shop';
		$this->assign('nav',$nav);
		include CONF_PATH.'enum/enum.php';//$enumdata
        $this->assign('enumdata',$enumdata);
		
     
        $this->display();        

	}
	
	public function pwd(){
		$this->display();
	}

	public function dopwd(){
		if(isset($_POST) && !empty($_POST)){
			$pwd=I('post.password');
			if($pwd==""){
				$data['error']=1;
		    	$data['msg']="新密码不能为空";
		    	return $this->ajaxReturn($data);
			}
			if(!validate_pwd($pwd)){
				$data['error']=1;
		    		$data['msg']="密码由4-20个字符 ，数字，字母或下划线组成";
		    		return $this->ajaxReturn($data);
			}
			$db=D('Agent');
			$info=$db->where(array('id'=>$this->aid))->field('id,account,password')->find();
			if(md5($_POST['oldpwd'])!=$info['password']){
					$data['error']=1;
		    		$data['msg']="旧密码不正确";
		    		return $this->ajaxReturn($data);
			}
		}
		
		$_POST['update_time']=time();
		$_POST['password']=md5($_POST['password']);
		$where['id']=$this->aid;
			if($db->where($where)->save($_POST)){
				$data['error']=0;
	    		$data['msg']="更新成功";
	    		return $this->ajaxReturn($data);
			}else{
				$data['error']=1;
	    		$data['msg']=$db->getError();
	    		return $this->ajaxReturn($data);
			}
		
	}
	
	public function saveshop(){
		if(IS_AJAX){
			$user = D('Shop');
			$id=I('post.id','0','int');
	        $where['id']=$id;
	        $where['pid']=$this->aid;
	        $info=$user->where($where)->find();
	        if(!$info){
	        	//无此用户信息
	        	$data['error']=1;
	    		$data['msg']="服务器忙，请稍候再试";
	    		return $this->ajaxReturn($data);
	        }
	        /*
	        $lv="";
	        foreach($_POST['shoplevel'] as $K=>$v )
	        {
	        	$lv.="#".$v."#";
	        }
	        $_POST['shoplevel']=$lv;
	        $trade="";
	        foreach($_POST['trade'] as $K=>$v )
	        {
	        	$trade.="#".$v."#";
	        }
	        $_POST['trade']=$trade;
	        */
	        $_POST['linkflag']=1;//不限制
	        if($user->create($_POST,2)){
	        	if($user->where($where)->save()){
	        		$data['error']=0;
		    		$data['url']=U('shoplist');
		    		return $this->ajaxReturn($data);
	        	}else{
	        		$data['error']=1;
		    		$data['msg']=$user->getError();
		    		return $this->ajaxReturn($data);
	        	}
	        }else{
	        		$data['error']=1;
		    		$data['msg']=$user->getError();
		    		return $this->ajaxReturn($data);
	        }
		}else{
			$data['error']=1;
    		$data['msg']="服务器忙，请稍候再试";
    		return $this->ajaxReturn($data);
		}
	}

	/*
	 * 开户
	 */
	public function openshop(){
		if(isset($_POST) && !empty($_POST)){
			$db=D('Agent');
			$sql="select a.id,a.money,a.level,b.openpay from ".C('DB_PREFIX')."agent a left join ".C('DB_PREFIX')."agentlevel b on a.level=b.id ";
			$sql.=" where a.id=".$this->aid;
			$where['id']=$this->aid;
			$info=$db->query($sql);
			if(!$info){
				$data['error']=1;
	    		$data['msg']="服务器忙，请稍候再试";
	    		return $this->ajaxReturn($data);
			}
			
			$money=$info[0]['money']==null?0:$info[0]['money'];
			$pay=$info[0]['openpay']==null?0:$info[0]['openpay'];
			if($money<$pay){
				$data['error']=1;
	    		$data['msg']="当前帐号余额不足，无法添加商户";
	    		return $this->ajaxReturn($data);
			}
			$user=D('Shop');
			$now=time();
			$_POST['pid']=$this->aid;
			$_POST['authmode']='#0#';
			$_POST['linkflag']=1;//不限制
			$_POST['maxcount']=C('OpenMaxCount');
	        if($user->create($_POST,1)){
	        	$aid=$user->add();
	        	if($aid>0){
	        		 $rs['shopid']=$aid;
		    			$rs['sortid']=0;
		    			$rs['routename']=$_POST['shopname'];
		    			$rs['gw_id']=$_POST['account'];
		    			
			        	M("Routemap")->data($rs)->add();
	        		//扣款
	        		$db->where($where)->setDec('money',$pay);
	        		//添加消费记录
	        		$paydata['aid']=$this->aid;
	        		$paydata['paymoney']=$pay;
	        		$paydata['oldmoney']=$money;
	        		$paydata['nowmoney']=$money-$pay;
	        		$paydata['do']=0;
	        		$paydata['desc']='商户开户扣款';
	        		$paydata['add_time']=$now;
	        		$paydata['update_time']=$now;
	        		D('Agentpay')->add($paydata);
	        		
	        		$data['error']=0;
		    		$data['url']=U('shoplist');
		    		return $this->ajaxReturn($data);
	        	}else{
	        		$data['error']=1;
		    		$data['msg']=$user->getDbError();
		    		return $this->ajaxReturn($data);
	        	}
	        }else{
	        		$data['error']=1;
		    		$data['msg']=$user->getError();
		    		return $this->ajaxReturn($data);
	        }
		}else{
			$data['error']=1;
    		$data['msg']="服务器忙，请稍候再试";
    		return $this->ajaxReturn($data);
		}
	}
	
	public function report(){
		$nav['m']=$this->getActionName();
		$nav['a']='report';
		$this->assign('nav',$nav);
		
		import('@.ORG.AdminPage');
		$db=D('Agentpay');
		$where['aid']=$this->aid;
		$count=$db->where($where)->count();
		$page=new AdminPage($count,C('ag_page'));
        $result = $db->where($where)->limit($page->firstRow.','.$page->listRows)->order(" add_time desc")-> select();
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
     
		$this->display();
	}
public  function routelist(){
			
			$id=I('get.id','0','int');
			
			if(empty($id)){
				$this->error("参数不正确");
			}
			import('@.ORG.AdminPage');
			$db=D('Routemap');
			$where['shopid']=$id;
			$count=$db->where($where)->count();
			$page=new AdminPage($count,C('ADMINPAGE'));
		
			$sql=" select a.* ,b.shopname from ". C('DB_PREFIX')."routemap a left join ". C('DB_PREFIX')."shop b on a.shopid=b.id left join ".C('DB_PREFIX')."agent c on b.pid=c.id where a.shopid=".$id." and b.pid=".$this->aid." order by a.id desc limit ".$page->firstRow.','.$page->listRows." ";
			
			
	        
			$result = $db->query($sql);
	     
	        
	        $this->assign('page',$page->show());
	        $this->assign('lists',$result);
			$this->display();        

	}
	
	public function editroute(){
		if(isset($_POST) && !empty($_POST)){
			$db= D('Routemap');
        	
        	$id = I('post.id','0','int');
	        $where['id']=$id;

         	$result =$db
                    ->where($where)
                    ->field('id')
                    ->find();
                   
                    
	         if($result==false){
	         	$this->error('没有此路由信息');
	         	exit;
	         }
	        
        	if($db->create()){
        			if($db->where($where)->save()){
	        		   $this->success('更新成功',U('shoplist'));
	        		}else{
	        			
	        			$this->error("操作失败");
	        		}
        	}else{
        		$this->error($db->getError());
        	}
		}else{
			$id=I('get.id','0','int');
			$shopid=I('get.shopid','0','int');
			$where['id']=$id;
			$where['shopid']=$shopid;
			$db=D('Routemap');
			$info=$db->where($where)->find();
			if(!$info){
				$this->error("参数不正确");
			}
			$this->assign("info",$info);
			
	

	        
	     
	        
	        
	        $this->display();    
		}
	}

	public function delroute(){
	 	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
         $shopid=I('get.shopid','0','int');
       
        $where['id']=$id;
        $where['shopid']=$id;
        
        $r = D('Routemap')->where($where)->find();
        
        if($r==false){
        	$this->error('没有此路由信息');
        }else{
          if(D('Routemap')->where($where)->delete()){
          	$this->success('删除成功');
          }else{
          	$this->error('删除失败');
          }
        	
        }
	}

	public  function addroute(){
		if(isset($_POST) && !empty($_POST)){
			$db=D('Routemap');
			if($db->create()){
				if($db->add()){
					$this->success('添加成功',U('index'));
				}else{
					$this->error("操作失败");
				}
			}else{
				$this->error($db->getError());
			
			}
		}else{
			$id=I('get.id','0','int');
			
			if(empty($id)){
				$this->error("参数不正确");
			}
			$where['id']=$id;
			$db=D('Shop');
			$info=$db->where($where)->field('id,shopname')->find();
			if(!$info){
				$this->error("参数不正确");
			}
			$this->assign("shop",$info);
			$this->display();        
		}
		
		
		
	}
}