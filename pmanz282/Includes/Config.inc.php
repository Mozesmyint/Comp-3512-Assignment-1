<?php
define('DBHOST', 'localhost');
define('DBNAME', 'f1');
define('DBUSER', 'root');
define('DBPASS', '');

//makes the file path similar to an absolute path
//Resource from https://www.sitepoint.com/community/t/how-to-manage-paths-correctly/52866/5
define('DBCONNSTRING', 'sqlite:' . __DIR__ . '/../data/f1.db');

?>