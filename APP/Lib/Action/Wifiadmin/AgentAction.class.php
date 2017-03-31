<?php
/**
 * 代理商管理动作
 */
class AgentAction extends  AdminAction{
	/**
	 * [_initialize 构造函数]
	 * @return [type] [description]
	 */
	protected function _initialize(){
		parent::_initialize();
		$this->doLoadID(400);
	}

	/**
	 * [ShowAjaxError 显示ajax错误信息]
	 * @param [type] $msg [description]
	 */
	private  function ShowAjaxError($msg){
				$data['msg']=$msg;
				$data['error']=1;
				$this->ajaxReturn($data);
	} 

	/**
	 * [index 代理商列表]
	 * @return [type] [description]
	 */
	public function index(){
		// 引用AdminPage页码工具类
		import('@.ORG.AdminPage');
		$db=D('Agent');
		// 统计代理商数量
		$count=$db->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
		// 获得代理商的信息
        $result = $db->field('id,account,name,add_time,linker,phone,money')->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
        // 分配页码
        $this->assign('page',$page->show());
        // 分配代理商数据
        $this->assign('lists',$result);
		$this->display();	
	}
	
	/**
	 * [add 添加代理商]
	 */
	public function add(){
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			// 设置添加时间
			$_POST['add_time'] = time();
			// 设置更新时间
			$_POST['update_time'] = time();
			// 代理等级不能为空
			if(empty($_POST['level'])){
				$this->ShowAjaxError('请选择代理商级别');
			}
			// 代理等级
			$lvid=intval( $_POST['level']);
			if($lvid<0){
				$this->ShowAjaxError('请选择代理商级别');
			}
			// 实例化一个对agent表操作的对象
			$db=D('Agent');
			// 代理商密码加密
			$_POST['password']=md5($_POST['password']);
			// 自动验证POST数据
			if($db->create()){
				// 添加代理商数据，$insertid：自增id
				$insertid=$db->add();
				// 添加代理商成功
				if($insertid){
					// 异步将提示信息传到前台
					$data['url']=U('index');
					$data['error']=0;
					$this->ajaxReturn($data);
				}else{
					$this->ShowAjaxError('添加代理商操作失败');
				}
			}else{
				// 自动验证失败
				$this->ShowAjaxError($db->getError());
			}	
		}else{
			// 实例化一个对agentlevel表操作的对象
			$lvdb=D('Agentlevel');
			// 查找状态为正常的代理商
			$where['state']=1;
			$lvinfo=$lvdb->where($where)->field('id,title') ->select();
			// 分配代理商等级数据
			$this->assign('lvlist',$lvinfo);
			$this->display();
		}
	}
	
	/**
	 * [edit 编辑代理商]
	 * @return [type] [description]
	 */
	public function edit(){
		// 实例化一个对agent表操作对象
		$db=D('Agent');
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			// 设置更新时间
			$_POST['update_time'] = time();
			// 代理商等级不能为空
			if(empty($_POST['level'])){
				$this->ShowAjaxError('请选择代理商级别');
			}
			$lvid=intval( $_POST['level']);
			// 代理商等级不能小于0
			if($lvid<0){
				$this->ShowAjaxError('请选择代理商级别');
			}
			// 密码不能为空
		     if(!empty($_POST['password']) ){
               	$password = $_POST['password'];
               	// 对密码加密
               	$_POST['password']=md5($password);
            }else{
            	// 密码为空，就删除$_POST['password']
            	unset(	$_POST['password']);
            }
            // 自动验证POST数据
			if($db->create($_POST,2)){
				// 保存编辑后的商户数据
				if($db->where($where)->save()){
					// 编辑代理商成功，跳转地址
					$data['url']=U('index');
					$data['error']=0;
					$this->ajaxReturn($data);
				}else{
					$this->ShowAjaxError('编辑失败');
				}
			}else{
				// 自动验证失败
				$this->ShowAjaxError($db->getError());	
			}
		}else{
			// 实例化一个对agentlevel表操作对象
			$lvdb=D('Agentlevel');
			// 查找状态正常的代理商等级
			$where['state']=1;
			$lvinfo=$lvdb->where($where)->field('id,title') ->select();
			// 分配当前代理商等级数据
			$this->assign('lvlist',$lvinfo);
			// 获得编辑的代理商id
			$id=I('get.id','0','int');
			// 查找当前要编辑的代理商数据
			$where['id']=$id;
			$info=$db->where($where)->find();
			// 存在该代理商信息，就分配代理商数据
			if($info!=false){
				$this->assign('info',$info);
				$this->display();
			}else{
				$this->error("没有此等级信息");
			}
		}
	}
	
	/**
	 * [del 删除代理商]
	 * @return [type] [description]
	 */
	public  function del(){
		// 获得当前要删除的代理商id
		$id=I('get.id','0','int');
		// 获得当前代理商旗下的子代理商数据
		$db=D('Agent');
		$dbshop=D('shop');
		$agwhere['pid']=$id;
		$count=$dbshop->where($agwhere)->count();
		// 代理商旗下还要代理商，就不能进行删除
		if($count>0){
			$this->error("当前代理商包含商户账号，不能删除");
		}else{
			// 获得当前要删除的代理商数据
			$where['id']=$id;
			$db->where($where)->delete();
			$this->success("操作成功",U('index'));
		}	
	}
	
	/**
	 * [level 代理商等级列表]
	 * @return [type] [description]
	 */
	public function level(){
		// 引用AdminPage页码工具类
		import('@.ORG.AdminPage');
		// 实例化一个对agentlevel表操作的对象
		$db=D('Agentlevel');
		// 统计代理商等级数量
		$count=$db->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
		// 获得正常状态的代理商等级数据
		$where['state']=1;
        $result = $db->where($where)->field('id,title,openpay,add_time,state')->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
        // 分配页码
        $this->assign('page',$page->show());
        // 分配正常状态的代理商等级数据
        $this->assign('lists',$result);
		$this->display();
	}
	
	/**
	 * [addlevel 添加代理商等级]
	 * @return [type] [description]
	 */
	public function addlevel(){
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			$db=D('Agentlevel');
			// 自动验证POST数据
			if($db->create()){
				// 添加代理商等级数据
				if($db->add()){
					$this->success("添加成功",U('level'));
				}else{
					$this->error("操作失败");
				}
			}else{
				// 自动验证失败
				$this->error($db->getError());
			}
		}else{
			// 显示添加界面
			$this->display();
		}
	}
	
	/**
	 * [editlevel 编辑代理商等级]
	 * @return [type] [description]
	 */
	public function editlevel(){
		// 实例化一个对agentlevel表操作的对象
		$db=D('Agentlevel');
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			// 自动验证POST数据
			if($db->create($_POST,2)){
				// 保存编辑好的代理商等级信息
				if($db->where($where)->save()){
					$this->success("操作成功",U('level'));
				}else{
					$this->error("没有此角色信息");
				}
			}else{
				// 自动验证失败
				$this->error($db->getError());
			}	
		}else{
			// 获得要编辑的代理商等级id
			$id=I('get.id','0','int');
			// 获得当前要编辑的代理商等级数据
			$where['id']=$id;
			$info=$db->where($where)->find();
			// 存在当前编辑的代理商等级数据，就分配
			if($info!=false){
				$this->assign('info',$info);
				$this->display();
			}else{
				$this->error("没有此等级信息");
			}
		}
	}
	
	/**
	 * [delevel 删除代理商等级]
	 * @return [type] [description]
	 */
	public function delevel(){
		// 获得要删除的代理商等级id
		$id=I('get.id','0','int');
		// 实例化一个对agentlevel表操作的对象
		$db=D('Agentlevel');
		// 实例化一个对agent表操作的对象
		$dbag=D('Agent');
		// 统计当前要删除的代理商等级下的代理商数据
		$agwhere['level']=$id;
		$count=$dbag->where($agwhere)->count();
		// 当前代理商等级下还有代理商，不能删除
		if($count>0){
			$this->error("当前等级包含代理商账号，不能删除");
		}else{
			// 当前代理商等级下没有代理商
			$where['id']=$id;
			$db->where($where)->delete();
			$this->success("操作成功",U('level'));
		}	
	}

	/**
	 * [paylist 费用进出记录列表]
	 * @return [type] [description]
	 */
	public function paylist(){
		// 引用页码工具类
		import('ORG.Util.Page');
		// 实例化一个对agentpay表操作对象
		$db=D('Agentpay');
		// 统计扣款记录
		$count=$db->count();
		$page=new Page($count,C('ADMINPAGE'));
		// 获得代理商的账号、代理商名称，操作日期，操作类型
		$sql="select a.*,b.account,b.name from ".C('DB_PREFIX')."agentpay a left join ".C('DB_PREFIX')."agent b on a.aid=b.id order by a.add_time desc limit ".$page->firstRow.','.$page->listRows;
		$result=$db->query($sql);
		// 分配页码
		$this->assign('page',$page->show());
		// 分配代理商的账号、代理商名称，操作日期，操作类型
        $this->assign('lists',$result);
		$this->display();
	}

	/**
	 * [dopay 账号调整(扣款记录)]
	 * @return [type] [description]
	 */
	public function dopay(){// P(1);exit;

		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			// 实例化一个对agent表操作对象
			$dbagent=D('Agent');
			// 获得代理商的id
			$id=I('post.aid','0','int');
			// 获得当前代理商的信息
			$where['id']=$id;
			$info=$dbagent->where($where)->field('id,account,money')->find();
				// P($_POST);exit;
			if(!$info){

				$this->error("没有此代理商信息");
			}
				// 实例化一个对agentpay表操作对象
			$db=D('Agentpay');
			// 操作类型
			$do=$_POST['do'];
			// 获得当前代理商账号的余额
			$money=$info['money']==null?0:$info['money'];
			// 获得支付的金额
			$pay=$_POST['paymoney']==null?0:$_POST['paymoney'];
			// 
			$oldmoney='';
	        $nowmoney='';
	        // 自动验证POST数据
	        if($db->create()){
	        	// $do为0表示扣款，为1表示充值
	        	if($do=="0"){
					if($money<$pay){
						$this->error("当前帐号余额不足，无法扣款");
					}
					$oldmoney=$money;
					$nowmoney=$money-$pay;
				}else{
					$oldmoney=$money;
					$nowmoney=$money+$pay;
				}
				$_POST['oldmoney']=$oldmoney;
				$_POST['nowmoney']=$nowmoney;
				// P($_POST);exit;
				// 向Agentpay表中添加数据
				if($db->add()){
					// $do为0表示扣款
					if($do=="0"){
						$dbagent->where($where)->setDec('money',$pay);
					}else{
						$dbagent->where($where)->setInc('money',$pay);
					}
					$this->success("操作成功",U('index'));
				}else{
					$this->error("操作失败");
				}
	        }else{
	        	// 自动验证失败
	        	$this->error($db->getError());
	        }
		}else{
			// 实例化一个对agent表操作对象
			$dbagent=D('Agent');
			// 获得代理商的id
			$id=I('get.id','0','int');
			// p($id);exit;
			// 获得当前代理商的信息
			$where['id']=$id;
			$info=$dbagent->where($where)->field('id,account,money')->find();
			if(!$info){
				$this->error("没有此代理商信息");
			}
			// 分配当前代理商信息
			$this->assign('info',$info);
			$this->display();
		}
	}
}