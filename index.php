<?php
define("DB_NAME","mrs");
/**
 * This script provides a RESTful API interface for a the COINS system.
 *
 * Input:
 *
 * $_GET['format'] = [ json | html | xml ]
 * $_GET['method'] = []
 *
 * Output: A formatted HTTP response
 *
 * Author: Mark Roland
 */


// --- Step 1: Initialize variables and functions

/**
 * Deliver HTTP Response
 * @param string $format The desired HTTP response content type: [json, html, xml]
 * @param string $api_response The desired HTTP response data
 * @return void
 **/
function deliver_response($format, $api_response) {

    // Define HTTP responses
    $http_response_code = array
        (
         200 => 'OK',
         400 => 'Bad Request',
         401 => 'Unauthorized',
         403 => 'Forbidden',
         404 => 'Not Found'
        );

    // Set HTTP Response
    header
    (
        'HTTP/1.1 ' . $api_response['status'] . ' ' . 
        $http_response_code[ $api_response['status'] ]
    );

    // Process different content types
    if ( strcasecmp($format,'json') == 0 ) {

        // Set HTTP Response Content Type
        header('Content-Type: application/json; charset=utf-8');

        // Format data into a JSON response
        $json_response = json_encode($api_response);

        // Deliver formatted data
        echo $json_response;

    } elseif ( strcasecmp($format,'xml') == 0 ) {

        // Set HTTP Response Content Type
        header('Content-Type: application/xml; charset=utf-8');

        // Format data into an XML response (This is only good at 
        // handling string data, not arrays)
        $xml_response = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
            '<response>' . "\n" . 
            "\t" . '<code>' . $api_response['code'] . '</code>' . "\n" . 
            "\t" . '<data>' . $api_response['data'] . '</data>' . "\n" . 
            '</response>';

        // Deliver formatted data
        echo $xml_response;

    } else {

        // Set HTTP Response Content Type (This is only good at handlin
        // g string data, not arrays)
        header('Content-Type: text/html; charset=utf-8');

        // Deliver formatted data
        echo $api_response['data'];

    }

    // End script process
    exit;

}
function getUserDBSession() {
    if (!isset($_SESSION['objDbLink'])) {
        $_SESSION['objDbLink'] = new BaseAppData(DB_NAME, 'MRSDBA');
    }
}

// Define whether an HTTPS connection is required
$HTTPS_required = TRUE;

// Define whether user authentication is required
$authentication_required = FALSE;

// Define API response codes and their related HTTP response
$api_response_code = array(
        0 => array('HTTP Response' => 400, 'Message' => 'Unknown Error'),
        1 => array('HTTP Response' => 200, 'Message' => 'Success'),
        2 => array('HTTP Response' => 403, 'Message' => 'HTTPS Required'),
        3 => array('HTTP Response' => 401, 'Message' => 'Authentication Required'),
        4 => array('HTTP Response' => 401, 'Message' => 'Authentication Failed'),
        5 => array('HTTP Response' => 404, 'Message' => 'Invalid Request'),
        6 => array('HTTP Response' => 400, 'Message' => 'Invalid Response Format')
        );

// Set default HTTP response of 'ok'
$response['code'] = 0;
$response['status'] = 404;
$response['data'] = NULL;

// --- Step 2: Authorization

// Optionally require connections to be made via HTTPS
if ( $HTTPS_required && $_SERVER['HTTPS'] != 'on' ) {
    $response['code'] = 2;
    $response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
    $response['data'] = $api_response_code[ $response['code'] ]['Message']; 

    // Return Response to browser. This will exit the script.
    deliver_response($_GET['format'], $response);
}

// Optionally require user authentication
if ( $authentication_required ) {

    if ( empty($_POST['username']) || empty($_POST['password']) ) {
        $response['code'] = 3;
        $response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
        $response['data'] = $api_response_code[ $response['code'] ]['Message'];
    } elseif ( $_POST['username'] != 'foo' && $_POST['password'] != 'bar' ) {
        // Return an error response if user fails authentication. This is a very simplistic example
        // that should be modified for security in a production environment
        $response['code'] = 4;
        $response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
        $response['data'] = $api_response_code[ $response['code'] ]['Message'];
    }

}

// --- Step 3: Process Request
include("BaseAppData.inc");
getUserDbSession();

// Method A: Say Hello to the API
if ( strcasecmp($_GET['method'],'hello') == 0) {
    $response['code'] = 1;
    $response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
    $response['data'] = 'Hello World';  
} elseif ( strcasecmp($_GET['method'],'encrypt') == 0) {
    $input =  '';
    if (isset($_GET['arg1'])) {
        $input = $_GET['arg1'];
    }
    $response['code'] = 1;
    $response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
    $response['data'] = $_SESSION['objDbLink']->encryptAppString($input); 
}

// --- Step 4: Deliver Response

// Return Response to browser
deliver_response($_GET['format'], $response);

?>
