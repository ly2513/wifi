<?php
/**
 * 上网认证模板设置控制器
 */
class AuthsetAction extends  BaseUserAction{
	// 用于存放商户模板id,模板目录、广告显示时间
	public  $tplset;
	/**
	 * [getShop 获得商户模板id,模板目录、广告显示时间]
	 * @return [type] [description]
	 */
	private function getShop(){
		$id=session('uid');
		$shop=D('Shop')->where(array('id'=>$id))->field('tpl_id,tpl_path,ad_show_time,authmode')->find();
		$this->tplset= $shop;
		return $shop;
	}
	
	/**
	 * [tplset 模板设置]
	 * @return [type] [description]
	 */
	public function tplset(){
		// 获得商户模板id,模板目录、广告显示时间,认证方式
		$shop=$this->getShop();
		$this->assign('info',$this->tplset);
		// 获得所有的模板数据
		$list=D('Authtpl')->where('state=1')->order('id asc')->select();
		$this->assign('tpl',$list);

		$this->assign('a','authtplset');
		
		$this->display();
	}

	/**
	 * [dotplset 执行模板设置]
	 * @return [type] [description]
	 */
	public function dotplset(){
		// 获得商户模板id,模板目录、广告显示时间
		$shop=$this->getShop();
		// 当前商户id
    	$id=session('uid');
    	// 前台异步将模板id传过来
		$tplid=I('get.tpl','0','int');

		// $this->assign('authmode',$authmode);
		// 从数据库检测该模板是否存在
		$tpl=D('Authtpl')->where(array('id'=>$tplid))->find();
		// 模板信息不存在
		if($tpl==false){
			exit;//不操作模板设置
		}
		// 获得当前商户设置的认证模式
		$authmode=explode('#', $shop['authmode']);
		// 定义新数组，存放认证模式
		$auth=array();
		// 过滤数组
		foreach ($authmode as $key => $value) {
			if($key%2==0) continue;
			$auth[]=$value;
		}
		// 没有设置手机认证，就不能选认证模板
		if($tplid==1007){
			if(in_array(1,$authmode)){
				$result['status']=1;
				$result['message']='设置手机认证模板成功';
				echo json_encode($result);
			}else{
				$result['status']=0;
				$result['message']='设置手机认证模板失败';
				echo json_encode($result);
				exit;
			}
		}else if($tplid==1008){
			// 没有设置注册认证，就不能选注册认证模板
			if(in_array(0,$authmode)){
				$result['status']=1;
				$result['message']='设置注册认证模板成功';
				echo json_encode($result);
			}else{
				$result['status']=0;
				$result['message']='设置注册认证模板失败';
				echo json_encode($result);
				exit;
			}
		}else if($tplid==1009){
			// 没有设置手微信关注认证，就不能选微信关注认证模板
			if(in_array(4,$authmode)){
				$result['status']=1;
				$result['message']='设置微信关注认证模板成功';
				echo json_encode($result);
			}else{
				$result['status']=0;
				$result['message']='设置微信关注认证模板失败';
				echo json_encode($result);
				exit;
			}
		}else{
			$result['status']=1;
			$result['message']='设置此认证模板成功';
			echo json_encode($result);
		}
		$where['id']=$id;
		if($this->tplset){
			//更新
			D('Shop')->where($where)->save(array('tpl_id'=>$tplid,'tpl_path'=>$tpl['keyname']));
		}
	
	}

	/**
	 * [adshowtimeset 广告显示时间设置]
	 * @return [type] [description]
	 */
	public function adshowtimeset(){
		// 获得商户模板id,模板目录、广告显示时间
		$this->getShop();
		$this->assign('info',$this->tplset);
		// 判断是否有POST数据传过来
		if(isset($_POST) && !empty($_POST)){
			if(!isNumber($_POST['ad_show_time']) || $_POST['ad_show_time']<0){
				$this->error('时间必须为数字,且不能为负数');
			}
			// 保存商户广告显示时间
			$id=session('uid');
			$where['id']=$id;
			D('Shop')->where($where)->save($_POST);
			$this->success ( '设置成功', U ( 'Authset/tplset' , true, true, true) );
		}
		$this->display();
	}
	
}