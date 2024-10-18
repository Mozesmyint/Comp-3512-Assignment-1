<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index-page.css">
    <title>APIs Tester Page</title>
</head>
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
        <a href="APIsPage.php">
            APIs
        </a>
    </nav>
</header>
<body>
    <section>
        <h2>API List</h2>
        <ul>
            <li><a href="api/circuits.php">Returns all the circuits</a></li>
            <li><a href="api/circuits.php?ref=?">Returns just the specified circuit (use the circuitRef field), e.g., /api/circuits.php?ref=monaco</a></li>
            
            <li><a href="api/constructor.php">Returns all the constructors</a></li>
            <li><a href="api/constructor.php?ref=?">Returns just the specified constructor (use the constructorRef field), e.g., /api/constructors/mclaren</a></li>
            
            <li><a href="api/drivers.php">Returns all the drivers</a></li>
            <li><a href="api/drivers.php?ref=?">Returns just the specified driver (use the driverRef field), e.g., /api/drivers/hamilton</a></li>
            <li><a href="api/drivers.php?race=?">Returns the drivers within a given race, e.g., /api/drivers/race/1106</a></li>
            
            <li><a href="api/races.php?ref=?">Returns just the specified race. Don’t provide the foreign key for the circuit; instead provide the circuit name, location, and country.</a></li>
            <li><a href="api/races.php">Returns the races within the 2022 season ordered by round, e.g., /api/races/season/2022</a></li>
            
            <li><a href="api/qualifying.php?ref=?">Returns the qualifying results for the specified race, e.g., /api/qualifying/1106 Provide the same fields as with results for the foreign keys. Sort by the field position in ascending order.</a></li>
            
            <li><a href="api/results.php?ref=?">Returns the results for the specified race, e.g., /api/results/1106 Don’t provide the foreign keys for the race, driver, and constructor; instead provide the following fields: driver(driverRef, code, forename, surname), race (name, round, year, date), constructor (name, constructorRef, nationality). Sort by the field grid in ascending order (1 st place first, 2nd place second, etc).</a></li>
            <li><a href="api/results.php?driver=?">Returns all the results for a given driver, e.g., /api/results/driver/max_verstappen</a></li>
        </ul>
    </section>
</body>
</html>