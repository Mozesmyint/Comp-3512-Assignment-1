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
"SELECT %s --Select
FROM Drivers 
   %s --Inner
   %s --Where
   %s --Group
   %s --Order
   ";
private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getAllAPI() { 
   $selectSQL = " Drivers.forename, Drivers.surname, Drivers.dob, Drivers.nationality, Drivers.url AS dURL ";
   $innerjoinSQL =
   " INNER JOIN Qualifying ON Drivers.driverId = Qualifying.driverId
      INNER JOIN Races ON Qualifying.raceId = Races.raceId
   ";
   $whereSQL = " WHERE Races.year = 2022 ";
   $sql =sprintf(self::$baseSQL, $selectSQL, $innerjoinSQL, $whereSQL, "", "");
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, null); 
   return $statement->fetchAll(); 
} 
public function getOneForDriverRef($identifier){
   $selectSQL = " Drivers.forename, Drivers.surname, Drivers.dob, Drivers.nationality, Drivers.url AS dURL ";
   $innerjoinSQL =
   " INNER JOIN Qualifying ON Drivers.driverId = Qualifying.driverId
      INNER JOIN Races ON Qualifying.raceId = Races.raceId
   ";
   $whereSQL = " WHERE Drivers.driverRef LIKE ? AND Races.year = 2022 ";
   $sql =sprintf(self::$baseSQL, $selectSQL, $innerjoinSQL, $whereSQL, "", "");
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $identifier);
   return $statement->fetch(PDO::FETCH_ASSOC); 
}
public function getAllForRace($driverRef){
   //Inspiration to use Distinct: https://www.w3schools.com/sql/trysql.asp?filename=trysql_select_distinct
   $sql = 
   "SELECT DISTINCT Races.round, Races.name, results.position, results.points
   FROM Drivers
         INNER JOIN results on Drivers.driverId = results.driverId
         -- INNER JOIN Qualifying ON Drivers.driverId = Qualifying.driverId
         INNER JOIN Races ON results.raceId = Races.raceId
         INNER JOIN Circuits ON Circuits.circuitId = Races.circuitId
         INNER JOIN ConstructorResults ON Races.raceId = ConstructorResults.raceId
         INNER JOIN Seasons ON Races.year = Seasons.year
   WHERE 
      Drivers.driverRef = ? AND Races.year = 2022
   GROUP BY Races.round, Races.name, results.position 
   ORDER BY Races.round ";
   //If using DISTINCT, values in select must be repeated in group by or aggregated

   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $driverRef);
   return $statement->fetchAll(PDO::FETCH_ASSOC);
}
public function getAllForDriverIdAPI($driverId){
   //Inspiration to use Distinct: https://www.w3schools.com/sql/trysql.asp?filename=trysql_select_distinct
   $sql = 
   "SELECT DISTINCT Drivers.driverRef, Drivers.forename, Drivers.surname, Drivers.dob, Drivers.nationality, Drivers.url
   FROM Drivers
         INNER JOIN results on Drivers.driverId = results.driverId
         INNER JOIN Races ON results.raceId = Races.raceId
         INNER JOIN Circuits ON Circuits.circuitId = Races.circuitId
         INNER JOIN ConstructorResults ON Races.raceId = ConstructorResults.raceId
         INNER JOIN Seasons ON Races.year = Seasons.year
   WHERE 
      Races.raceId = ?
   GROUP BY Drivers.driverRef, Drivers.forename, Drivers.surname, Drivers.dob, Drivers.nationality, Drivers.url
   ";
   //If using DISTINCT, values in select must be repeated in group by or aggregated

   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $driverId);
   return $statement->fetchAll(PDO::FETCH_ASSOC);
}
}
class ConstructorsDB{
private static $baseSQL = 
"SELECT %s --Select
FROM Constructors 
%s --Inner
%s --Where
%s --Group
%s --Order
";

private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getAllConstructorsAPI() { 
   $selectSQL = " Constructors.constructorRef, Constructors.name, Constructors.nationality, Constructors.url ";
   $sql = sprintf(self::$baseSQL, $selectSQL, "", "", "", "");
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, null ); 
   return $statement->fetchAll(PDO::FETCH_ASSOC); 
   }
