<?php

include 'function.php';
session_start();

// Create Database Connection
	$servername = "localhost";
	$username = "administrator";
	$password = "db_admin!";
	$dbname = "school_db";
	
	// Create connection
		 $con = mysqli_connect($servername, $username, $password, $dbname);

	// Check connection
		if (!$con) {
			die("Connection failed: " . mysqli_connect_error());
		}

?>
