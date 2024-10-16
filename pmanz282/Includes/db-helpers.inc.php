<?php 
require_once './Includes/Config.inc.php';
require_once './Includes/db-classes.inc.php';


function displayDriver($d){
        echo "<p> Name: ".$d['forename']." ".$d['surname']."</p>";
        echo "<p> Date of Birth: ".$d['dob']."</p>";
        echo "<p> Age: ".calcAge($d['dob'])."</p>";
        echo "<p> Nationality: ".$d['nationality']."</p>";
        echo "<p> Url: ".$d['url']."</p>";
}
function calcAge($dob){ //not finished, implement age in displayDriver
    //Inspiration from: https://stackoverflow.com/questions/64003/how-do-i-use-php-to-get-the-current-year
    $currDate = date("Y");
    //Inspiration from: https://sentry.io/answers/convert-a-date-format-in-php/#:~:text=The%20Solution,in%20Unix%20time%20as%20parameters.
    //https://stackoverflow.com/questions/8529656/how-do-i-convert-a-string-to-a-number-in-php
    $DoBDate = date("Y", ((int)$dob));
    return ($currDate - $DoBDate);

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
                echo "<td>".$r['MAX_POINTS']."</td>";  //look at db-classes.inc.php L69 for debugging, name = nickname
            echo "</tr>";
    }
        echo "</tbody>";
    echo "</table>";
}

?>
