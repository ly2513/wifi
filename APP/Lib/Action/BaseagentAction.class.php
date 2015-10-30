<?php
/*
 * 后台基本信息
 */
class BaseagentAction extends BaseAction{
	public   $aid;//代理商用户ID
	protected  function _initialize(){
		parent::_initialize();
		if(!session('aid')||session('aid')==null||session('aid')==''){
			$this->redirect('index/index/alog');
		}else{
			$this->aid=session('aid');
			
			$this->loadMenu();
		}
	}
	private  function  loadMenu(){
		$path=CONF_PATH.GROUP_NAME."/Menu.php";
		if(is_file($path)){
			$config = require $path;
		}
		$this->assign("menu",$config);
	}
}