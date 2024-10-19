<?php
class DatabaseHelper { 
   /* Returns a connection object to a database */
   public static function createConnection( $values=array() ) { 
      $connString = $values[0]; 
      $user = $values[1]; 
      $password = $values[2]; 
      $pdo = new PDO($connString,$user,$password); 
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
      return $pdo; 
   } 
   /*
   Runs the specified SQL query using the passed connection and 
   the passed array of parameters (null if none)
   */
   public static function runQuery($connection, $sql, $parameters) { 
   $statement = null; 
   // if there are parameters then do a prepared statement
   if (isset($parameters)) { 
   // Ensure parameters are in an array
      if (!is_array($parameters)) { //put them in array if not in one alr
         $parameters = array($parameters); 
      } 
      // Use a prepared statement if parameters 
      $statement = $connection->prepare($sql); 


      
      //binding parameters to sql (?)
      for($i=0;$i<count($parameters);$i++){
         $statement->bindValue($i+1, $parameters[$i]);
   }

      $executedOk = $statement->execute($parameters); 

      if (! $executedOk) throw new PDOException; 
   } else { 
      // Execute a normal query 
      $statement = $connection->query($sql); 
      if (!$statement) throw new PDOException; 
   } 
   return $statement; 
   } 

} 
class DriversDB{
private static $baseSQL = 
"SELECT Drivers.url AS dURL, * FROM Drivers 
   INNER JOIN Qualifying ON Drivers.driverId = Qualifying.driverId
   INNER JOIN Races ON Qualifying.raceId = Races.raceId
   INNER JOIN ConstructorResults ON ConstructorResults.constructorId =  Qualifying.constructorId
   INNER JOIN Seasons ON Races.year = Seasons.year ";
private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getAll() { 
   $sql = self::$baseSQL; 
   $statement = 
      DatabaseHelper::runQuery($this->pdo, $sql, null); 
   return $statement->fetchAll(); 
} 
public function getOneForDriverRef($identifier){
   $sql = self::$baseSQL . " WHERE Drivers.driverRef LIKE ? AND Races.year = 2022"; 
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $identifier);
   return $statement->fetch(PDO::FETCH_ASSOC); 
}
public function getAllForRace($identifier){
   //Inspiration to use Distinct: https://www.w3schools.com/sql/trysql.asp?filename=trysql_select_distinct
   $sql = 
   "SELECT DISTINCT Races.round, races.name, results.position, results.points
   FROM Drivers
         INNER JOIN results on Drivers.driverId = results.driverId
         -- INNER JOIN Qualifying ON Drivers.driverId = Qualifying.driverId
         INNER JOIN Races ON results.raceId = Races.raceId
         INNER JOIN Circuits ON Circuits.circuitId = Races.circuitId
         INNER JOIN ConstructorResults ON Races.raceId = ConstructorResults.raceId
         INNER JOIN Seasons ON Races.year = Seasons.year
   WHERE 
      Races.year = 2022 AND Drivers.driverRef = ?
   GROUP BY Races.round, Circuits.name, results.position 
   ORDER BY Races.round";
   //If using DISTINCT, values in select must be repeated in group by or aggregated

   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $identifier);
   return $statement->fetchAll(PDO::FETCH_ASSOC);
}
}
class ConstructorsDB{
private static $baseSQL = 
"SELECT * FROM Constructors ";
private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getAll() { 
   $sql = self::$baseSQL; 
   $statement = 
      DatabaseHelper::runQuery($this->pdo, $sql, null ); 
   return $statement->fetchAll(PDO::FETCH_ASSOC); 
   }
public function getConstructorRef($constructorRef) {
   $sql = self::$baseSQL . " WHERE constructorRef = ?";
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($constructorRef));
   return $statement->fetchAll();
}
public function getALLConstructorDetails($constructorRef){
   $sql = 
   "SELECT Constructors.constructorRef, Constructors.name, Constructors.nationality, Constructors.url FROM Constructors
   WHERE Constructors.constructorRef = ?
   ";
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $constructorRef);
   return $statement->fetch(PDO::FETCH_ASSOC);
}
public function getALLRaceResultsConstructor($constructorRef){
   //Round Circuit Driver Position Points
   $sql = 
   "SELECT DISTINCT Races.round, races.name, Drivers.forename, Drivers.surname, Results.points, Results.position 
   FROM Constructors
      -- INNER JOIN Qualifying ON Constructors.constructorId = Qualifying.constructorId
      INNER JOIN results ON Constructors.constructorId = Results.constructorId
      INNER JOIN Drivers ON Drivers.driverId = results.driverId
      INNER JOIN Races ON Races.raceId = results.raceId
      INNER JOIN Circuits ON Circuits.circuitId = Races.circuitId
      -- INNER JOIN Results ON Results.constructorId = Qualifying.constructorId
   WHERE 
      Races.year = 2022 AND Constructors.constructorRef = ?
   GROUP BY 
      Constructors.constructorRef, Races.round, Circuits.name, Drivers.forename, Drivers.surname
   ORDER BY 
      Races.round, results.position";
   // name, constructorRef, nationality
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $constructorRef);
   return $statement->fetchAll(PDO::FETCH_ASSOC);
}
}
class CircuitsDB{
private static $baseSQL = "SELECT * FROM circuits";
private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getAll() { 
   $sql = self::$baseSQL; 
   $statement = 
      DatabaseHelper::runQuery($this->pdo, $sql, null); 
   return $statement->fetchAll(); 
   } 
public function getCircuitRef($circuitRef) {
   $sql = self::$baseSQL . " WHERE circuitRef = ?";
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($circuitRef));
   return $statement->fetchAll();
}
}
class RacesDB{
private static $baseSQL = 
"
SELECT raceId, year, round, circuitId
FROM Races
INNER JOIN Circuits ON Races.circuitId = Circuits.circuitId
INNER JOIN Qualifying ON Races.raceId = Qualifying.raceId
INNER JOIN Results ON Races.raceId = Results.raceId
";

private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getAll() { 
   $sql = self::$baseSQL; 
   $statement = 
      DatabaseHelper::runQuery($this->pdo, $sql, null); 
   return $statement->fetchAll(); 
   } 
public function getAllUniqRaces(){
   $sql = 
   "SELECT DISTINCT Races.raceId, Races.name FROM Drivers
   INNER JOIN Qualifying ON Drivers.driverId = Qualifying.driverId
   INNER JOIN Races ON Qualifying.raceId = Races.raceId
   INNER JOIN ConstructorResults ON ConstructorResults.constructorId =  Qualifying.constructorId
   INNER JOIN Seasons ON Races.year = Seasons.year
   WHERE Seasons.year = 2022 ORDER BY Races.round";
   $statement = DatabaseHelper::runQuery($this->pdo,$sql,null);
   return $statement->fetchAll(PDO::FETCH_ASSOC);
}
}
class QualifyingDB{
private static $baseSQL = 
"
SELECT * 
FROM Qualifying 
INNER JOIN Drivers ON Qualifying.driverId = Drivers.driverId
INNER JOIN Races ON Qualifying.raceId = Races.raceId
INNER JOIN Constructors ON Constructors.constructorId = 
Qualifying.constructorId;
";

private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getQualifyingRef($qualifyingRef) {
   $sql = self::$baseSQL . " WHERE qualifyingId = ?";
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($qualifyingRef));
   return $statement->fetchAll();
}
public function getAll() { 
   $sql = self::$baseSQL; 
   $statement = 
      DatabaseHelper::runQuery($this->pdo, $sql, null); 
   return $statement->fetchAll(); 
   } 
}
class ResultsDB{
private static $baseSQL = 
"SELECT races.name AS raceName, results.*, drivers.forename, drivers.surname, drivers.driverRef, constructors.name AS constructorName, 
qualifying.q1, qualifying.q2, qualifying.q3, constructors.constructorRef
FROM results
INNER JOIN races ON Results.raceId = Races.raceId
INNER JOIN drivers ON Results.driverId = Drivers.driverId
INNER JOIN constructors ON Results.constructorId = Constructors.constructorId
LEFT JOIN qualifying ON results.raceId = qualifying.raceId AND results.driverId = qualifying.driverId
WHERE results.raceId = :raceId";

private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getAll($raceId) { 
   $sql = self::$baseSQL; 
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($raceId)); 
   return $statement->fetchAll(PDO::FETCH_ASSOC); 
   } 
}
?>