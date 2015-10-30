<?php
require_once(DATA_PATH.'nusoap/nusoap.php');
/*
 * 短信平台接口代码
 */
class XCSMS{
	/**
	 * 网关地址
	 */
	var $url;
	/**
	 * 帐号
	 */
	var $user;
	/**
 	* 密码
 	* Enter description here ...
 	* @var unknown_type
 	*/
	var $password;

	/**
	 * webservice客户端
	 */
	var $soap;
	
	/**
	 * 默认命名空间
	 */
	var $namespace = 'http://tempuri.org/';
	
	/**
	 * 往外发送的内容的编码,默认为 GBK
	 */
	var $outgoingEncoding = "GBK";
	
	/**
	 * 往内发送的内容的编码,默认为 GBK
	 */
	var $incomingEncoding = '';
	// 构造函数
	function __construct($server,$u,$p,$proxyhost = false,$proxyport = false,$proxyusername = false, $proxypassword = false, $timeout = 2, $response_timeout = 30){
		$this->url=$server;
		$this->user=$u;
		$this->password=$p;
		
		/**
		 * 初始化 webservice 客户端
		 */	
		
		$this->soap = new nusoap_client($server,true); 
		// 默认往外设置编码为utf-8；
		$this->soap->soap_defencoding ='GBK';
		$this->soap->decode_utf8 = false;	
		
	}
	/**
	 * 设置发送内容的字符编码
	 * @param string $outgoingEncoding 发送内容字符集编码
	 */
	function setOutgoingEncoding($outgoingEncoding)
	{
		$this->outgoingEncoding =  $outgoingEncoding;
		$this->soap->soap_defencoding = $this->outgoingEncoding;
		
	}
	
	function setUser($u){
		$this->user=$u;
	}
	function setPwd($p){
		$this->password=$p;
	}
	

	/**
	 * 设置接收内容 的字符编码
	 * @param string $incomingEncoding 接收内容字符集编码
	 */
	function setIncomingEncoding($incomingEncoding)
	{
		$this->incomingEncoding =  $incomingEncoding;
		$this->soap->xml_encoding = $this->incomingEncoding;
	}	
	
	
	
	function setNameSpace($ns)
	{
		$this->namespace = $ns;
	}
	
	function test(){
		dump ($this->soap->call('HelloWorld', array()));
	}
	/**
	 * 
	 * 获取短信帐号余额
	 */
	function GetSmsAccount()
	{
		
		
		$params = array('key'=>$this->user,'pwd'=>$this->password);
		
		$result = $this->soap->call("GetSmsAccount",$params);

		return $result['GetSmsAccountResult'];
	}
	/**
	 * 
	 * 获取短信单价
	 */
	function GetSmsPrice()
	{
		
		
		$params = array('key'=>$this->user,'pwd'=>$this->password);
		
		$result = $this->soap->call("GetSmsPrice",$params);

		return $result['GetSmsPriceResult'];
	}
	
	function SendSms($phones,$msg){
		$params = array('key'=>$this->user,'pwd'=>$this->password,'phone'=>$phones,'info'=>$msg);
		
		$result = $this->soap->call("SmsSendMany",$params);
	
		return $result['SmsSendManyResult'];
	}
}