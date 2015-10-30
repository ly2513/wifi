<?php
/**
 * 商户控制器
 */
class ShopAction extends  AdminAction{
	/**
	 * [index  商户列表]
	 * @return [type] [description]
	 */
	public function index(){
		$this->doLoadID(300);
		// 引用AdminPage页码类
		import('@.ORG.AdminPage');
		// 实例化一个操作shop表的对象
		$db=D('Shop');
		// 判断是否有POST数据提交，查询
		if (isset($_POST) && !empty($_POST)){
			// 商户名称
			if(isset($_POST['sname'])&&$_POST['sname']!=""){
					$map['sname']=$_POST['sname'];
					$where.=" and a.shopname like '%%%s%%'";	
			}
			// 登录账号
			if(isset($_POST['slogin'])&&$_POST['slogin']!=""){
					$map['slogin']=$_POST['slogin'];
					$where.=" and a.account like '%%%s%%'";
			}
			// 联系电话
			if(isset($_POST['phone'])&&$_POST['phone']!=""){
					$map['phone']=$_POST['phone'];
					$where.=" and a.phone like '%%%s%%'";
			}
			// 代理商
			if(isset($_POST['agent'])&&$_POST['agent']!=""){
					$map['agent']=$_POST['agent'];
					$where.=" and b.name like '%%%s%%'";
			}
			
			$_GET['p']=0;
		}else{
			// 商户名称
			if(isset($_GET['sname'])&&$_GET['sname']!=""){
					$map['sname']=$_GET['sname'];
					$where.=" and a.shopname like '%%%s%%'";
					
			}
			// 登录账号
			if(isset($_GET['slogin'])&&$_GET['slogin']!=""){
					$map['slogin']=$_GET['slogin'];
					$where.=" and a.account like '%%%s%%'";
			}
			// 联系电话
			if(isset($_GET['phone'])&&$$_GET['phone']!=""){
					$map['phone']=$_GET['phone'];
					$where.=" and a.phone like '%%%s%%'";
			}
			// 代理商
			if(isset($_GET['agent'])&&$_GET['agent']!=""){
					$map['agent']=$_GET['agent'];
					$where.=" and b.name like '%%%s%%'";
			}
		}
		// 统计商户数量
		$sqlcount=" select count(*) as ct from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id ";
		if(!empty($where)){
			$sqlcount.=" where true ".$where;
		}
		$rs=$db->query($sqlcount,$map);
		$count=$rs[0]['ct'];
		// 实例化一个页码对象
		$page=new AdminPage($count,C('ADMINPAGE'));
		foreach($map as $k=>$v){
			//赋值给Page";
			$page->parameter.=" $k=".urlencode($v)."&";
		}
		// 获得所有的商户数据
		$sql=" select a.id,a.shopname,a.add_time,a.linker,a.phone,a.account,a.maxcount,a.linkflag,b.name as agname from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id ";
		if(!empty($where)){
			$sql.=" where true ".$where;
		}
		$sql.=" order by a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
		$result = $db->query($sql,$map);
        // 分配页码
        $this->assign('page',$page->show());
        // 分配商户数据
        $this->assign('lists',$result);
		$this->display();
	}

