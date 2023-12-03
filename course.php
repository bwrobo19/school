<! DOCTYPE HTML >
<html lan = en">
		
	<?php require ("db.php"); ?>

	<?php
	
		//Check if form has been submitted to add student to the class.
			if (!empty($_POST)) {
				if ($_POST['action'] == "addRegistration") {
					$rosterMsg = enrollStudent($_SESSION['userID'],$_POST['courseID']);
				}
				
				if ($_POST['action'] == "cancelRegistration") {
					$rosterMsg = cancelEnrollment($_POST['userID'],$_POST['courseID']);
				}
			}	
		
		// Pull Course Information from GET super global variable.
		
			$courseID = $prerequisiteID = $maxEnrollment = $currentEnrollment = $courseNum = $courseTitle = "";
			
			if (!empty($_GET)){
			
				$courseID = test_input($_GET['courseID']);
			
				$sql = "SELECT * FROM tbl_course WHERE courseID='" . $courseID . "' LIMIT 1";
				$result= mysqli_query($con,$sql);
				$row = mysqli_fetch_array($result);
			
				$courseTitle = $row['title'];
				$courseDescription = $row['description'];
				$courseNum = $row['courseNum'];
				$maxEnrollment = $row['maxEnrollment'];
				$currentEnrollment = $row['currentEnrollment'];
				$prerequisiteID = $row['prerequisiteID'];
				$sessionID = $row['sessionID'];
				
				// Update Previously Viewed Courses
					addCourseHistory($courseID);
			}
			

	?>
    
	<head>
		<title>Class Enrollment Portal -  <?php echo $courseTitle ?></title>
		<meta charset="utf-8">
		<meta name ="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="style.css">
		
	</head>
	
	<body>
	
	<?php require ("header.php") ?>

	<table width='100%'><tr><td valign='top'>
	
	<h1>Course Detail</h1>
    <h3><font color="#0000aa"><b><?php echo $courseNum . " -  " . $courseTitle ?></b></font></h3> 
    
    <?php
		
		// Course Navigation Buttons.
			if(isset($_SESSION['userID'])) {
				if ($_SESSION['userType'] == "student") {
				//Check if user is currently enrolled in class.
					$sql = "SELECT * FROM tbl_enrollment WHERE userID = '" . $_SESSION['userID'] . "' AND courseID='" . $courseID . "'";
					$query = mysqli_query($con,$sql);
					if (mysqli_num_rows($query)>0) {
						echo "<form method='POST' action=''><input type='hidden' name='courseID' value='" . $courseID . "'>";
						echo "<input type='hidden' name='action' value='cancelRegistration'>";
						echo "<input type='hidden' name='userID' value='" . $_SESSION['userID'] . "'>";
						echo "<input type='submit' value='Cancel Registration' class='courseButton'></form>";	
					}
				//Show Register for class button.
					else{
						echo "<form method='POST' action=''><input type='hidden' name='courseID' value='" . $courseID . "'>";
						echo "<input type='hidden' name='action' value='addRegistration'>";
						echo "<input type='submit' value='Register for Class' class='courseButton'></form>";						
					}
				}
				if ($_SESSION['userType'] == "administrator") {
						echo "<form method='GET' action='updateClass.php'>";
						echo "<input type='hidden' name='courseID' value='" . $courseID . "'>";
						echo "<input type='submit' value='Edit Class Information' class='courseButton'></form>";				
				}
			}
    
		//List Class Prerequisite
	 
		if (!empty($prerequisiteID)) {
    		echo "<p><b>Prerequisite:</b> " . $prerequisiteID . "</p>";
        }
		
		//List Session
		if (!empty($sessionID)) {
			$sql = "SELECT * FROM tbl_session WHERE sessionID='" . $sessionID . "'";
			$query = mysqli_query($con,$sql);
			$result = mysqli_fetch_array($query);
			echo "<p><b>Session: " . $result['year'] . " " . $result['description'] . "</b></p>";
		}	
	?>
	<p><b>Class Enrollment: </b><?php echo $currentEnrollment . " (Max - " . $maxEnrollment . ")";?></p>
    <p><?php echo $courseDescription; ?></p>    

	</td>
   
    
    <?php if(isset($_SESSION['userID']) and !empty($_SESSION['userID'])) {
	?>
    <td valign='top' class='rosterBar'>
        <?php
			if (!empty($rosterMsg)) {
				echo "<p align='center'><font color='red'><b>" . $rosterMsg . "</b></font></p>";			
			}
			echo "<h3>Current Class Roster</h3>";
			echo "<table>";
			$rosterCnt = 0;
			$sql = "SELECT * FROM tbl_enrollment WHERE courseID='" . $courseID . "' ORDER BY status,requestDate";
			$query = mysqli_query($con,$sql);
			foreach($query as $x) {
				if ($rosterCnt == $maxEnrollment) {
					echo "<tr><td colspan='2'><h3>Wait List</h3></td></tr>";
				}
			
				$sql = "SELECT * from tbl_user WHERE userID='" . $x['userID'] . "' LIMIT 1";
				$query = mysqli_query($con,$sql);
				$student = mysqli_fetch_array($query);				
				$rosterCnt++;
				
				echo "<tr><td valign='middle'>" . $student['firstName'] . " " . $student['lastName'] .  "</td>";
				If ($_SESSION['userType'] == "administrator" or $_SESSION['userID'] == $x['userID']) {
							echo "<td valign='middle'><form method='POST' action=''><input type='hidden' name='courseID' value='" . $courseID . "'>";
						echo "<input type='hidden' name='action' value='cancelRegistration'>";
						echo "<input type='hidden' name='userID' value='" . $student['userID'] . "'>";
						echo "</td><td valign='middle'><input type='submit' value=' cancel ' class='rosterButton'></form></td></tr>";	
				}
			}
	
			echo "</table>";
		}		
	?>
    
    </td></tr></table>
       
	<?php require_once ("footer.php") ?>
	
	</body>
</html>