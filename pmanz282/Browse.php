<?php
require_once './Includes/Config.inc.php';
require_once './Includes/db-classes.inc.php';
require_once './Includes/db-helpers.inc.php';

$conn = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));

$raceStmt = $conn->query("SELECT raceId, round, name from Races WHERE year = 2022;");
$race = $raceStmt;

$raceGateway = new RacesDB($conn);
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
            <a href="APIsPage.php"> <!--To be changed when page is made-->
                APIs
            </a>
        </nav>
    </header>

    <div class="container">
        <div class="leftcolumn">
            <h2>2022 Races</h2>
            <ul>
                <?php
                foreach ($race as $r) { ?>
                    <li>
                        <span>Round <?= $r['round']; ?>: <?= $r['name']; ?></span>
                        <a href="browse.php?raceId=<?= $r['raceId']; ?>" class="resultsBTN">Results</a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div class="rightcolumn">
            <?php
            if (isset($_GET['raceId'])) {
                $raceId = $_GET['raceId'];

                $resultsGateway = new ResultsDB($conn);
                $results = $resultsGateway->getAll($raceId);

                if ($results && count($results) > 0) { ?>
                    <h2>Results for <?= $results[0]['raceName']; ?></h2>

                    <h3>Qualifying</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Pos</th>
                                <th>Driver</th>
                                <th>Constructor</th>
                                <th>Q1</th>
                                <th>Q2</th>
                                <th>Q3</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $r) { ?>
                                <tr>
                                    <td><?= $r['positionOrder']; ?></td>
                                    <td><a href="DriverPage.php?driverRef=<?= $r['driverRef']; ?>"><?= $r['forename'] . ' ' . $r['surname']; ?></a></td>
                                    <td><a href="ConstructorPage.php?constructorId=<?= $r['constructorId']; ?>"><?= $r['constructorName']; ?></a></td>
                                    <td><?= $r['q1']; ?></td>
                                    <td><?= $r['q2']; ?></td>
                                    <td><?= $r['q3']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <div class="results-summary">
                        <div class="result-box">
                            <h3>1st</h3>
                            <p><?= $results[0]['forename'] . ' ' . $results[0]['surname']; ?></p>
                        </div>
                        <div class="result-box">
                            <h3>2nd</h3>
                            <p><?= $results[1]['forename'] . ' ' . $results[1]['surname']; ?></p>
                        </div>
                        <div class="result-box">
                            <h3>3rd</h3>
                            <p><?= $results[2]['forename'] . ' ' . $results[2]['surname']; ?></p>
                        </div>
                    </div>

                    <h3>Race Results</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Pos</th>
                                <th>Driver</th>
                                <th>Constructor</th>
                                <th>Laps</th>
                                <th>Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $result) { ?>
                                <tr>
                                    <td><?= $result['positionOrder']; ?></td>
                                    <td><a href="DriverPage.php?driverRef=<?= $result['driverRef']; ?>"><?= $result['forename'] . ' ' . $result['surname']; ?></a></td>
                                    <td><a href="constructor.php?constructorId=<?= $result['constructorId']; ?>"><?= $result['constructorName']; ?></a></td>
                                    <td><?= $result['laps']; ?></td>
                                    <td><?= $result['points']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php
                } else { ?>
                    No results available for this race.
                <?php
                }
            } else { ?>
                Please select a race to view the results.
            <?php } ?>
        </div>
    </div>
</body>

</html>