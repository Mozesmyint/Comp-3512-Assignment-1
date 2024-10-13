<?php 
require_once('Config.inc.php');
require_once('db-classes.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>F1 Dashboard Project</h1>
    <div class="container">
        <div class="section">
            <h2>Driver Details</h2>
    <?php
        try{
            $pdo = new PDO (DBCONNSTRING, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if(isset($_GET['driverRef'])){
                $driverRef = $_GET['driverRef'];
            
                $sql = "SELECT name, dob, nationality, url FROM Drivers WHERE driverRef = :driverRef";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $driver = $stmt->fetchall(PDO::FETCH_ASSOC);

                if($driver){ 
                    echo "<p>Name: " . $driver['name'] . "</p>";
                    echo "<p>Name: " . $driver['dob'] . "</p>";
                    echo "<p>Name: " . $driver['nationality'] . "</p>";
                    echo "<p>Name: " . $driver['url'] . "</p>";
                } else {
                    echo "No driver details found";
                }
            } else {
                echo "No driverRef";
            }
        } catch (PDOException $e){
            die($e->getMessage);
        }
    ?>
        </div>
        <div class="section">
            <h2>Race Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Rnd</th>
                        <th>Circuit</th>
                        <th>Pos</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
