<?php
// 后台系统信息管理(系统管理、网站管理)
class SystemAction extends AdminAction{
	/**
	 * [_initialize 构造函数]
	 * @return [type] [description]
	 */
	protected function _initialize(){
		parent::_initialize();
		$this->doLoadID(100);
	}

	public function index(){
		$this->doLoadID(200);
		if (isset($_POST) && !empty($_POST)){
			$this->sitesave();
		}else{
			$this->display();
		}	
	}
	
	public function setting(){
		$this->doLoadID(200);
		if (isset($_POST) && !empty($_POST)){
			$file=$this->_post('files');
			$act=$this->_post('action');
			
			unset($_POST['files']);
			unset($_POST['action']);
			unset($_POST[C('TOKEN_NAME')]);
		
			if($this->update_config($_POST,CONF_PATH.$file)){
		
				$this->success('操作成功',U('System/'.$act));
		
			}else{
		
				$this->error('操作失败',U('System/'.$act));
		
			}
		}else{
			$this->display();
		}
	}

	private  function sitesave(){
	
		$file=$this->_post('files');
		$file='site.php';
		$act=$this->_post('action');
		
		unset($_POST['files']);
		unset($_POST['action']);
		unset($_POST[C('TOKEN_NAME')]);
		// P($_POST);exit;
		if($this->update_config($_POST,CONF_PATH.$file)){
	
			$this->success('操作成功',U('System/'.$act));
	
		}else{
	
			$this->error('操作失败',U('System/'.$act));
	
		}
	
	}
	
	/**
	 * [update_config 更新网站配置信息]
	 * @param  [type] $new_config  [用户配置项]
	 * @param  string $config_file [配置文件]
	 * @return [type]              [description]
	 */
	private function update_config($new_config, $config_file = '') {
		// 定义要写入的文件
		!is_file($config_file) && $config_file = CONF_PATH.'site.php';
		// 判断文件是否可写
		if (is_writable($config_file)) {
			
			$config = require $config_file;
			// 将系统配置项和用户配置合并
			$config = array_merge($config, $new_config);
			// 将配置项写入Conf/site.php文件中
			file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
			// 删除编译文件、临时文件
			@unlink(RUNTIME_FILE);
			return true;
		} else {
			return false;
		}
	
	}
	
	/**
	 * [role 角色列表]
	 * @return [type] [description]
	 */
	public function role(){
		// 引用page工具类
		import('ORG.Util.Page');
		// 实例化一个对role表操作的对象
		$db=D('Role');
		$count=$db->count();
		// 实例化一个Page对象
		$page=new Page($count,C('ADMINPAGE'));
		// 获得角色信息
		$info=$db->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('lists',$info);
		// 分配页码
		$this->assign('page',$page->show());
		$this->display();
	}

