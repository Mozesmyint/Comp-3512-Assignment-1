
<?php 

try{
    $pdo = new PDO (DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT name, dob, nationality, url FROM Drivers";
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
}
catch (PDOException $e){
    die($e->getMessage);
}
?>