<?php
	/*
	 * 设置全局过滤函数
	 */
	function filterAll(&$value){
		$value = htmlspecialchars($value);
	}
	
	function update_config($new_config, $config_file = '') {
	
		!is_file($config_file) && $config_file = CONF_PATH . 'temp.php';
	
		if (is_writable($config_file)) {
	
			$config = require $config_file;
	
			$config = array_merge($config, $new_config);
	
			file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
	
			@unlink(RUNTIME_FILE);
	
			return true;
	
		} else {
	
			return false;
	
		}
	
	}
	function isNumber($val)
    {
            if(ereg("^[0-9]+$", $val))
                return true;
            return false;
     }
	function validate_user($val){
		if(ereg("^[a-zA-Z0-9]{4,20}$", $val))
			return true;
		return false;
	}
	function validate_pwd($val){
		if(ereg("^[a-zA-Z0-9_]{4,20}$", $val))
			return true;
		return false;
	}

	function validate_weixin_token($val){
		if(ereg("^[a-zA-Z0-9_]{3,20}$", $val))
			return true;
		return false;
	}
	/*
	 * 手机号码
	 */
	function isPhone($val){
			
		  if (ereg("^1[1-9][0-9]{9}$",$val))
		  return true;
		return false;
	
          
	}
	/*
	 * 手机号码
	 */
	function isQQ($val){
			
		  if (ereg("^[1-9][0-9]{4,12}$",$val))
		  return true;
		return false;
	
          
	}
	function isSmsCode($val){
			
		  if (ereg("^[0-9]{6}$",$val))
		  return true;
		return false;
	
          
	}
	function inject_check($sql_str) { 
		return eregi ( 'select|inert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|UNION|into|load_file|outfile', $sql_str ); 

	} 
	
	
	function isUrl($val){
		 $val = trim($val);
		 $tmpUrl1 = substr($val,0,7);
		 if($tmpUrl1 == 'http://'){
			return true;
		 }
		 $tmpUrl2 = substr($val,0,8);
		 if($tmpUrl12 == 'https://'){
			return true;
		 }
		 return false;
		 
	}
	/*
	function isUrl($s)  {  
		return preg_match('/^http[s]?:\/\/'.  
		    '(([0-9]{1,3}\.){3}[0-9]{1,3}'. // IP形式的URL- 199.194.52.184  
		    '|'. // 允许IP和DOMAIN（域名）  
		    '([0-9a-z_!~*\'()-]+\.)*'. // 域名- www.  
		    '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.'. // 二级域名  
		    '[a-z]{2,6})'.  // first level domain- .com or .museum  
		    '(:[0-9]{1,4})?'.  // 端口- :80  
		    '((\/\?)|'.  // a slash isn't required if there is no file name  
		    '(\/[0-9a-zA-Z_!~\'\(\)\[\]\.;\?:@&=\+\$,%#-\/^\*\|]*)?)$/',  
		    $s) == 1;  
	}  
	*/
	/*
	 * 
	 */
	function getAdPos($pos){
		switch ($pos){
			case 0:
				return  '首页';
				break;
			case 1:
				return  '认证页面';
				break;
			default:
				return  '认证页面';
				break;
				
		}
	}
	
	function getStatus($id){
		switch ($id){
			case 0:
				return  '停用';
				break;
			case 1:
				return  '正常';
				break;
			default:
				return  '正常';
				break;
				
		}
	}
	function getAdMode($mode){
		switch ($mode){
			case 0:
				return  '图片广告';
				break;
			case 1:
				return  '图文广告';
				break;
			default:
				return  '图片广告';
				break;
				
		}
	}
	function getPaymodel($id){
		switch ($id){
			case 0:
				return  '扣款';
				break;
			case 1:
				return  '充值';
				break;
			
				
		}
	}
	function getAuthWay($pos){
		switch ($pos){
			case 0:
				return  '注册认证';
				break;
			case 1:
				return  '手机认证';
				break;
			case 2:
				return  '免认证';
				break;
			default:
					return  '注册认证';
				break;
				
		}
	}
	
	function getAgent(){
		 $agent = $_SERVER['HTTP_USER_AGENT'];
		 return $agent;
	}
	
	/*
	 * 获取系统信息
	 */
	function getOS ()
	{
	  
	    $agent = $_SERVER['HTTP_USER_AGENT'];
	    $os = false;
	    if (eregi('win', $agent) && strpos($agent, '95')){
	        $os = 'Windows 95';
	    }elseif (eregi('win 9x', $agent) && strpos($agent, '4.90')){
	        $os = 'Windows ME';
	    }elseif (eregi('win', $agent) && ereg('98', $agent)){
	        $os = 'Windows 98';
	    }elseif (eregi('win', $agent) && eregi('nt 5.1', $agent)){
	        $os = 'Windows XP';
	    }elseif (eregi('win', $agent) && eregi('nt 5.2', $agent)){    
	        $os = 'Windows 2003';
	    }elseif (eregi('win', $agent) && eregi('nt 5', $agent)){
	        $os = 'Windows 2000';
	    }elseif (eregi('win', $agent) && eregi('nt', $agent)){
	        $os = 'Windows NT';
	    }elseif (eregi('win', $agent) && ereg('32', $agent)){
	        $os = 'Windows 32';
	    }elseif (eregi('linux', $agent)){
	        $os = 'Linux';
	    }elseif (eregi('unix', $agent)){
	        $os = 'Unix';
	    }elseif (eregi('sun', $agent) && eregi('os', $agent)){
	        $os = 'SunOS';
	    }elseif (eregi('ibm', $agent) && eregi('os', $agent)){
	        $os = 'IBM OS/2';
	    }elseif (eregi('Mac', $agent) && eregi('PC', $agent)){
	        $os = 'Macintosh';
	    }elseif (eregi('PowerPC', $agent)){
	        $os = 'PowerPC';
	    }elseif (eregi('AIX', $agent)){
	        $os = 'AIX';
	    }elseif (eregi('HPUX', $agent)){
	        $os = 'HPUX';
	    }elseif (eregi('NetBSD', $agent)){
	        $os = 'NetBSD';
	    }elseif (eregi('BSD', $agent)){
	        $os = 'BSD';
	    }elseif (ereg('OSF1', $agent)){
	        $os = 'OSF1';
	    }elseif (ereg('IRIX', $agent)){
	        $os = 'IRIX';
	    }elseif (eregi('FreeBSD', $agent)){
	        $os = 'FreeBSD';
	    }elseif (eregi('teleport', $agent)){
	        $os = 'teleport';
	    }elseif (eregi('flashget', $agent)){
	        $os = 'flashget';
	    }elseif (eregi('webzip', $agent)){
	        $os = 'webzip';
	    }elseif (eregi('offline', $agent)){
	        $os = 'offline';
	    }else{
	        $os = 'Unknown';
	    }
	    return $os;
	}
	
	/*
	 * 获取浏览器信息
	 */
	function getUserBrowser(){
	    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Maxthon')) {
	        $browser = 'Maxthon';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 12.0')) {
	        $browser = 'IE12.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 11.0')) {
	        $browser = 'IE11.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) {
	        $browser = 'IE10.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {
	        $browser = 'IE9.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {
	        $browser = 'IE8.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
	        $browser = 'IE7.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {
	        $browser = 'IE6.0';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'NetCaptor')) {
	        $browser = 'NetCaptor';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')) {
	        $browser = 'Netscape';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Lynx')) {
	        $browser = 'Lynx';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
	        $browser = 'Opera';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
	        $browser = 'Chrome';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
	        $browser = 'Firefox';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
	        $browser = 'Safari';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'iphone') || strpos($_SERVER['HTTP_USER_AGENT'], 'ipod')) {
	        $browser = 'iphone';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'ipad')) {
	        $browser = 'iphone';
	    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'android')) {
	        $browser = 'android';
	    } else {
	        $browser = 'other';
	    }
    	return $browser;
	}
	
	function showauthcheck($val,$data){
		if(strpos($data,"#".$val."#")>-1){
			echo 'checked';
		}else{
			if(strpos($data,"#".$val."=")>-1){
				echo  'checked';
			}
		}
	}
	
	function echojsonkey($val,$key){
		$json=json_decode($val);
		switch($key){
			case "pwd":
				return $json->pwd;
				break;
			case "user":
				return $json->user;
				break;
		}
		
	}
	
	function showauthdata($val,$data){
		$tmp=explode('#', $data);
		foreach($tmp as $v){
			if($v!='#'&&$v!=''){
				$arr[]=$v;
			}
		}
		foreach($arr as $v){
			$temp=explode('=', $v);
			if(count($temp)>1&&$temp[0]==$val){
				//$dt=json_decode($temp[1]);
				return $temp[1];
				break;
			}
		}
		
	}
	
	
	
	
	/**
	    * 导出数据为excel表格
	    *@param $data    一个二维数组,结构如同从数据库查出来的数组
	    *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
	    *@param $filename 下载的文件名
	    *@examlpe 
	    $stu = M ('User');
	    $arr = $stu -> select();
	    exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
	*/
	 function exportexcel($data=array(),$title=array(),$filename='report'){
	    header("Content-type:application/octet-stream");
	    header("Accept-Ranges:bytes");
	    header("Content-type:application/vnd.ms-excel");  
	    header("Content-Disposition:attachment;filename=".$filename.".xls");
	    header("Pragma: no-cache");
	    header("Expires: 0");
	    //导出xls 开始
	    if (!empty($title)){
	        foreach ($title as $k => $v) {
	            $title[$k]=iconv("UTF-8", "GB2312",$v);
	            
	        }
	        $title= implode("\t", $title);
	        echo "$title\n";
	    }
	    if (!empty($data)){
	        foreach($data as $key=>$val){
	            foreach ($val as $ck => $cv) {
	                $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
	            }
	            $data[$key]=implode("\t", $data[$key]);
	            
	        }
	        echo implode("\n",$data);
	    }
	 }
	
	 /*输出execl，配置标题和匹配的字段
	  * 
	  */
	  function exportexcelByKey($data=array(),$title=array(),$filename='report'){
	    
	  	header("Content-type:application/octet-stream");
	    header("Accept-Ranges:bytes");
	    header("Content-type:application/vnd.ms-excel");  
	    header("Content-Disposition:attachment;filename=".$filename.".xls");
	    header("Pragma: no-cache");
	    
	    header("Expires: 0");
	    
	    //导出xls 开始
	    if (!empty($title)){
	        foreach ($title as $k) {
	            //$excelhead[]=iconv("UTF-8", "GB2312",$k[0]);
	            $excelhead[]=iconv("UTF-8", "GB2312",$k[0]);
	            $field[]=$k[1];
	        }
	        $excelhead= implode("\t", $excelhead);
	        
	        echo "$excelhead\n";
	    }
	    
	    if (!empty($data)){
	        foreach($data as $obj){
	        	
	            
	            	$Line=null;
	            	
	            	foreach ($field as $fv){
	            		
	            		$Line[]=iconv("UTF-8", "GB2312", $obj[$fv]);
	            		//$Line[]=$data[$key][$fv];
	            	}
	            	$Line= implode("\t", $Line);
	            	echo "$Line\n";
	            	 
	               
	            
	          
	            
	        }
	      
	    }
	   
	 }
	 
	 function smsstate($pos){
		 switch ($pos){
				case 0:
					return  '等待发送';
					break;
				case 1:
					return  '已发送';
					break;
				case 2:
					return  '发送失败,等待重发';
					break;
				default:
						return  '等待发送';
					break;
					
			}
	 }
	 
	 function getCatelog($id){
		switch ($id){
			case 0:
				return  '图文';
				break;
			case 1:
				return  '链接';
				break;
				case 2:
				return  '电话';
				break;
				case 3:
				return  '地图导航';
				break;
			default:
				return  '图文';
				break;
				
		}
	}
	
	function getArtCate($list,$id){
		$rs="";
		foreach ($list as $k=>$v){
			if($v['id']==$id){
				$rs=$v['title'];
				break;
			}
		}
		return $rs;
	}
	 function getNewsMode($id){
		switch ($id){
			case 1:
				return  '新闻中心';
				break;
			case 2:
				return  '产品动态';
				break;

		}
	}
	function showclass_common($vo,$cid){
		switch (strtolower($vo['mode'])){
			case "0":
				$html2.=U('Api/Wap/lists',array('classid'=>$vo['id'],'sid'=>$vo['uid'],'cid'=>$cid));
				
				return $html2;
				break;
			case "1":
				$html2=$vo['titleurl'];
				
				return $html2;
				break;
			case "2":
				$html2.="tel:".$vo['tel'];
				
				return $html2;
				break;
			case "3":
				//http://api.map.baidu.com/marker?location=40.047669,116.313082&title=我的位置&content=百度奎科大厦&output=html&src=yourComponyName|yourAppName
				//http://api.map.baidu.com/marker?location=39.892963,116.313504&title=%E5%BE%AE%E7%9B%9F&name=%E5%BE%AE%E7%9B%9F&content=%E4%B8%8A%E6%B5%B7%E5%B8%82%E6%9D%A8%E6%B5%A6%E5%8C%BA%E4%BA%94%E8%A7%92%E5%9C%BA&output=html&src=weiba|weiweb
				//坐标是纬度进度
				$url="http://api.map.baidu.com/marker?location=".$vo['point_y'].",".$vo['point_x']."&title=".'福清'."&content="."福清市"."&output=html&src=weiyibai|weiyibai";
				return $url;
				
	
				break;
			
		}
	
	}
	
	function showmapurl($vo){
		//dump($vo);
		
		return "http://api.map.baidu.com/marker?location=".$vo['point_y'].",".$vo['point_x']."&title=".urlencode($vo['shopname'])."&content=".urlencode($vo['address'])."&output=html&src=weiyibai|weiyibai";
	}
	
	function showmapurlshop($vo){
		//dump($vo);
		
		return "http://api.map.baidu.com/marker?location=".$vo['point_y'].",".$vo['point_x']."&title=".urlencode($vo['shopname'])."&content=".urlencode($vo['shopaddress'])."&output=html&src=weiyibai|weiyibai";
	}
	/*
	 * 获取当前日期
	 */
	function getNowDate(){
		return  date("Y-m-d");
	}
	
	function getvsersion(){
		$data['auth']=getserdata();
		$data['ver']='5910416';
		return json_encode( $data);
	}
	
	/*
	 * 获取客户端MAc
	 */
	function getClientMac(){
		
	}
	function cutNewsInfo($info){
		$str=strip_tags($info);
		return substr($str, 0,20);
	}
	