	// 添加角色
	/**
	 * [addrole 添加角色]
	 * @return [type] [description]
	 */
	public function addrole(){
		// 实例化一个对role表操作的对象
		$db=D('Role');
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			if($db->create()){
				// 向Role表添加新数据
				if($db->add()){
					$this->success("操作成功",U('Role'));
				}else{
					$this->error("操作失败");
				}
			}else{
				// 自动验证没通过，显示错误信息
				$this->error($db->getError());
			}
		}else{
			// 显示模板
			$this->display();
		}
	}

	/**
	 * [editrole 编辑角色]
	 * @return [type] [description]
	 */
	public function editrole(){
		// 实例化一个对role表操作的对象
		$db=D('Role');
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			// 对提交的post数据自动验证
			if($db->create($_POST,2)){
				// 保存编辑后的数据
				$db->where($where)->save();
				$this->success("操作成功",U('Role'));
			}else{
				// 自动验证失败
				$this->error($db->getError());
			}
		}else{
			// 没有post数据提交时显示
			// 获得GET中的id
			$id=I('get.id','0','int');
			// 查询条件
			$where['id']=$id;
			// 获得当前要编辑的用户信息
			$info=$db->where($where)->find();
			// 存在当前编辑的用户
			if($info!=false){
				// 向模板中分配当前编辑的用户信息
				$this->assign('info',$info);
				$this->display();
			}else{
				// 当前编辑的用户信息不存在
				$this->error("没有此角色信息");
			}
		}	
	}

	/**
	 * [delrole 删除角色]
	 * @return [type] [description]
	 */
	public  function delrole(){
		// 实例化一个对role表操作的对象 
		$db=D('Role');
		// 获得GET数据中的id
		$id=I('get.id','0','int');
		// 查询条件
		$where['id']=$id;
		// 获得当前要删除的用户信息
		$info=$db->where($where)->find();
		// 获得角色的id
		$adminwhere['role']=$info['id'];
		// 统计admin表中要删除当前角色的用户人数
		$count=D('Admin')->where($adminwhere)->count();
		// 数据库中存在当前要删除的角色信息
		if($info!=false){
			// 用户表中还存在当前角色的用户
			if($count>0){
				$this->error("当前角色还有用户存在，不能删除");
				exit;
			}
			// 删除当前角色
			$db->where($where)->delete();
			// 跳转到角色列表
			$this->success("删除成功",U('system/role'));
		}else{
			
			$this->error("没有此角色信息");
		}
	}
	
	/**
	 * [roleaccess 角色权限管理]
	 * @return [type] [description]
	 */
	public function roleaccess(){
		// 实例化一个对权限表操作的对象
		$accdb=D('access');
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			// 获得POST中的角色id(角色类型)
			$del['role_id']=I('post.roleid');
			// 删除当前角色的权限
			$accdb->where($del)->delete();
			// 获得当前角色允许操作的节点
			foreach ($_POST['nodeid'] as $v){
				// 角色id
				$data['role_id']=$del['role_id'];
				// 角色允许的操作的节点
				$data['node_id']=$v;
				// 向权限表中添加数据
				$accdb->add($data);	
			}
			$this->success('操作成功',U('Role'));
		}else{
		// 没有POST数据提交时
			// 获得角色id
			$id=I('get.id','0','int');
			// 查询条件
			$rolewhere['id']=$id;
			// 实例化一个对role表操作的对象
			$roledb=D('Role');
			// 获得当前要分配权限的角色信息
			$info=$roledb->where($rolewhere)->find();
			// 不存在当前要分配权限的角色信息
			if($info==false){
				$this->error("没有此角色信息");
			}else{
				// 分配当前的要分配权限的角色信息
				$this->assign('role',$info);
			}
			// 获得当前要分配权限的角色id
			$accwhere['role_id']=$id;
			// 查找当前角色所允许的操作权限
			$acc=$accdb->where($accwhere)->select();
			// 用于存放节点字符串
			$rs="";
			foreach($acc as $k=>$v){
				$rs.="#".$v['node_id']."#";
			}
			// 分配节点
			$this->assign('acc',$rs);
			// 实例化一个对treenode表操作的对象
			$db=D('treenode');
			//查找启用的节点
			$where['status']=1;
			$trees=$db->where($where)->select();
			// 分配启用的节点数据
			$this->assign('trees',$trees);
			$this->display();
		}
	}
	/**
	 * [userlist 用户列表]
	 * @return [type] [description]
	 */
	public function userlist(){
		// 获得role表中的id,name数据
		$role = M('Role')->getField('id,name');
		// 分配role数据
		$this->assign('role',$role);
		// 引用Page类
		import('ORG.Util.Page');
		// 实例化一个admin表的操作对象
		$db=D('Admin');
		// 查找不是站长的用户
		$where['user']= array('neq',C('SPECIAL_USER'));
		// 统计除站长以外的用户数量
		$count=$db->where($where)->count();
		// 实例化一个页码对象；'ADMINPAGE'=>20,//后台分页数量
		$page=new Page($count,C('ADMINPAGE'));
		// 获得除站长以外的所有用户信息
		$info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		// 分配用户数据
		$this->assign('lists',$info);
		// 分配页码
		$this->assign('page',$page->show());
		$this->display();
	}

	/**
	 * [AddUser 添加用户]
	 */
	public function AddUser(){
		// 判断是否有post数据提交
		if (isset($_POST) && !empty($_POST)){
			// 实例化一个对admin表操作的对象
			$db=D('Admin');
			// 获得用户密码
			$password = $_POST['password'];
			// 获得用户确认密码
            $repassword = $_POST['repassword'];
            // 检测密码和确认密码是否都填写
            if(empty($password) || empty($repassword)){
                $this->error('密码必须填写！');
            }
            // 检测这两个密码是否一致
            if($password != $repassword){
                $this->error('两次输入密码不一致！');
            }
            // 设置添加时间、更新时间、最后登录时间
            $_POST['add_time'] = time();
			$_POST['update_time'] = time();
			$_POST['last_logintime'] = time();
			// 对密码进行加密
			$_POST['password'] = md5($_POST['password']);
			// 对POST数据进行自动验证
			if($db->create()){
			 	// 添加用户数据
                $user_id = $db->add();
                // 获得自增id
                if($user_id){
                 	$data['user_id'] = $user_id;
                 	// 获得角色id
                    $data['role_id'] = $_POST['role'];
                    // 角色用户表添加数据
                    if (M("RoleUser")->data($data)->add()){
                    	// 分配跳转地址
                        $this->assign("jumpUrl",U('userlist'));
                        $this->success('添加成功！');
                    }else{
                        $this->error('用户添加成功,但角色对应关系添加失败!');
                    }
			 	}else{
			 		// 没有自增id,表示添加用户失败
			 		$this->error('添加失败!');
			 	}
			}else{
				// 自动验证失败
			 	$this->error($db->getError());
			}
		}else{
			// 没有POST数据提交
			// 实例化一个对role表操作的对象
			$roledb=D('Role');
			// 条件是启用状态
			$where['status']=1;
			// 获得角色表中的id、name数据
			$role=$roledb->where($where)->field('id,name')->select();
			// 分配角色名称数据
			$this->assign('role',$role);
			$this->display();
		}

	}

	/**
	 * [edituser  编辑用户]
	 * @return [type] [description]
	 */
	public function edituser(){
		// 实例化一个对admin操作的对象
		$db=D('Admin');
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			// 获得新密码
			$password = $_POST['password'];
			// 获得新密码的确认密码
            $repassword = $_POST['repassword'];
            // 检测是否都填写了密码和确认密码
            if(!empty($password) || !empty($repassword)){
            	// 检测两次输入的密码是否一致
                if($password != $repassword){
                    $this->error('两次输入密码不一致！');
                }
                // 对新密码进行md5加密
                $_POST['password'] = md5($password);
            }else{
            	$this->error('密码必须填写！');
            }
			if($db->create($_POST,2)){
				if($db->where($where)->save()){
					$rwhere['user_id'] = $_POST['id'];
                    $data['role_id'] = $_POST['role'];
                    M("RoleUser")->where($rwhere)->save($data);
                    $this->assign("jumpUrl",U('userlist'));
                    $this->success('编辑成功！');
				}else{
					$this->error("错误");
				}
			}else{
				$this->error($db->getError());
			}
		}else{
			//获得要编辑用户的id 
			$id=I('get.id','0','int');
			// 查找条件
			$where['id']=$id;
			// 获得要编辑的用户信息
			$info=$db->where($where)->find();
			// 检测当前编辑的用户是否存在
			if($info!=false){
				// 分配当前编辑用户信息
				$this->assign('info',$info);
				// 实例化一个对role表操作的对象
				$roledb=D('Role');
				// 获得角色的名称、id
				$role=$roledb->field('id,name')->select();
				// 分配角色信息
				$this->assign('role',$role);
				$this->display();
			}else{
				$this->error("没有此用户信息");
			}
		}
	}
	/**
	 * [deluser 删除当用户]
	 * @return [type] [description]
	 */
	public function deluser(){
		// 实例化一个对admin操作的对象
		$db=D('Admin');
		// 获得当前要删除的用户id
		$id=I('get.id','0','int');
		// 查找条件
		$where['id']=$id;
		// 获得当前要删除的用户信息
		$info=$db->where($where)->find();
		// 存在当前要删除的用户，就删除
		if($info!=false){
			$db->where($where)->delete();
			$this->error("删除成功",U('system/userlist'));
		}else{
			$this->error("没有此用户信息");
		}
	}
	
	
}