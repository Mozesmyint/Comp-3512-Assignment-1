<?php
require_once './Includes/Config.inc.php';
require_once './Includes/db-classes.inc.php';

try{
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

    $driver = new DriversDB($pdo);
    $con = new ConstructorsDB($pdo);
    $cir = new CircuitsDB($pdo);
    $race = new RacesDB($pdo); //ambiguous 
    $qual = new QualifyingDB($pdo);
    $res = new ResultsDB($pdo); 

    $values = $res->getAll();

    echo count($values);
    foreach($values as $v){
        echo $v[0];
    }
    $pdo = null;
}catch(Exception $e){
 echo $e;
}


?>
