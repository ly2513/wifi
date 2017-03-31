<?php
class TestAction extends Action{
/**
	 * 解密密码 tanxy
	 */
	public function decrypt_password($encrypted=null,$key = null){
		if($key == null){
			$key = '!@#$%^&*';
		}
		$result=$this->decrypt('9sQSPAzAteIJRi3iWHbMx3a8+/ayp49cSMsNgzj0VTg=',$key);
		echo  $result;
	}
	/**
	 * 加密密码 tanxy
	 * Enter description here ...
	 * @param  $encrypted
	 */
	public function encrypt_password(){
		$key = '!@#$%^&*';
		$result=$this->encrypt('http://10.1.15.234?token=',$key);
		echo $result;
		exit;
	}
	/**
	 * 加密 tanxy
	 * @param $input
	 * @param $key
	 */
	private function encrypt($input,$key) {
		$size = mcrypt_get_block_size('des', 'ecb');
		$input = $this->pkcs5_pad($input, $size);
		$td = mcrypt_module_open('des', '', 'ecb', '');
		$iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		@mcrypt_generic_init($td, $key, $iv);
		$data = mcrypt_generic($td, $input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$data = base64_encode($data);
		return $data;
	}
	/**
	 * 解密 tanxy
	 *
	 *  @param $encrypted
	 * @param $key
	 */
	private function decrypt($encrypted,$key) {

		$encrypted = base64_decode($encrypted);
		$td = mcrypt_module_open('des','','ecb','');
		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);
		@mcrypt_generic_init($td, $key, $iv);
		$decrypted = mdecrypt_generic($td, $encrypted);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$y=$this->pkcs5_unpad($decrypted);
		return $y;
	}
	private function pkcs5_pad ($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}
	/**
	 * tanxy
	 * Enter description here ...
	 * @param unknown_type $text
	 */
	private function pkcs5_unpad($text) {
		$pad = ord($text{strlen($text)-1});
		if ($pad > strlen($text))
		return false;
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
		return false;
		return substr($text, 0, -1 * $pad);
	}
	
}
