<?php
require_once './Includes/Config.inc.php';
require_once './Includes/db-classes.inc.php';
require_once './Includes/db-helpers.inc.php';

$conn = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));

$raceStmt = $conn->query("SELECT raceId, round, name from Races WHERE year = 2022;");
$race = $raceStmt;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index-page.css">
    <title>Browse Races</title>
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
            <h2>2022 Races</h2>
            <ul>
                <?php 
                    foreach ($race as $r) {
                        echo "<li>";
                        echo "<span>Round " . $r['round'] . ": " . $r['name'];
                        echo "<a href=browse.php?raceId=" . $r['raceId'] . " class=resultsBTN>Results</a>";
                        echo "</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</body>

</html>