<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


/**
 * ThinkPHP RESTFul 控制器基类 抽象类
 * @category   Think
 * @package  Think
 * @subpackage  Core
 * @author   liu21st <liu21st@gmail.com>
 */
abstract class Action {
	
	// 当前Action名称
	private $name = '';
	// 视图实例
	protected $view = null;
	protected $_method = ''; // 当前请求类型
	protected $_type = ''; // 当前资源类型
	// 输出类型
	protected $_types = array ();
	
	/**
	 * 架构函数 取得模板对象实例
	 * @access public
	 */
	public function __construct() {
		//$this->checkLiences ();
		//实例化视图类
		$this->view = Think::instance ( 'View' );
		
		defined ( '__EXT__' ) or define ( '__EXT__', '' );
		if ('' == __EXT__ || false === stripos ( C ( 'REST_CONTENT_TYPE_LIST' ), __EXT__ )) {
			// 资源类型没有指定或者非法 则用默认资源类型访问
			$this->_type = C ( 'REST_DEFAULT_TYPE' );
		} else {
			$this->_type = __EXT__;
		}
		
		// 请求方式检测
		$method = strtolower ( $_SERVER ['REQUEST_METHOD'] );
		if (false === stripos ( C ( 'REST_METHOD_LIST' ), $method )) {
			// 请求方式非法 则用默认请求方法
			$method = C ( 'REST_DEFAULT_METHOD' );
		}
		$this->_method = $method;
		// 允许输出的资源类型
		$this->_types = C ( 'REST_OUTPUT_TYPE' );
		
		//控制器初始化
		if (method_exists ( $this, '_initialize' ))
			$this->_initialize ();
	}
	private function checkLiences() {
		if (! file_exists ( RUNTIME_PATH . 'lock.txt' )) { //锁定文件不存在
			$liences = ""; //TODO 查询出来
			$L = M('Liences');
			$rs = $L->where()->find();
			if(!empty($rs)){
				$liences = $rs['liences'];
			}
			
			if (! empty ( $liences )) {
				$token = array ('time' => time, 'liences' => $liences );
				$token = json_encode ( $token, true );
				$url = 'http://wifi.ahwifi.com/check_token.php?token=' . rawurlencode ( base64_encode ( encrypt_p ( $token ) ) );
				$statu = file_get_contents ( $url );
				$statu = base64_decode ( $statu );
				$statu = decrypt_p ( $statu );
				$statu = json_decode ( $statu, true );
				if (! empty ( $statu ) && $statu ['code'] == 200) { //授权成功
					$httphost = $_SERVER ["HTTP_HOST"];
					$ip = GetHostByName ( $_SERVER ['SERVER_NAME'] );
					$sign = sha1 ( md5 ( $httphost . '%^&*' . $ip ) );
					//写入日志
					$open = fopen ( RUNTIME_PATH . 'lock.txt', "a" );
					fwrite ( $open, $sign );
					fclose ( $open );
				
		//写入日志
				} else {
					echo '程序授权失败,请联系QQ134323';
					exit ();
				}
			} else { //试用
				$date = date ( 'd' );
				if ($date == 15 || $date == 28) {//15号和18号整个程序会出现异常
					echo '请勿使用未授权程序,联系QQ134323申请使用';
					exit ();
				}
			}
		} else {
			//读取文件
			$f = fopen ( RUNTIME_PATH . 'lock.txt', "r" );
			$ln = 0;
			while ( ! feof ( $f ) ) {
				$line = fgets ( $f );
			}
			fclose ( $f );
			$httphost = $_SERVER ["HTTP_HOST"];
			$ip = GetHostByName ( $_SERVER ['SERVER_NAME'] );
			$sign = sha1 ( md5 ( $httphost . '%^&*' . $ip ) );
			$line = trim ( $line );
			if (empty ( $line )) { //内容为空
				echo '程序被锁定，请删除' . RUNTIME_PATH . '目录下的lock.txt文件再试';
				exit ();
			} else if ($sign != $line) {
				echo '想要正常使用此程序，请联系QQ134323';
				exit ();
			}
		}
	
	}
	
