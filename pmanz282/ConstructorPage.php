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
    <title>COMP3512-f1DashboardProject</title>
</head>
<body>
<?php
        try{
            $conn = DatabaseHelper::createConnection(array(DBCONNSTRING, 
            DBUSER, DBPASS));

            //returns drivers for a season
            $driverGateway = new DriversDB($conn);
            $constructorGateway = new ConstructorsDB($conn);
            if(isset($_GET['constructorRef'])){
                $construct = $constructorGateway->getALLConstructorDetails($_GET['constructorRef']);
                $constructRaceDetails = $constructorGateway
                ->getALLRaceResultsConstructor($_GET['constructorRef'] );
            }
        } catch (PDOException $e){
            die($e->getMessage());
        }
    ?>

    <h1>F1 Dashboard Project</h1>
    <div class="container">
        <div class="section">
            <h2>Constructor Details</h2>
            <p>
                <?php
                    if(isset($_GET['constructorRef'])){
                        if(count($construct) > 0){
                            displayConstructorDetails($construct);
                        }else{
                          echo "no driver found with search term = " . $_GET['constructorRef'];
                        }
                      }else{
                        echo "Enter a search term and press Filter";
                      }
                ?>
            </p>
        </div>
        <div class="section">
            <h2>Race Results</h2>
                <?php
                    if(isset($_GET['constructorRef'])){
                        if(count($constructRaceDetails) > 0){
                            displayALLRaceResultsConstructor($constructRaceDetails);
                        }
                      }else{
                        echo "error";
                      }
                ?>
        </div>
    </div>
</body>
</html>