	/**
	 * [addshop 添加商户]
	 * @return [type] [description]
	 */
	public function addshop(){
		$this->doLoadID(300);
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			$user=D('Shop');
			// 添加时间
			$now=time();
			// 顶级代理商户
			$_POST['pid']=0;
			// 默认注册认证
			$_POST['authmode']='#0#';
			// 对密码加密
			$_POST['password']=md5($_POST['password']);
			// 自动验证POST数据
	        if($user->create($_POST,1)){
	        	// 添加商户信息，$id为自增id
	        	$id=$user->add();
	        	if($id>0){
			       // 添加商户成功
	        		$data['error']=0;
		    		$data['url']=U('index');
		    		return $this->ajaxReturn($data);
	        	}else{
	        		// 添加商户失败
	        		$data['error']=1;
		    		$data['msg']=$user->getDbError();
		    		return $this->ajaxReturn($data);
	        	}
	        }else{
	        	// 自动验证失败
        		$data['error']=1;
	    		$data['msg']=$user->getError();
	    		return $this->ajaxReturn($data);
	        }
		}else{
			// 分配商户等级数据
			include CONF_PATH.'enum/enum.php';//$enumdata
	        $this->assign('enumdata',$enumdata);
	        $this->display();      
		}  
	}

	/**
	 * [editshop 编辑商户信息]
	 * @return [type] [description]
	 */
	public function editshop(){
		$this->doLoadID(300);
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			$user = D('Shop');
			$id=I('post.id','0','int');
	        $where['id']=$id;
	        // 获得要编辑的商户信息
	        $info=$user->where($where)->find();
	        //无此用户信息
	        if(!$info){
	        	$data['error']=1;
	    		$data['msg']="服务器忙，请稍候再试";
	    		return $this->ajaxReturn($data);
	        }
	        // 商户信息更新时间
	       $_POST['update_time']=time();
	       // 自动验证POST数据
	        if($user->create($_POST,2)){
	        	// 保存编辑好的商户信息
	        	if($user->where($where)->save($_POST)){
	        		$data['error']=0;
		    		$data['url']=U('index');
		    		return $this->ajaxReturn($data);
	        	}else{
	        		// 保存失败
	        		$data['error']=1;
		    		$data['msg']=$user->getError();
		    		return $this->ajaxReturn($data);
	        	}
	        }else{
	        	// 自动验证失败
        		$data['error']=1;
	    		$data['msg']=$user->getError();
	    		return $this->ajaxReturn($data);
	        }
		}else{
			// 没有POST数据提交
			// 获得要编辑的商户id
			$id=I('get.id','0','int');
			
			$where['id']=$id;
			$db=D('Shop');
			// 获得要编辑商户信息
			$info=$db->where($where)->find();
			if(!$info){
				$this->error("参数不正确");
			}
			// 分配商户信息
			$this->assign("shop",$info);
			//$enumdata分配商户等级
			include CONF_PATH.'enum/enum.php';
	        $this->assign('enumdata',$enumdata);
	        $this->display();        
		}	
	}

	/**
	 * [routelist 路由管理]
	 * @return [type] [description]
	 */
	public  function routelist(){
		$this->doLoadID(300);
		// 获得商户id
		$id=I('get.id','0','int');
		
		if(empty($id)){
			$this->error("参数不正确");
		}
		// 引用page页码类
		import('@.ORG.AdminPage');
		// 实例化一个操作routmap表的对象
		$db=D('Routemap');
		$where['shopid']=$id;
		// 统计当前商户的路由数量
		$count=$db->where($where)->count();
		// 实例化一个page类
		$page=new AdminPage($count,C('ADMINPAGE'));
		// 获得当前商户数据和旗下的路由数据
		$sql=" select a.* ,b.shopname from ". C('DB_PREFIX')."routemap a left join ". C('DB_PREFIX')."shop b on a.shopid=b.id where a.shopid=".$id." order by a.id desc limit ".$page->firstRow.','.$page->listRows." ";
		$result = $db->query($sql);
     	// 分配页码
        $this->assign('page',$page->show());
        // 当前商户数据和旗下的路由数据
        $this->assign('lists',$result);
		$this->display();        	
	}

	/**
	 * [addroute 添加路由]
	 * @return [type] [description]
	 */
	public  function addroute(){
		$this->doLoadID(300);
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			$db=D('Routemap');
			// 自动验证POST数据
			if($db->create()){
				// 向routemap表中添加数据
				if($db->add()){
					$this->success('添加成功',U('index'));
				}else{
					$this->error("操作失败");
				}
			}else{
				// 自动验证失败
				$this->error($db->getError());
			}
		}else{
			// 获得商户id
			$id=I('get.id','0','int');
			
			if(empty($id)){
				$this->error("参数不正确");
			}
			// 获得当前商户的id,商户名等信息
			$db=D('Shop');
			$where['id']=$id;
			$info=$db->where($where)->field('id,shopname')->find();
			if(!$info){
				$this->error("参数不正确");
			}
			// 分配商户的id,商户名
			$this->assign("shop",$info);
			$this->display();        
		}
	}

	/**
	 * [editroute 编辑路由]
	 * @return [type] [description]
	 */
	public function editroute(){
		$db= D('Routemap');
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			
        	// 自动验证POST数据
        	if($db->create()){
        		// 向routemap表保存修改后数据
    			if($db->save()){
        		   $this->success('更新成功',U('index'));
        		}else{
        			$this->error("操作失败");
        		}
        	}else{
        		// 自动验证失败
        		$this->error($db->getError());
        	}
		}else{
			// 获得要编辑的路由id
			$id=I('get.id','0','int');
			$where['id']=$id;
			// 获得要编辑的路由信息
			$info=$db->where($where)->find();
			
			if(!$info){
				$this->error("参数不正确");
			}
			// 分配当前要编辑的路由信息
			$this->assign("info",$info);
			// 获得当前路由所属商户的商户名和商户id
			$opt=D('Shop');
			$where['id']=$info['shopid'];
			$shopinfo=$opt->where($where)->field('id,shopname')->find();
			// 分配当前路由所属商户的商户名和商户id
	        $this->assign("shop",$shopinfo);
	        $this->display();    
		}
	}

	/**
	 * [delroute 删除路由]
	 * @return [type] [description]
	 */
 	public function delroute(){
     	// 获得要删除的路由id
        $id =I('get.id','0','int');
        // 获得当前要删除的路由信息
        $where['id']=$id;
        $r = D('Routemap')->where($where)->find();
        // 当前的路由信息不存在，就不执行删除
        if($r==false){
        	$this->error('没有此路由信息');
        }else{
        	// 删除当前的路由信息
		    if(D('Routemap')->where($where)->delete()){
		      	$this->success('删除成功');
		    }else{
		      	$this->error('删除失败');
		    }
        }
    }
	
	
}
?>