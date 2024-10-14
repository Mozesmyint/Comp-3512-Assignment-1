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
    //returns drivers for a season
    $driverGateway = new DriversDB($conn);
    $drivers = $driverGateway->getAll();

    //returns driver specified ['driverRef']

    //returns drivers within a given race

    $driverGateway = new DriversDB($conn);
    if ( isCorrectQueryStringInfo("driverRef") ) {
        // $paintings = $gateway->getAllForArtist($_GET["artist"]); 
    }else if ( isCorrectQueryStringInfo("race") ) {
        // $paintings = $gateway->getAllForGallery($_GET["gallery"]); 
    }else 
    //returns drivers for a season
        $drivers = $driverGateway->getAll();

    echo json_encode( $drivers, JSON_NUMERIC_CHECK );
    $conn = null;
}catch(Exception $e){ die($e->getMessage());}

function isCorrectQueryStringInfo($param) { 
 if ( isset($_GET[$param]) && !empty($_GET[$param]) ) { 
 return true; 
 } else { 
 return false; 
 } 
}
?>