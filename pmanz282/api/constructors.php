<?php

require_once 'pmanz282\Includes\Config.inc.php';
require_once 'pmanz282\Includes\db-classes.inc.php';

// Tell the browser to expect JSON rather than HTML
header('Content-type: application/json'); 
// indicate whether other domains can use this API
header("Access-Control-Allow-Origin: *"); 

try{
    $conn = $DatabaseHelper::createConnection(array(DBCONNSTRING, 
    DBUSER, DBPASS));

    // echo json_encode();
}catch(Exception $e){}


?>