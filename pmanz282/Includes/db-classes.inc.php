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
   private static $baseSQL = "SELECT * FROM drivers";
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
class ConstructorsDB{
   private static $baseSQL = "SELECT * FROM constructors";
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