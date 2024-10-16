<?php

require_once '../Includes/Config.inc.php';
require_once '../Includes/db-classes.inc.php';

// Tell the browser to expect JSON rather than HTML
header('Content-type: application/json'); 
// indicate whether other domains can use this API
header("Access-Control-Allow-Origin: *"); 

try{
    $conn = DatabaseHelper::createConnection(array(DBCONNSTRING, 
    DBUSER, DBPASS));

    $driverGateway = new DriversDB($conn);
    

    //returns driver specified 
    if ( isCorrectQueryStringInfo('driverRef') ) {
        $result = $driverGateway->getOneForDriverRef($_GET['driverRef']); 

    //returns drivers within a given race
    }else if ( isCorrectQueryStringInfo('race') ) {
        $result = $driverGateway->getAllForRace($_GET['race']);

    //returns drivers for the season (2022)
    }else{
        // $result = $driverGateway->getAllDriverForSeason();
    } 
    
    echo json_encode( $result, JSON_NUMERIC_CHECK );
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