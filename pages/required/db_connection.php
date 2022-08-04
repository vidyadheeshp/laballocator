<?php
//ini_set('MAX_EXECUTION_TIME', -1);
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$database = 'laballocator';
$con=mysqli_connect($dbhost, $dbuser, $dbpass, $database);
	// Check connection
	 if(!$con )
	  {
	  echo "Failed to connect to MySQL: " . mysql_error();
	  }
?>