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
"SELECT * FROM Drivers 
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
   $sql = self::$baseSQL . " WHERE Drivers.driverRef LIKE ? AND Races.year = 2023"; 
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $identifier);
   return $statement->fetch(PDO::FETCH_ASSOC); 
}
public function getAllForRace($identifier){
   //Inspiration to use Distinct: https://www.w3schools.com/sql/trysql.asp?filename=trysql_select_distinct
   $sql = 
   "SELECT DISTINCT Races.round, Circuits.name, Qualifying.position, MAX(ConstructorResults.points) AS MAX_POINTS
   FROM Drivers
         INNER JOIN Qualifying ON Drivers.driverId = Qualifying.driverId
         INNER JOIN Races ON Qualifying.raceId = Races.raceId
         INNER JOIN Circuits ON Circuits.circuitId = Races.circuitId
         INNER JOIN ConstructorResults ON Races.raceId = ConstructorResults.raceId
         INNER JOIN Seasons ON Races.year = Seasons.year
   WHERE 
      Races.year = 2023 AND Drivers.driverRef = ?
   GROUP BY Races.round, Circuits.name, Qualifying.position 
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
public function getALLConstructorDetails($constructorRef){
   $sql = 
   "SELECT Constructors.constructorRef, Constructors.name, Constructors.nationality, Constructors.url FROM Constructors
   WHERE Constructors.constructorRef = ?
   ";
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $constructorRef);
   return $statement->fetch(PDO::FETCH_ASSOC);
}
public function getALLRaceResultsForConstructor($driverRef ,$constructorRef){
   $sql = 
   "
   
   ";
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($driverRef, $constructorRef));
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
   WHERE Seasons.year = 2023 ORDER BY Races.round";
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
public function getAll() { 
   $sql = self::$baseSQL; 
   $statement = 
      DatabaseHelper::runQuery($this->pdo, $sql, null); 
   return $statement->fetchAll(); 
   } 
}
class ResultsDB{
private static $baseSQL = 
"
SELECT * FROM Results
INNER JOIN Races ON Results.raceId = Races.raceId
INNER JOIN Drivers ON Results.driverId = Drivers.driverId
INNER JOIN Constructors ON Results.constructorId = 
Constructors.constructorId
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
}
?>