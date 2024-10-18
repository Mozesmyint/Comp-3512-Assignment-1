<?php 
require_once './Includes/Config.inc.php';
require_once './Includes/db-classes.inc.php';
require_once './Includes/db-helpers.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>COMP3512-f1DashboardProject</title>
</head>
    <header>
            <!-- Logo -->
            <img class="logo" src="./Includes/images/f1_logo.png" alt="logo">
            <h2>F1 Dashboard Project</h2>
        <nav>
            <!-- Home button -->
            <a href="index.php">
                Home
            </a>
            <!-- Browse button -->
            <a href="Browse.php">
                Browse
            </a>
            <!-- APIs button -->
            <a href="APIsPage.php">
                APIs
            </a>
            
        </nav>
    </header>
<body>
<?php
        try{
            $conn = DatabaseHelper::createConnection(array(DBCONNSTRING, 
            DBUSER, DBPASS));

            //returns drivers for a season
            $driverGateway = new DriversDB($conn);
            
                
            if(isset($_GET['driverRef'])){
                $driver = $driverGateway->getOneForDriverRef($_GET['driverRef']);
                $raceResults = $driverGateway->getAllForRace($_GET['driverRef']);
            }
            $conn = null;
        
        } catch (PDOException $e){
            die($e->getMessage());
        }
    ?>
    
    <div class="container">
        <fieldset class="section">
            <h2>Driver Details</h2>
            <p>
                <?php
                    if(isset($_GET['driverRef'])){
                        displayDriver($driver);
                      }
                ?>
            </p>
        </fieldset>
        <fieldset class="section">
            <h2>Race Results</h2>
                <?php
                    if(isset($_GET['driverRef'])){
                        if(count($raceResults) > 0){
                            displayAllRaceResultsDriver($raceResults);
                        }
                      }else{
                        echo "no value in driverRef";
                      }
                ?>
        </fieldset>
    </div>
</body>
</html>
