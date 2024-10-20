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
    $resultsGateway = new ResultsDB($conn);
    //returns specified race using curcuit name,location,country
    if(isset($_GET['raceId'])){
        $results = $resultsGateway->getRaceIdAPI($_GET['raceId']);
    }
    if(isset($_GET['driverRef'])){
        $results = $resultsGateway->getDriverRefAPI($_GET['driverRef']);
    }

    echo json_encode($results, JSON_NUMERIC_CHECK);
}catch(Exception $e){
    die($e->getMessage());
}


?>