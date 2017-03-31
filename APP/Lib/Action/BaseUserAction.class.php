<?php
class  BaseUserAction extends BaseAction{
	protected function _initialize()
	{
		 parent::_initialize();
		 $this->isLogin();
	}
	private function isLogin()
    {
        if(!session('uid'))
        {
            $this->redirect('index/index/log');
        }    
    }
}