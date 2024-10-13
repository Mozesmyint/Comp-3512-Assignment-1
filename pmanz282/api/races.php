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
    /**Don't use foreign keys for race driver and constructor
     * instead use the following fields 
     * driver (driverRef, code, forename, surname)
     * race (name, round, year, data)
     * constructor (name, constructorRef, nationalilty
     * SORT BY grid (ASCENDING 1st place first, seconds place)
     */
    //retuns results from specified race

    //returns results for a given driver

    // echo json_encode();
}catch(Exception $e){}


?>