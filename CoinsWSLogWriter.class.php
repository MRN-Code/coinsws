<?php
class CoinsWSLogWriter {
    private $logPath;

    public function __construct() {
        $this->logPath = '/var/log/coinsws/coinsws_log';
    }
    public function write($message) {
        $handle = fopen($this->logPath, 'a+');
        $timestamp = date('Y-m-d H:i:s');
        fwrite($handle, $timestamp . ' ' . $message . "\n");
        fclose($handle);
    }

}
