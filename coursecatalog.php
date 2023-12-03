<! DOCTYPE HTML >
<html lan = en">
		
	<?php require ("db.php"); ?>

	<head>
		<title>Class Enrollment Portal - Course Catalog</title>
		<meta charset="utf-8">
		<meta name ="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="style.css">
		
	</head>
	
	<body>
	
	<?php require ("header.php") ?>
	
    
    
	<h1>Course Catalog</h1>

	<?php
		// Retrieve all classes from tbl_course
		$sessionID = 0;
		$sql = "SELECT * FROM tbl_course ORDER BY sessionID,courseNum";
		$query = mysqli_query($con,$sql);
		foreach ($query as $row) {
			//Display Session HEading
				if ($sessionID <> $row['sessionID']) {
					$sessionID = $row['sessionID'];
					$sql = "SELECT * from tbl_session WHERE sessionID='" . $sessionID . "' LIMIT 1";
					$result = mysqli_fetch_array(mysqli_query($con,$sql));
					echo "<br><h2>" . $result['year'] . " " . $result['description'] . "</h2>";
				}
			
			//Display Course Description and link
				echo "<a href='course.php?courseID=" . $row['courseID'] . "'>" . $row['courseNum'] . " - " . $row['title'] . "</a><br>";
		}
	?>

	   
	<?php require_once ("footer.php") ?>
	
	</body>
</html>
