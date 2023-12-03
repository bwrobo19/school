<?php require "db.php";
	
	session_unset();?>

<! DOCTYPE HTML >
<html lan = en">
	
	<head>
		<title>Employee Portal - Brian Wroblewski</title>
		<meta charset="utf-8">
		<meta name ="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="style.css">
	</head>
	
	<body>
	
	<?php require ("header.php") ?>

	<h1>Log Out</h1>
		
<?php

	if (Empty($_SESSION)) {
		
		echo "User successfully logged out.";
	}	

?>

	<?php require_once ("footer.php") ?>
	
	</body>
</html>
