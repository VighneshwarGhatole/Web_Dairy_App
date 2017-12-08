<?php
class AESWith16Pad {
	function pad($data, $blocksize = 16) {
		$pad = $blocksize - (strlen ( $data ) % $blocksize);
		return $data . str_repeat ( chr ( $pad ), $pad );
	}
	public function decryptECB($data, $key) {
		return mcrypt_decrypt ( MCRYPT_RIJNDAEL_128, $key, base64_decode ( $data ), MCRYPT_MODE_ECB );
	}
	public function encryptECB($data, $key) {
		return base64_encode ( mcrypt_encrypt ( MCRYPT_RIJNDAEL_128, $key, $this->pad ( $data ), MCRYPT_MODE_ECB ) );
	}
}
