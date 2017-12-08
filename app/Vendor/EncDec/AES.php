<?php
class AES {
	
    protected $_key;
    protected $_iv;
    protected $_blockSize;
    protected $_encrypt;
    protected $_cipher;

    function __construct ( $key, $iv, $encrypt = true ){
        $this->_key = $key;
        $this->_iv = $iv;
        $this->_encrypt = $encrypt;
        $this->_blockSize = mcrypt_get_block_size( MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB );
        $this->_cipher = mcrypt_module_open( MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '' );
        mcrypt_generic_init( $this->_cipher, $this->_key, $this->_iv );
    }

    function __destruct (){
        mcrypt_generic_deinit( $this->_cipher );
        mcrypt_module_close( $this->_cipher );
    }

    public function transformBlock ( $text ){
        if ( $this->_encrypt )
            return mcrypt_generic( $this->_cipher, $text );
        else
            return mdecrypt_generic( $this->_cipher, $text );
    }

}

class EncryptDecrypt {
	
    private $keyString;
    private $ivString;

    function __construct ( $keyStr ){
        $this->keyString = $keyStr;//$this->convert($keyStr);
        $this->ivString = str_repeat( "\0", strlen( $keyStr ) );
    }

    function __destruct (){
        unset( $this->keyString );
    }

    private function convert($h) {
        if (!is_string($h))
            return null;
        $r = '';
        for ($a = 0; $a < strlen($h); $a+=2) {
          $r.=chr(hexdec($h{$a} . $h{($a + 1)}));
        }
        return $r;
    }
    
    public function encryptJson ( $data ){
        $encryptor = new AES( $this->keyString, $this->ivString );
        return strtoupper( bin2hex( $encryptor->transformBlock( $data ) ) );
    }
    
    public function decryptJson ( $data ){
        $decryptor = new AES( $this->keyString, $this->ivString, false );
        return $decryptor->transformBlock( hex2bin( $data ) );
    }
}