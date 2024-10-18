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
    <link rel="stylesheet" href="css/index-page.css">
    <title>COMP3512-f1DashboardProject</title>
</head>
    <header>
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
            <a href="DriverPage.php"> <!--To be changed when page is made-->
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
            
        
        } catch (PDOException $e){
            die($e->getMessage());
        }
    ?>

    <h1>F1 Dashboard Project</h1>
    <div class="container">
        <div class="section">
            <h2>Driver Details</h2>
            <p>
                <?php
                    if(isset($_GET['driverRef'])){
                        displayDriver($driver);
                    //     if(count($driver) > 0){
                            
                    //         displayDriver($driver);
                    //     }else{
                    //       echo "no driver found with search term = " . $_GET['driverRef'];
                    //     }
                    //   }else{
                    //     echo "Enter a search term and press Filter";
                      }
                ?>
            </p>
        </div>
        <div class="section">
            <h2>Race Results</h2>
                <?php
                    if(isset($_GET['driverRef'])){
                        if(count($raceResults) > 0){
                            displayAllRaceResults($raceResults);
                        }
                      }else{
                        echo "error";
                      }
                ?>
        </div>
    </div>
</body>
</html>
