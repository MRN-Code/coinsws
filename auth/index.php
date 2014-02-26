<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Router for coinsws auth and encryption services
 *
 * @author     Dylan Wood <dwood@mrn.org>
 */

//require app, which will set constants and 
//define our router (app).
require '../app.php';

//specify routes
$app->get('/cypher/:mode/:inputString', function($mode, $inputString) use ($app) {
    require_once 'classes/Cypher.class.php';
    echo Cypher::getEncryptedString($inputString, $mode);
});

$app->run();
