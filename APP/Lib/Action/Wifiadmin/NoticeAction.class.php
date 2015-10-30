<?php
/**
 * 信息管理
 */
class NoticeAction extends  AdminAction{
	/**
	 * [_initialize 构造函数]
	 * @return [type] [description]
	 */
	protected function _initialize(){
		parent::_initialize();
		$this->doLoadID(700);
	}
	/**
	 * [index 系统消息]
	 * @return [type] [description]
	 */
	public function index(){
		// 页码类
		import('@.ORG.AdminPage');
		// 实例化一个对notice表操作对象
		$db=D('Notice');
		$count=$db->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
		// 获得所有系统信息
        $result = $db->where($where)->field('id,title,info,add_time')->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
        // 分配页码
        $this->assign('page',$page->show());
        // 分配数据
        $this->assign('lists',$result);
		$this->display();

	}
	
	/**
	 * [add 添加系统消息]
	 */
	public function add(){
		// 判断是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			// 设置添加时间、更新时间
			$_POST['add_time'] = time();
			$_POST['update_time'] = time();
			// 实例化一个对notice表操作对象 
			$user=D('Notice');
			// 自动验证POST数据
	        if($user->create($_POST,1)){
	        	// 获得自增主键
	        	$id=$user->add();
	        	if($id>0){
					$this->success('添加成功',U('index','',true,true,true));
				}else{
	        		$this->error('操作出错');
	        	}
	        }else{
	        	// 自动验证POST数据失败
	        	$this->error($user->getError());
	        }
		}else{
	        $this->display();      
		}  
	}

	
	/**
	 * [edit 编辑系统信息]
	 * @return [type] [description]
	 */
	public function edit(){
		// 判读是否有POST数据提交
		if (isset($_POST) && !empty($_POST)){
			// 设置更新时间
			$_POST['update_time'] = time();

			$user = D('Notice');
			$id=I('post.id','0','int');
	        $where['id']=$id;
	        $info=$user->where($where)->find();
	        if(!$info){
	        	//无此用户信息
	        	$data['error']=1;
	    		$data['msg']="服务器忙，请稍候再试";
	    		return $this->ajaxReturn($data);
	        }
	       	// 自动验证POST数据
	        if($user->create($_POST,2)){
	        	if($user->where($where)->save()){
	        		$this->success('更新成功',U('index','',true,true,true));
	        	}else{
	        		$this->error("操作失败");
	        	}
	        }else{
	        	// 自动验证失败
	        	$this->error($user->getError());
	        }
		}else{
			// 没有POST数据提交
			// 获得要编辑的系统信息id
			$id=I('get.id','0','int');
			
			$where['id']=$id;
			$db=D('Notice');
			// 获得编辑的系统信息内容
			$info=$db->where($where)->find();
			if(!$info){
				$this->error("参数不正确");
			}
			// 分配数据
			$this->assign("info",$info);
	        $this->display();        
		}
		
	}

	/**
	 * [del 删除系统信息]
	 * @return [type] [description]
	 */
	public function del(){
			$id=I('get.id','0','int');
			$where['id']=$id;
			$db=D('Notice');
			$info=$db->where($where)->find();
			if(!$info){
				$this->error("没有此系统消息");
			}
			$db->where($where)->delete();
			$this->success('操作成功',U('index'));
     
	}

	
}