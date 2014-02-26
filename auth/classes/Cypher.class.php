<?php
/**
 *Class to handle encryption and decryption
 *
 */

class Cypher {
    
    private $mode;
    private $cypherKey;
    private $cypherMethod;

    public function __construct($mode) {
        $this->setMode($mode);
        $this->setCypherMethod();
        $this->setCypherKey();
    }
    
    public static function getEncryptedString($string, $mode) {
        $cypher = new Cypher($mode);
        return $cypher->encrypt($string);
    }
    
    private function setCypherKey() {
        switch ($this->mode) {
            case 'cas':
                $this->cypherKey = '123920-928340982309283';
                break;
            case 'mrs':
                $this->cypherKey = '12398273942872039872';
                break;
            default:
                throw new Exception(
                    'Unknown cypher mode: "' . $this->get('mode') . '"'
                );
        }
        return $this;
    }
    
    private function setMode($mode) {
        $this->mode = $mode;
    }
    private function setCypherMethod() {
        $this->cypherMethod = 'aes128';
        return $this;
    }
    
    private function get($key) {
        return $this->$key;
    }
    
    public function encrypt($string) {
        return openssl_encrypt(
            $string, $this->get('cypherMethod'), $this->get('cypherKey'), 0, 1234567891234567
        );
    }
    
}
