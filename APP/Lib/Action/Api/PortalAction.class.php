<?php
class PortalAction extends BaseApiAction{
	private  $tpl;
	private $uid;
	private $classinfo;
	public  function _initialize(){
		parent::_initialize();
		$this->getShopInfo();
	}
	/*
	 * 获取用户ID
	 */
	private function getShopInfo(){
		
		$gw_id=$_GET['gw_id'];
				$sql="select a.*,b.shopname,b.authmode,b.maxcount,b.linkflag,b.sh,b.eh,b.pid,b.countflag,b.countmax,b.tpl_path from ".C('DB_PREFIX')."routemap a left join ".C('DB_PREFIX')."shop b on a.shopid=b.id ";
		$sql.=" where a.gw_id='".$gw_id."' limit 1";
		$db=D('Routemap');
		$info=$db->query($sql);
		$id=$info[0]['shopid'];
		$this->uid=$info[0]['shopid'];
		if(!isNumber($id)){
			$this->error("参数不正确");
		}
		$tmpdb=D('Wap');
		$where['uid']=$id;
		$info=$tmpdb->where($where)->find();
            
		if($info){
			$this->uid=$id;
			Cookie('wapuid',$id);
			$this->tpl=$info;
			$this->assign('siteinfo',$info);
			$catedb=D('Wapcatelog');
			$where['uid']=$this->uid;
			$catelog=$catedb->where($where)->select();
			$this->classinfo=$catelog;
			$this->assign('classinfo',$catelog);
		}
	}
	public function index(){
		$db=D('Shop');
		$whereinfo['id']=$this->uid;
        $info=$db->where($whereinfo)->find();
		if($info['authaction']==1){
		   $jump=$info['jumpurl'];
                   redirect($jump, 2, '页面跳转中...');
		}
		

		if($info['authaction']==2){
		
		$jump=cookie('gw_url');
		redirect($jump, 2, '页面跳转中...');
		}
		if($info['authaction']==3){
	         	redirect(U('api/wap/index',array('sid'=>1)), 2, '页面跳转中...');
		}
		
	}
	
	public function lists(){
		import('ORG.Util.Page');
		
		$pg=$this->_get('p','intval');
		$pagesize=C('WAP_List');
		if(!$pg) $pg=1;
		if($pg<1) $pg=1;
		$where['cid']=$this->_get('classid','intval');
		$news=D('Arts');
	

		$count=$news->where($where)->count();
		$page=new Page($count,$pagesize);
		
		$listinfo=$news->where($where)->limit($page->firstRow.",".$page->listRows)->select();
		
		foreach($this->classinfo as $k=>$v){
			if($v['id']==$where['cid']){
				$nowclass=$v;
				break;
			}
		}
		
		
		
		$maxpage=ceil($count/$pagesize);
		if($maxpage==0){
			$maxpage=1;
		}
		if($pg>$maxpage){
			$pg=$maxpage;
		}
		$this->assign('nowclass',$nowclass);
		
		$this->assign('info',$this->wxdata);

		$this->assign('page',$maxpage);//总页数
		$this->assign('p',$pg);//当前页
		
		$this->assign('listinfo',$listinfo);//当前页
		if($this->tpl['list_tpl_path']==""){
		
			$this->display('list_t1');
		}else{
			$this->display($this->tpl['list_tpl_path']);
		}

		
		
	}

	public function info(){
		$id=$this->_get('id','intval');
		$news=D('Arts');
		$where['id']=$id;
		$where['uid']=cookie('wapuid');
		$data=$news->where($where)->find();
		
		$this->assign('data',$data);
		if($this->tpl['info_tpl_path']==""){
		
			$this->display('info_t1');
		}else{
			$this->display($this->tpl['info_tpl_path']);
		}
	
	}
}