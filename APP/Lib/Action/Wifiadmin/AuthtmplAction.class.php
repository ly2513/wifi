<?php
class AuthtmplAction extends AdminAction{
	protected function _initialize(){
		parent::_initialize();
		$this->doLoadID(1000);
	}
	
	public function index(){
		import('@.ORG.AdminPage');
		$db=D('Authtpl');
		$where=" true ";
		if (isset($_POST) && !empty($_POST)){
			if(isset($_POST['id'])&&$_POST['id']!=""){
					$map['id']=$_POST['id'];
					$where.=" and id like '%%%s%%'";	
			}
			if(isset($_POST['tpname'])&&$_POST['tpname']!=""){
					$map['tpname']=$_POST['tpname'];
					$where.=" and tpname like '%%%s%%'";
			}
			
			
			$_GET['p']=0;
		}else{
			if(isset($_GET['id'])&&$_GET['id']!=""){
					$map['id']=$_POST['id'];
					$where.=" and id like '%%%s%%'";	
			}
			if(isset($_GET['tpname'])&&$_GET['tpname']!=""){
					$map['tpname']=$_POST['tpname'];
					$where.=" and tpname like '%%%s%%'";
			}
		}
		$count=$db->where($where,$map)->count();
		$page=new AdminPage($count,C('ADMINPAGE'));
        $result = $db->where($where,$map)->field('id,tpname,keyname,pic,state,group,ownerid,oneflag,add_time')->limit($page->firstRow.','.$page->listRows)->order('id desc') -> select();
		
        $this->assign('page',$page->show());
        $this->assign('lists',$result);
      
		$this->display();
	}
	
	public function getagent(){
		
		import('@.ORG.AdminAjaxPage');
		$db=D('Agent');
		
		$count=$db->where()->count();
		$page=new AdminAjaxPage($count,C('ADMINPAGE'));
		//$page=new AdminAjaxPage($count,1,'AjaxPage2(this);');
        $result = $db->where($where)->field('id,account,name')->limit($page->firstRow.','.$page->listRows)->order('add_time desc') -> select();
       	$back['list']=$result;
    	$back['pg']=$page->show();
    	return $this->ajaxReturn($back);
		
	}
	
	public function getshop(){
		
		import('@.ORG.AdminAjaxPage');
		$db=D('Shop');
		if (isset($_POST) && !empty($_POST)){
			if(isset($_POST['sname'])&&$_POST['sname']!=""){
					$map['sname']=$_POST['sname'];
					$where.=" and a.shopname like '%%%s%%'";	
			}
			if(isset($_POST['slogin'])&&$_POST['slogin']!=""){
					$map['slogin']=$_POST['slogin'];
					$where.=" and a.account like '%%%s%%'";
			}
			if(isset($_POST['phone'])&&$_POST['phone']!=""){
					$map['phone']=$_POST['phone'];
					$where.=" and a.phone like '%%%s%%'";
			}
			if(isset($_POST['agent'])&&$_POST['agent']!=""){
					$map['agent']=$_POST['agent'];
					$where.=" and b.name like '%%%s%%'";
			}
			
			$_GET['p']=0;
		}else{
			if(isset($_GET['sname'])&&$_GET['sname']!=""){
					$map['sname']=$_GET['sname'];
					$where.=" and a.shopname like '%%%s%%'";
					
			}
			if(isset($_GET['slogin'])&&$_GET['slogin']!=""){
					$map['slogin']=$_GET['slogin'];
					$where.=" and a.account like '%%%s%%'";
			}
			if(isset($_GET['phone'])&&$$_GET['phone']!=""){
					$map['phone']=$_GET['phone'];
					$where.=" and a.phone like '%%%s%%'";
			}
			if(isset($_GET['agent'])&&$_GET['agent']!=""){
					$map['agent']=$_GET['agent'];
					$where.=" and b.name like '%%%s%%'";
			}
		}
		
		$sqlcount=" select count(*) as ct from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id ";
		if(!empty($where)){
			$sqlcount.=" where true ".$where;
		}
		
		$rs=$db->query($sqlcount,$map);
		
		$count=$rs[0]['ct'];
		$page=new AdminAjaxPage($count,C('ADMINPAGE'),'AjaxPageShop(this);');
		foreach($map as $k=>$v){
			$page->parameter.=" $k=".urlencode($v)."&";//赋值给Page";
		}
		
		$sql=" select a.id,a.shopname,a.add_time,a.linker,a.phone,a.account,a.maxcount,a.linkflag,b.name as agname from ". C('DB_PREFIX')."shop a left join ". C('DB_PREFIX')."agent b on a.pid=b.id ";
		if(!empty($where)){
			$sql.=" where true ".$where;
		}
		$sql.=" order by a.add_time desc limit ".$page->firstRow.','.$page->listRows." ";
		
      
		$result = $db->query($sql,$map);
     	
        
        $back['list']=$result;
        
    	$back['pg']=$page->show();
    	return $this->ajaxReturn($back);
	}
	