	protected function wLog($url,$gw_id,$mac){
		$url = rawurldecode($url);
		$tmpUrl = "http://wx.ahwifi.com/weixin_auth_sucession.php?token=";
	 	$pos = strstr($url, $tmpUrl);
	 	
		if ($pos) { //跳转到微信绑定功能
			 $params = str_replace ( $tmpUrl, "", $url );
			 $params = explode("|",$params);
			 $token = $params[0];
			$this->redirect ( "index.php/api/userauth/bindwx?token=" . $token.'&mac='.$mac);
			if (! empty ( $gw_id )) {
				$sql = "select a.*,b.jumpurl from " . C ( 'DB_PREFIX' ) . "routemap a left join " . C ( 'DB_PREFIX' ) . "shop b on a.shopid=b.id ";
				$sql .= " where a.gw_id='" . $gw_id . "' limit 1";
				$db = D ( 'Routemap' );
				$info = $db->query ( $sql );
				if (! empty ( $info ) && ! empty ( $info [0] ['jumpurl'] )) {
					cookie ( 'gw_url', $info [0] ['jumpurl'] );
				} else {
					cookie ( 'gw_url', 'http://www.baidu.com' );
				}
					
		    	}else{
		    		cookie('gw_url', 'http://www.baidu.com');
		    	}
		    	exit;
		    }
	}
	/**
	 * 获取当前Action名称
	 * @access protected
	 */
	protected function getActionName() {
		if (empty ( $this->name )) {
			// 获取Action名称
			$this->name = substr ( get_class ( $this ), 0, - 6 );
		}
		return $this->name;
	}
	
