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
$app->group('/FIPSCypher', function() use($app) {
    require_once 'classes/FIPSCypher.class.php';
    $app->get('/encrypt/:mode/:inputString', function($mode, $inputString) use($app) {
        echo FIPSCypher::encryptString($inputString, $mode);
    });
    $app->get('/encryptMany/:mode/:inputJSON', function($mode, $inputJSON) use($app) {
        $app->response->headers->set('Content-Type', 'application/json');
        echo FIPSCypher::encryptJSONList($inputJSON, $mode);
    });
});

$app->run();