public function getConstructorRefAPI($constructorRef) {
   $selectSQL = " Constructors.constructorRef, Constructors.name, Constructors.nationality, Constructors.url ";
   $whereSQL =" WHERE constructorRef = ? ";
   $sql = sprintf(self::$baseSQL, $selectSQL, "", $whereSQL, "", "");
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($constructorRef));
   return $statement->fetchAll();
}
public function getALLConstructorDetails($constructorRef){
   $selectSQL = " Constructors.constructorRef, Constructors.name, Constructors.nationality, Constructors.url ";
   $whereSQL =" WHERE Constructors.constructorRef = ? ";
   $sql = sprintf(self::$baseSQL, $selectSQL, "", $whereSQL, "", "");
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $constructorRef);
   return $statement->fetch(PDO::FETCH_ASSOC);
}
public function getALLRaceResultsConstructor($constructorRef){
   //Round Circuit Driver Position Points
   $selectSQL = " DISTINCT Races.round, Races.name, Drivers.forename, Drivers.surname, Results.points, Results.position  ";
   $innerjoinSQL = 
   " INNER JOIN results ON Constructors.constructorId = Results.constructorId
      INNER JOIN Drivers ON Drivers.driverId = results.driverId
      INNER JOIN Races ON Races.raceId = results.raceId
      INNER JOIN Circuits ON Circuits.circuitId = Races.circuitId
   ";
   $whereSQL = " WHERE Races.year = 2022 AND Constructors.constructorRef = ? ";
   $groupSQL = " GROUP BY Constructors.constructorRef, Races.round, Circuits.name, Drivers.forename, Drivers.surname ";
   $orderSQL = " ORDER BY Races.round, results.position ";
   $sql = sprintf(self::$baseSQL, $selectSQL, $innerjoinSQL,$whereSQL, $groupSQL, $orderSQL);
   // name, constructorRef, nationality
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $constructorRef);
   return $statement->fetchAll(PDO::FETCH_ASSOC);
}
}
class CircuitsDB{
private static $baseSQL = 
"SELECT 
   Circuits.circuitRef, Circuits.name, Circuits.location, Circuits.country,
   Circuits.lat, Circuits.lng, Circuits.alt, Circuits.url
FROM circuits
%s --Where
";
private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getAllCircuitsAPI() { 
   $sql = sprintf(self::$baseSQL, "");
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, null); 
   return $statement->fetchAll(); 
   } 
public function getCircuitRefAPI($circuitRef) {
   $whereSQL = " WHERE circuitRef = ? ";
   $sql = sprintf(self::$baseSQL, $whereSQL);
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($circuitRef));
   return $statement->fetchAll();
}
}
class RacesDB{
private static $baseSQL = 
"
SELECT %s
FROM Races
%s
WHERE Races.year = 2022 %s
%s";

private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getAll() { 
   $selectSQL =" Races.name, Races.round, Races.year, Races.date ";
   $orderSQL =" ORDER BY Races.round";
   $sql = sprintf(self::$baseSQL, $selectSQL, "", "",$orderSQL);
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, null); 
   return $statement->fetchAll(PDO::FETCH_ASSOC); 
} 

public function getRacesRef($raceId){
   $selectSQL = " Circuits.name, Circuits.location, Circuits.country ";
   $joinSQL =" INNER JOIN Circuits ON Races.circuitId = Circuits.circuitId ";
   $whereSQL =" AND Races.raceId = ? ";
   $sql = sprintf(self::$baseSQL, $selectSQL, $joinSQL, $whereSQL,"");
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $raceId); 
   return $statement->fetch(PDO::FETCH_ASSOC); 
}
}
class QualifyingDB{
private static $baseSQL = 
"SELECT Qualifying.position, Qualifying.q1, Qualifying.q2,Qualifying.q3 
   FROM Qualifying
   INNER JOIN Races ON Races.raceId = Qualifying.raceId
   INNER JOIN Results ON Races.raceId = Results.raceId
   %s
   GROUP BY Qualifying.position
";
private $pdo;
public function __construct($connection){
   $this->pdo = $connection;
}
public function getQualifyingRef($raceId) {
   //Inspiration to use sprintf, https://www.w3schools.com/php/func_string_sprintf.asp
   $whereSQL =" WHERE Races.raceId = ? ";
   $Stringsql =sprintf(self::$baseSQL, $whereSQL);
   $statement = DatabaseHelper::runQuery($this->pdo, $Stringsql, $raceId);
   return $statement->fetchALL(PDO::FETCH_ASSOC);
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
public function getRaceIdAPI($raceId){
   /*driverRef, code, forename, surname
   name, round, year, date
   name, constructorRef, nationality
   */
   $sql =
   "SELECT 
    Drivers.driverRef, Drivers.code, Drivers.forename, Drivers.surname,
    Races.name, Races.round, Races.year, Races.date,
    Constructors.name, Constructors.constructorRef, Constructors.nationality
   FROM Results
   INNER JOIN Drivers ON Drivers.driverId = Results.driverId
   INNER JOIN Races ON Races.raceId = Results.raceId
   INNER JOIN Constructors ON Constructors.constructorId = Results.constructorId
   WHERE Races.year = 2022 AND Races.raceId = ?
   ORDER BY Results.grid
   ";
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $raceId);
   return $statement->fetchAll(PDO::FETCH_ASSOC);
}
public function getDriverRefAPI($driverRef){
   $sql= 
   "SELECT Drivers.driverRef, Drivers.number, Drivers.code, Drivers.forename, Drivers.surname,
   Drivers.dob, Drivers.nationality, Drivers.url
   FROM Results
   INNER JOIN Drivers ON Drivers.driverId = Results.driverId
   INNER JOIN Races ON Races.raceId = Results.raceId
   WHERE Races.year = 2022 AND Drivers.driverRef = ?
   ";
   $statement = DatabaseHelper::runQuery($this->pdo, $sql, $driverRef);
   return $statement->fetchALL(PDO::FETCH_ASSOC);
}




}
?>