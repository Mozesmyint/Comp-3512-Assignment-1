<?php

require_once '../Includes/Config.inc.php';
require_once '../Includes/db-classes.inc.php';

// Tell the browser to expect JSON rather than HTML
header('Content-type: application/json');
// indicate whether other domains can use this API
header("Access-Control-Allow-Origin: *");

try {
    $conn = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));
    $constructorGateway = new ConstructorsDB($conn);
    if (isCorrectQueryStringInfo("ref")) {
        $constructors = $constructorGateway->getConstructorRefAPI($_GET["ref"]);
    } else {
        $constructors = $constructorGateway->getAllConstructorsAPI();
    }
    echo json_encode($constructors, JSON_NUMERIC_CHECK);
} catch (Exception $e) {
    die($e->getMessage());
}

function isCorrectQueryStringInfo($param)
{
    if (isset($_GET[$param]) && !empty($_GET[$param])) {
        return true;
    } else {
        return false;
    }
}
