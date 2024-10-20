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
    /**Don't use foreign keys for race driver and constructor
     * instead use the following fields 
     * driver (driverRef, code, forename, surname)
     * race (name, round, year, data)
     * constructor (name, constructorRef, nationalilty
     * SORT BY grid (ASCENDING 1st place first, seconds place)
     */
    $racesGateway = new RacesDB($conn);

    //returns results from specified race
    //returns results for a given driver
    if(isset($_GET['raceId'])){
        $raceResults = $racesGateway->getRacesRef($_GET['raceId']);
    }else{
        $raceResults = $racesGateway->getAll();
    }

    echo json_encode($raceResults, JSON_NUMERIC_CHECK);
}catch(Exception $e){
    die($e->getMessage());
}


?>