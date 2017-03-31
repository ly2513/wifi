<?php
class BaseAction extends Action{
	public $p = '';//正式环境中这里要改成''
	protected function _initialize()
	{
		//读取模板主题路径
		$theme_path = $this->_getThemePath();
		$public = array('css'=>$this->p.'/UI/Public/css','js'=>$this->p.'/UI/Public/js','img'=>$this->p.'/UI/Public/images/','root'=>$this->p.'/UI/Public');
		$theme =	array('css'=>$theme_path.'/style/css','js'=>$theme_path.'/style/js','img'=>$theme_path.'/style/images','root'=>$theme_path.'/');
		$style = array('P' =>$public,'T' =>$theme);
		$Style =  $style;
		$this->assign('Theme',$Style);
		$this->assign('action', $this->getActionName());
		
	}
	
	private function _getThemePath()
	{
		$theme = C('DEFAULT_THEME');
		$group  = defined('GROUP_NAME')?GROUP_NAME.'/':'';
		if(1==C('APP_GROUP_MODE')){ // 独立分组模式
			return $theme_path = '/'.dirname(BASE_LIB_PATH).'/'.$group.basename(TMPL_PATH).'/'.$theme;
		}else{
			return $theme_path = '/'.basename(TMPL_PATH).'/'.$group.$theme;
		}
	}
	protected function uploadFile($uid, $file_name, $tmp_file) {
		if (is_null ( $_FILES ['img'] ['name'] ) || $_FILES ['img'] ['name'] == "") {
			return array (null, "没有选择图片,上传失败");
		}
		$storeType = C ( 'STORE_TYPE' );
		if ($storeType == 1) {
			import ( 'ORG.Net.UploadFile' );
			$upload = new UploadFile ();
			$upload->maxSize = C ( 'AD_SIZE' );
			$upload->allowExts = C ( 'AD_IMGEXT' );
			$upload->savePath = C ( 'AD_SAVE' );
			if (! $upload->upload ()) {
				return array (null, $upload->getErrorMsg () );
			} else {
				$info = $upload->getUploadFileInfo ();
				$savename = $info [0] ['savename'];
				$key = trim ( $info [0] ['savepath'], '.' ) . $savename;
				return array (array ('key' => $key ), null );
			}
		} else if ($storeType == 2) {
			import ( "qiniu.io", dirname ( __FILE__ ),'.php' );
			import ( "qiniu.rs", dirname ( __FILE__ ),'.php' );
			$key1 = md5 ( $uid . time () ) . $file_name;
			$qiniu = C ( 'QINIU' );
			$accessKey = $qiniu ['accessKey'];
			$secretKey = $qiniu ['secretKey'];
			$bucket = $qiniu ['bucket'];
			
			Qiniu_SetKeys ( $accessKey, $secretKey );
			$putPolicy = new Qiniu_RS_PutPolicy ( $bucket );
			$upToken = $putPolicy->Token ( null );
			
			$putExtra = new Qiniu_PutExtra ();
			$putExtra->Crc32 = 1;
			$rs = Qiniu_PutFile ( $upToken, $key1, $tmp_file, $putExtra );
			return $rs;
		} else { //百度
			import ( "Baidu.bcs", dirname ( __FILE__ ) );
			
			$bsc = C ( 'BSC' );
			$key1 = md5 ( $uid . time () ) . $file_name;
			
			$accessKey = $bsc ['accessKey'];
			$secretKey = $bsc ['secretKey'];
			$bucket = $bsc ['bucket'];
			$host = $bsc ['host'];
			$baidu_bcs = new BaiduBCS ( $accessKey, $secretKey, $host );
			$response = $baidu_bcs->get_bucket_acl ( $bucket );
			if ($response->status == '403') {
				$acl = BaiduBCS::BCS_SDK_ACL_TYPE_PRIVATE;
				$response = $baidu_bcs->create_bucket ( $bucket, $acl );
			}
			
			if ($response->status != '200') {
				return array (null, '创建bucket失败' );
			}
			$opt = array ();
			$opt ['acl'] = BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_WRITE;
			$opt [BaiduBCS::IMPORT_BCS_LOG_METHOD] = "bs_log";
			$opt ['curlopts'] = array (CURLOPT_CONNECTTIMEOUT => 10, CURLOPT_TIMEOUT => 1800 );
			//print_r($tmp_file);exit;
			$response = $baidu_bcs->create_object ( $bucket, '/' . $key1, $tmp_file, $opt );
			if ($response->status == '200') {
				return array (array ('key' => $key1 ), null );
			} else {
				return array (null, '上传失败' );
			}
		}
	
	}
	/**
	 * 生成下载连接
	 * Enter description here ...
	 * @param unknown_type $version_id
	 */
	protected function downloadUrl($file_name) {
		$storeType = C ( 'STORE_TYPE' );
		if ($storeType == 1) {
			return $file_name;
		} else if ($storeType == 2) {
			import ( "qiniu.rs", dirname ( __FILE__ ),'.php' );
			$qiniu = C ( 'QINIU' );
			$accessKey = $qiniu ['accessKey'];
			$secretKey = $qiniu ['secretKey'];
			$domain = $qiniu ['domain'];
			
			Qiniu_SetKeys ( $accessKey, $secretKey );
			
			$baseUrl = Qiniu_RS_MakeBaseUrl ( $domain, $file_name );
			$getPolicy = new Qiniu_RS_GetPolicy ();
			$privateUrl = $getPolicy->MakeRequest ( $baseUrl, null );
			return $privateUrl;
		} else {
			
			return 'http://' . $_SERVER ['SERVER_NAME'] . $this->p.'/index.php/download/img?img_name='.$file_name;
			
		}
	}
	protected function delete($key1){
		$storeType = C ( 'STORE_TYPE' );
		if ($storeType == 1) {
			//删除本地
			if (file_exists($key1)) {
				$result=unlink ($key1);
				return $result;
			}
			return false;
		} else if ($storeType == 2) {
			import ( "qiniu.rs", dirname ( __FILE__ ),'.php' );
			$qiniu = C ( 'QINIU' );
			$accessKey = $qiniu ['accessKey'];
			$secretKey = $qiniu ['secretKey'];
			$bucket = $qiniu ['bucket'];

			Qiniu_SetKeys($accessKey, $secretKey);
			$client = new Qiniu_MacHttpClient(null);

			$err = Qiniu_RS_Delete($client, $bucket, $key1);
			if ($err !== null) {
				return false;
			} else {
				echo true;
			}
	}else{
		//TODO 删除百度
	}
}
}