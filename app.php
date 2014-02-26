<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Define constants that will be used by multiple service routers
 * @author     Dylan Wood <dwood@mrn.org>
 */

define('VENDOR_DIR', realpath(dirname(__FILE__)) . '/vendor');

require VENDOR_DIR . '/Slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
