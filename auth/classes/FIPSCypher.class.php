<?php
require 'classes/auth/Cypher.class.php';
/**
 *Class to handle encryption and decryption
 *
 */

class FIPSCypher extends Cypher {
    
    const FIPS_OPENSSL_PATH = '/usr/local/ssl/bin/openssl';

    public static function encryptString($string, $mode) {
        $cypher = new FIPSCypher($mode);
        return $cypher->encrypt($string);
    }
    
    public static function encryptJSONList($string, $mode) {
        if ($list = json_decode($string)) {
            //test if list is an object because php associative arrays
            //get converted to objects during json_encode/decode
            if (is_object($list)) {
                $list = (array) $list;
            }
            return json_encode(static::encryptListElements($list, $mode));
        } else {
            throw new Exception(
                'Could not parse JSON string: "' . $string .'"'
            );
        }
    }

    public static function encryptListElements($list, $mode) {
        $cypher = new FIPSCypher($mode);
        return array_map(array($cypher, 'encrypt'), $list);
    }

    public function encrypt($string) {
        //avoid encrypting an empty string:
        if (!strlen(trim($string))) {
            return $string;
        }
        exec ('export OPENSSL_FIPS=1');
        $execStr = "echo " . escapeshellarg(escapeshellcmd($string)) . " | "
            . self::FIPS_OPENSSL_PATH . " " . $this->get('cypherMethod') . " "
            . "-e -a -K " . $this->get('cypherKey') . " -iv "
            . $this->get('cypherIV');
        return exec($execStr);
    }

    public function decrypt($string) {
        //avoid decrypting an empty string:
        if (!strlen(trim($string))) {
            return $string;
        }
        exec ('export OPENSSL_FIPS=1');
        $string = base64_decode($string);
        $execStr = "echo " . escapeshellarg($string) . " | "
            . self::FIPS_OPENSSL_PATH . " " . $this->get('cypherMethod') . " "
            . "-d -a -K " . $this->get('cypherKey') . " " . "-iv "
            . $this->get('cypherIV');
        return exec($execStr);
    }
}
