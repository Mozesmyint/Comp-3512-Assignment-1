<!-- <?php
        require_once './Includes/Config.inc.php';
        require_once './Includes/db-classes.inc.php';

        try {
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
            foreach ($values as $v) {
                echo $v[0];
            }
            $pdo = null;
        } catch (Exception $e) {
            echo $e;
        }
        ?> -->

<!-- not sure if we need try catch in index page? -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index-page.css">
</head>

<body>
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

    <div class="container">
        <div class="leftcolumn">
            <div class="desc">
                <p>Testing</p>
            </div>
            <a href="DriverPage.php"> <!--To be changed when page is made-->
                Browse 2022 Season
            </a>
        </div>

        <div class="rightcolumn">
            <img src="/pmanz282/Includes/images/testimg.png">
        </div>
    </div>
</body>

</html>