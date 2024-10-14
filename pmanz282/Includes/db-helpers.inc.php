<?php 
require_once './Includes/Config.inc.php';
require_once './Includes/db-classes.inc.php';


function displayDriver($d){
        echo "<p> Name: ".$d['forename']." ".$d['surname']."</p>";
        echo "<p> Date of Birth: ".$d['dob']."</p>";
        echo "<p> Age: ".$d['dob']."</p>";
        echo "<p> Nationality: ".$d['nationality']."</p>";
        echo "<p> Url: ".$d['url']."</p>";
}
function calcAge($dob){ //not finished, implement age in displayDriver
    $birthday = date("Y/m/d") - $dob;
    return $birthday;
}
function displayAllRaceResults($results){
    echo "<table>";
    echo "<thead>";
        echo "<tr>";
            echo "<th>Rnd</th>";
            echo "<th>Circuit</th>";
            echo "<th>Pos</th>";
            echo "<th>Points</th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($results as $r){
            echo "<tr>";
                echo "<td>".$r['round']."</td>";
                echo "<td>".$r['name']."</td>";
                echo "<td>".$r['position']."</td>";
                echo "<td>".$r['points']."</td>"; //not finished must implement Constructor for points value
            echo "</tr>";
    }
        echo "</tbody>";
    echo "</table>";
}

?>
