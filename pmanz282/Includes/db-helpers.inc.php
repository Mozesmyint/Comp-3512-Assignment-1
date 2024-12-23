<?php 
require_once './Includes/Config.inc.php';
require_once './Includes/db-classes.inc.php';


function displayDriver($d){
        echo "<p> Name: ".$d['forename']." ".$d['surname']."</p>";
        echo "<p> Date of Birth: ".$d['dob']."</p>";
        echo "<p> Age: ".calcAge($d['dob'])."</p>";
        echo "<p> Nationality: ".$d['nationality']."</p>";
        echo '<p> Url: <a href="'.$d['dURL'].'">' . $d['dURL'] .'</a></p>';
}
function calcAge($dob){
    //Inspiration from: https://stackoverflow.com/questions/64003/how-do-i-use-php-to-get-the-current-year
    //$currDate = date("Y");
    //Inspiration from: https://sentry.io/answers/convert-a-date-format-in-php/#:~:text=The%20Solution,in%20Unix%20time%20as%20parameters.
    //https://stackoverflow.com/questions/8529656/how-do-i-convert-a-string-to-a-number-in-php
    //$DoBDate = date("Y", ((int)$dob));
    //return ($currDate - $DoBDate);

    //inspiration from https://www.w3resource.com/php-exercises/php-date-exercise-18.php
    $dob = new DateTime($dob);
    $now = new DateTime();

    return $now->diff($dob)->y;

}
function displayRaceResults($list){
    foreach($list as $l){
        echo "<p>".$l['name']."</p>";
    }

}
function displayAllRaceResultsDriver($results){
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
                echo "<td>".$r['points']."</td>";  //look at db-classes.inc.php L69 for debugging, name = nickname
            echo "</tr>";
    }
        echo "</tbody>";
    echo "</table>";
}
function displayALLRaceResultsConstructor($results){
    echo "<table>";
    echo "<thead>";
        echo "<tr>";
            echo "<th>Rnd</th>";
            echo "<th>Circuit</th>";
            echo "<th>Driver</th>";
            echo "<th>Position</th>";
            echo "<th>Points</th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($results as $r){
            echo "<tr>";
                echo "<td>".$r['round']."</td>";
                echo "<td>".$r['name']."</td>";
                echo "<td>".$r['forename']." ".$r['surname']."</td>";
                echo "<td>".$r['position']."</td>";
                echo "<td>".$r['points']."</td>";  //look at db-classes.inc.php L69 for debugging, name = nickname
            echo "</tr>";
    }
        echo "</tbody>";
    echo "</table>";
}
function displayConstructorDetails($d){
    echo "<p> Name: ".$d['name']."</p>";
    echo "<p> Nationality: ".$d['nationality']."</p>";
    echo '<p> Url: <a href="'.$d['url'].'">' . $d['url'] .'</a></p>';
}

?>
