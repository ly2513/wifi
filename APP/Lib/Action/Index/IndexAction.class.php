<?php

class IndexAction extends BaseAction {
    // public function index(){
    // 	$this->display('log');
    // }
    public function index(){
    	$this->display();
    }
    public function device(){

    	$this->display();
    }
    public function service(){

    	$this->display();
    }
    
    public function log(){
    	
    	$this->display();
    }
	public function alog(){
    	
    	$this->display();
    }
 	public function reg()
    {
        $this->display();
    }
   
	public function doagentlog(){
		if(isset($_POST) && !empty($_POST)){
	    	$user = isset($_POST['user']) ? strval($_POST['user']) : '';
	        $pass = isset($_POST['password']) ? strval($_POST['password']) : '';
	        $userM =D('Agent'); 
	        $pass=md5($pass);
	        $uid = $userM->where("account = '{$user}' AND password = '{$pass}'")->field('id,account')->find();
			
	        if($uid){
	            session('aid',$uid['id']);
	            session('account',$uid['account']);
	            session('agentname',$uid['name']);
	            $data['error']=0;
	    		$data['msg']="";
	    		$data['url']=U('agent/index/index');
	    		return $this->ajaxReturn($data);
	           // $this->success('登录成功','index.php?m=User');
	        }else{
	        	$data['error']=1;
	    		$data['msg']="帐号信息不正确";
	    		return $this->ajaxReturn($data);
	           
	        }
    	}else{
    		$data['error']=1;
    		$data['msg']="服务器忙，请稍候再试";
    		return $this->ajaxReturn($data);
    	}
	}
 	
	public function alogout(){
		    session(null);
          	$this->redirect('index/index/alog');
	}


	
}