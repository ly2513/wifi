<?php
class BscdownloadAction extends BaseAction {
	public function downloadimg() {
		
		$file_name = $_GET ['img_name'];
		require_once('bcs.class.php');
		$bsc = C ( 'BSC' );
		$accessKey = $bsc ['accessKey'];
		$secretKey = $bsc ['secretKey'];
		$bucket = $bsc ['bucket'];
		$host = $bsc ['host'];
		
		$baidu_bcs = new BaiduBCS ( $accessKey, $secretKey, $host );
	
		$opt = array ();
		$response = $baidu_bcs->get_object ( $bucket, '/' . $file_name, $opt );
		if ($response->status == '200') {
			header('content-Type:image/jpeg');
			echo $response->body;
		}
		exit ();
	}
}