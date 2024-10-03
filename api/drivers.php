<?php
include __DIR__ . "data/db_conn.php";

header('Content-Type: application/json');

$driver = getData("select driverRef from Drivers"); // don't know why I put this here, going to keep it just in case I remember why..

// if driver is passed as a query string..
if (isset($_GET['driverRef'])) {
    $driverRef = $_GET['driverRef'];
    $results = getData("select driverRef from Drivers WHERE driverRef= "+$_GET['driverRef']);

}

// else if not query string passed, then return all drivers from database..
else {
    $results = getData("select * from Drivers");
}
// TODO: Return drivers for a given race if race is passed as query string..

echo json_encode($results);
?>