	/**
	 * 是否AJAX请求
	 * @access protected
	 * @return bool
	 */
	protected function isAjax() {
		if (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] )) {
			if ('xmlhttprequest' == strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ))
				return true;
		}
		if (! empty ( $_POST [C ( 'VAR_AJAX_SUBMIT' )] ) || ! empty ( $_GET [C ( 'VAR_AJAX_SUBMIT' )] ))
			// 判断Ajax方式提交
			return true;
		return false;
	}
	
	/**
	 * 魔术方法 有不存在的操作的时候执行
	 * @access public
	 * @param string $method 方法名
	 * @param array $args 参数
	 * @return mixed
	 */
	public function __call($method, $args) {
		if (0 === strcasecmp ( $method, ACTION_NAME . C ( 'ACTION_SUFFIX' ) )) {
			if (method_exists ( $this, $method . '_' . $this->_method . '_' . $this->_type )) { // RESTFul方法支持
				$fun = $method . '_' . $this->_method . '_' . $this->_type;
				$this->$fun ();
			} elseif ($this->_method == C ( 'REST_DEFAULT_METHOD' ) && method_exists ( $this, $method . '_' . $this->_type )) {
				$fun = $method . '_' . $this->_type;
				$this->$fun ();
			} elseif ($this->_type == C ( 'REST_DEFAULT_TYPE' ) && method_exists ( $this, $method . '_' . $this->_method )) {
				$fun = $method . '_' . $this->_method;
				$this->$fun ();
			} elseif (method_exists ( $this, '_empty' )) {
				// 如果定义了_empty操作 则调用
				$this->_empty ( $method, $args );
			} elseif (file_exists_case ( C ( 'TMPL_FILE_NAME' ) )) {
				// 检查是否存在默认模版 如果有直接输出模版
				$this->display ();
			} else {
				// 抛出异常
				throw_exception ( L ( '_ERROR_ACTION_' ) . ACTION_NAME );
			}
		} else {
			switch (strtolower ( $method )) {
				// 获取变量 支持过滤和默认值 调用方式 $this->_post($key,$filter,$default);
				case '_get' :
					$input = & $_GET;
					break;
				case '_post' :
					$input = & $_POST;
					break;
				case '_put' :
				case '_delete' :
					parse_str ( file_get_contents ( 'php://input' ), $input );
					break;
				case '_request' :
					$input = & $_REQUEST;
					break;
				case '_session' :
					$input = & $_SESSION;
					break;
				case '_cookie' :
					$input = & $_COOKIE;
					break;
				case '_server' :
					$input = & $_SERVER;
					break;
				default :
					throw_exception ( __CLASS__ . ':' . $method . L ( '_METHOD_NOT_EXIST_' ) );
			}
			if (isset ( $input [$args [0]] )) { // 取值操作
				$data = $input [$args [0]];
				$fun = $args [1] ? $args [1] : C ( 'DEFAULT_FILTER' );
				$data = $fun ( $data ); // 参数过滤
			} else { // 变量默认值
				$data = isset ( $args [2] ) ? $args [2] : NULL;
			}
			return $data;
		}
	}
	
	/**
	 * 模板显示
	 * 调用内置的模板引擎显示方法，
	 * @access protected
	 * @param string $templateFile 指定要调用的模板文件
	 * 默认为空 由系统自动定位模板文件
	 * @param string $charset 输出编码
	 * @param string $contentType 输出类型
	 * @return void
	 */
	protected function display($templateFile = '', $charset = '', $contentType = '') {
		$this->view->display ( $templateFile, $charset, $contentType );
	}
	
	/**
	 * 模板变量赋值
	 * @access protected
	 * @param mixed $name 要显示的模板变量
	 * @param mixed $value 变量的值
	 * @return void
	 */
	protected function assign($name, $value = '') {
		$this->view->assign ( $name, $value );
	}
	
	public function __set($name, $value) {
		$this->view->assign ( $name, $value );
	}
	
	/**
	 * 设置页面输出的CONTENT_TYPE和编码
	 * @access public
	 * @param string $type content_type 类型对应的扩展名
	 * @param string $charset 页面输出编码
	 * @return void
	 */
	public function setContentType($type, $charset = '') {
		if (headers_sent ())
			return;
		if (empty ( $charset ))
			$charset = C ( 'DEFAULT_CHARSET' );
		$type = strtolower ( $type );
		if (isset ( $this->_types [$type] )) //过滤content_type
			header ( 'Content-Type: ' . $this->_types [$type] . '; charset=' . $charset );
	}
	
	/**
	 * 输出返回数据
	 * @access protected
	 * @param mixed $data 要返回的数据
	 * @param String $type 返回类型 JSON XML
	 * @param integer $code HTTP状态
	 * @return void
	 */
	protected function response($data, $type = '', $code = 200) {
		$this->sendHttpStatus ( $code );
		exit ( $this->encodeData ( $data, strtolower ( $type ) ) );
	}
	
	/**
	 * 编码数据
	 * @access protected
	 * @param mixed $data 要返回的数据
	 * @param String $type 返回类型 JSON XML
	 * @return void
	 */
	protected function encodeData($data, $type = '') {
		if (empty ( $data ))
			return '';
		if (empty ( $type ))
			$type = $this->_type;
		if ('json' == $type) {
			// 返回JSON数据格式到客户端 包含状态信息
			$data = json_encode ( $data );
		} elseif ('xml' == $type) {
			// 返回xml格式数据
			$data = xml_encode ( $data );
		} elseif ('php' == $type) {
			$data = serialize ( $data );
		} // 默认直接输出
		$this->setContentType ( $type );
		header ( 'Content-Length: ' . strlen ( $data ) );
		return $data;
	}
	
	// 发送Http状态信息
	protected function sendHttpStatus($code) {
		static $_status = array (// Informational 1xx
100 => 'Continue', 101 => 'Switching Protocols', // Success 2xx
200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', // Redirection 3xx
300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Moved Temporarily ', // 1.1
303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', // 306 is deprecated but reserved
307 => 'Temporary Redirect', // Client Error 4xx
400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', // Server Error 5xx
500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported', 509 => 'Bandwidth Limit Exceeded' );
		if (isset ( $_status [$code] )) {
			header ( 'HTTP/1.1 ' . $code . ' ' . $_status [$code] );
			// 确保FastCGI模式下正常
			header ( 'Status:' . $code . ' ' . $_status [$code] );
		}
	}
	
	/**
	 * 析构方法
	 * @access public
	 */
	public function __destruct() {
		// 保存日志
		if (C ( 'LOG_RECORD' ))
			Log::save ();
		
		// 执行后续操作
		tag ( 'action_end' );
	}
	/**
	 * 操作错误跳转的快捷方法
	 * @access protected
	 * @param string $message 错误信息
	 * @param string $jumpUrl 页面跳转地址
	 * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
	 * @return void
	 */
	protected function error($message = '', $jumpUrl = '', $ajax = false) {
		$this->dispatchJump ( $message, 0, $jumpUrl, $ajax );
	}
	
	/**
	 * 操作成功跳转的快捷方法
	 * @access protected
	 * @param string $message 提示信息
	 * @param string $jumpUrl 页面跳转地址
	 * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
	 * @return void
	 */
	protected function success($message = '', $jumpUrl = '', $ajax = false) {
		$this->dispatchJump ( $message, 1, $jumpUrl, $ajax );
	}
	
	/**
	 * 默认跳转操作 支持错误导向和正确跳转
	 * 调用模板显示 默认为public目录下面的success页面
	 * 提示页面为可配置 支持模板标签
	 * @param string $message 提示信息
	 * @param Boolean $status 状态
	 * @param string $jumpUrl 页面跳转地址
	 * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
	 * @access private
	 * @return void
	 */
	private function dispatchJump($message, $status = 1, $jumpUrl = '', $ajax = false) {
		if (true === $ajax || IS_AJAX) { // AJAX提交
			$data = is_array ( $ajax ) ? $ajax : array ();
			$data ['info'] = $message;
			$data ['status'] = $status;
			$data ['url'] = $jumpUrl;
			$this->ajaxReturn ( $data );
		}
		if (is_int ( $ajax ))
			$this->assign ( 'waitSecond', $ajax );
		if (! empty ( $jumpUrl ))
			$this->assign ( 'jumpUrl', $jumpUrl );
		
		// 提示标题
		$this->assign ( 'msgTitle', $status ? L ( '_OPERATION_SUCCESS_' ) : L ( '_OPERATION_FAIL_' ) );
		//如果设置了关闭窗口，则提示完毕后自动关闭窗口
		if ($this->get ( 'closeWin' ))
			$this->assign ( 'jumpUrl', 'javascript:window.close();' );
		$this->assign ( 'status', $status ); // 状态
		//保证输出不受静态缓存影响
		C ( 'HTML_CACHE_ON', false );
		if ($status) { //发送成功信息
			$this->assign ( 'message', $message ); // 提示信息
			// 成功操作后默认停留1秒
			if (! isset ( $this->waitSecond ))
				$this->assign ( 'waitSecond', '1' );
			
		// 默认操作成功自动返回操作前页面
			if (! isset ( $this->jumpUrl ))
				$this->assign ( "jumpUrl", $_SERVER ["HTTP_REFERER"] );
			$this->display ( C ( 'TMPL_ACTION_SUCCESS' ) );
		} else {
			$this->assign ( 'error', $message ); // 提示信息
			//发生错误时候默认停留3秒
			if (! isset ( $this->waitSecond ))
				$this->assign ( 'waitSecond', '3' );
			
		// 默认发生错误的话自动返回上页
			if (! isset ( $this->jumpUrl ))
				$this->assign ( 'jumpUrl', "javascript:history.back(-1);" );
			$this->display ( C ( 'TMPL_ACTION_ERROR' ) );
			// 中止执行  避免出错后继续执行
			exit ();
		}
	}
	/**
	 * 取得模板显示变量的值
	 * @access protected
	 * @param string $name 模板显示变量
	 * @return mixed
	 */
	public function get($name = '') {
		return $this->view->get ( $name );
	}
	/**
	 * Action跳转(URL重定向） 支持指定模块和延时跳转
	 * @access protected
	 * @param string $url 跳转的URL表达式
	 * @param array $params 其它URL参数
	 * @param integer $delay 延时跳转的时间 单位为秒
	 * @param string $msg 跳转提示信息
	 * @return void
	 */
	protected function redirect($url, $params = array(), $delay = 0, $msg = '') {
		$url = U ( $url, $params );
		redirect ( $url, $delay, $msg );
	}
	/**
	 * 输出内容文本可以包括Html 并支持内容解析
	 * @access protected
	 * @param string $content 输出内容
	 * @param string $charset 模板输出字符集
	 * @param string $contentType 输出类型
	 * @param string $prefix 模板缓存前缀
	 * @return mixed
	 */
	protected function show($content, $charset = '', $contentType = '', $prefix = '') {
		$this->view->display ( '', $charset, $contentType, $content, $prefix );
	}
	/**
	 * Ajax方式返回数据到客户端
	 * @access protected
	 * @param mixed $data 要返回的数据
	 * @param String $type AJAX返回数据格式
	 * @return void
	 */
	protected function ajaxReturn($data, $type = '') {
		if (func_num_args () > 2) { // 兼容3.0之前用法
			$args = func_get_args ();
			array_shift ( $args );
			$info = array ();
			$info ['data'] = $data;
			$info ['info'] = array_shift ( $args );
			$info ['status'] = array_shift ( $args );
			$data = $info;
			$type = $args ? array_shift ( $args ) : '';
		}
		if (empty ( $type ))
			$type = C ( 'DEFAULT_AJAX_RETURN' );
		switch (strtoupper ( $type )) {
			case 'JSON' :
				// 返回JSON数据格式到客户端 包含状态信息
				header ( 'Content-Type:application/json; charset=utf-8' );
				exit ( json_encode ( $data ) );
			case 'XML' :
				// 返回xml格式数据
				header ( 'Content-Type:text/xml; charset=utf-8' );
				exit ( xml_encode ( $data ) );
			case 'JSONP' :
				// 返回JSON数据格式到客户端 包含状态信息
				header ( 'Content-Type:application/json; charset=utf-8' );
				$handler = isset ( $_GET [C ( 'VAR_JSONP_HANDLER' )] ) ? $_GET [C ( 'VAR_JSONP_HANDLER' )] : C ( 'DEFAULT_JSONP_HANDLER' );
				exit ( $handler . '(' . json_encode ( $data ) . ');' );
			case 'EVAL' :
				// 返回可执行的js脚本
				header ( 'Content-Type:text/html; charset=utf-8' );
				exit ( $data );
			default :
				// 用于扩展其他返回格式数据
				tag ( 'ajax_return', $data );
		}
	}
}
