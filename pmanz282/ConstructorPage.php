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
    <link rel="stylesheet" href="css/Constructor.css">
    <title>COMP3512-f1DashboardProject</title>
</head>
<header>
    <img class="logo" src="./Includes/images/f1_logo.png" alt="logo">
    <h1>F1 Dashboard Project</h1>
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
    
    <div class="container">
        <fieldset class="section">
            <h2>Constructor Details</h2>
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
        </fieldset>
        <fieldset class="section">
            <h2>Race Results</h2>
                <?php
                    if(isset($_GET['constructorRef'])){
                        if(count($constructRaceDetails) > 0){
                            displayALLRaceResultsConstructor($constructRaceDetails);
                        }
                      }else{
                        echo "No value in constructorRef";
                      }
                ?>
        </fieldset>
    </div>
</body>
</html>