	public function del(){
		$id=$_GET['id'];
		$db=D('Authtpl');
		$where['id']=$id;
		$count=$db->where($where)->count();
		if($count>0){
			$db->where($where)->delete();
			$this->success("删除成功",U('index'));
		}else{
			$this->error("没有此模板信息");
		}
		
	}
	public function edit(){
			if (isset($_POST) && !empty($_POST)){
			$id=$_POST['id'];
			$db=D('Authtpl');
			$where['id']=$id;
			$count=$db->where($where)->count();
			if($count==0){
				$back['error']=1;
				$back['msg']="没有此模板信息";
				$this->ajaxReturn($back);
			}
			$g=$_POST['group'];
			if((int)$g>1){
				$pid=$_POST['ownerid'];
				if(empty($pid)||$pid==""||!isNumber($pid)){
					$back['error']=1;
					$back['msg']="请选择模板所属对象".$pid;
					$this->ajaxReturn($back);
				}
			}
			
			$db=D('Authtpl');
			if($db->create()){
				if($db->where($where)->save()){
					$back['error']=0;
					$back['url']=U('index');
		
					$this->ajaxReturn($back);
				}else{
					$back['error']=1;
					$back['msg']="添加失败";
					log::write($db->getError());
					$this->ajaxReturn($back);
				}
			}else{
				$back['error']=1;
				$back['msg']="添加失败";
				log::write($db->getError());
				$this->ajaxReturn($back);
			}
			
		}else{
			$id=$_GET['id'];
			$db=D('Authtpl');
			$where['id']=$id;
			$info=$db->where($where)->find();
			if($info){
				switch($info['group']){
					case 1:break;
					case 2:
						$sid=$info['ownerid'];
						$ainfo=D('Agent')->where(array('id'=>$sid))->field('id,name')->find();
						$this->assign('ainfo',$ainfo);
						
						break;
					case 3:
						$sid=$info['ownerid'];
						$ainfo=D('Shop')->where(array('id'=>$sid))->field('id,shopname')->find();
						$this->assign('sinfo',$ainfo);
						break;
				}
				
			}else{
				$this->error("没有此模板信息");
				exit;
			}
			
			$this->assign('info',$info);
			$this->display();
		}
		
	}
	
	public function add(){
		if (isset($_POST) && !empty($_POST)){
			
			$g=$_POST['group'];
			if((int)$g>1){
				$pid=$_POST['ownerid'];
				if(empty($pid)||$pid==""||!isNumber($pid)){
					$back['error']=1;
					$back['msg']="请选择模板所属对象";
					$this->ajaxReturn($back);
				}
			}
			
			$db=D('Authtpl');
			if($db->create()){
				if($db->add()){
					$back['error']=0;
					$back['url']=U('index');
		
					$this->ajaxReturn($back);
				}else{
					$back['error']=1;
					$back['msg']="添加失败";
					log::write($db->getError());
					$this->ajaxReturn($back);
				}
			}else{
				$back['error']=1;
				$back['msg']="添加失败";
				log::write($db->getError());
				$this->ajaxReturn($back);
			}
			
		}else{
			$this->display();
		}
		

	}
	
}