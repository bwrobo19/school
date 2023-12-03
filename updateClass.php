	
	<?php require ("db.php"); ?>

	<?php
		
		// Pull Course Information from GET super global variable.
		
			$courseID = $prerequisiteID = $maxEnrollment = $currentEnrollment = $courseNum = $courseTitle = $courseDescription = $err = $sessionID  = "";
			 
			if ($_SERVER['REQUEST_METHOD'] == 'POST' and $_SESSION['userType']== "administrator"){
			
				
				// Return Variables from post
					$courseID = test_input($_POST['courseID']);	
					$courseNum = test_input($_POST['courseNum']);
					$courseDescription = test_input($_POST['courseDescription']);
					$courseTitle = test_input($_POST['courseTitle']);
					$maxEnrollment = test_input($_POST['maxEnrollment']);
					$prerequisiteID = test_input($_POST['prerequisiteID']);
					$sessionID = test_input($_POST['sessionID']);
				
				//Error checking of variables.
					$sql = "SELECT * FROM tbl_course WHERE courseNum ='" . $courseNum . "' AND sessionID='" . $sessionID . "' AND courseID <>'". $courseID . "'";
					$query = mysqli_query($con,$sql);
					$cnt = mysqli_num_rows($query);
					if ($cnt > 0) {
						$err = "<br>Invalid course number, another course with that course number for the selected session.";
					}
					if (!is_numeric($maxEnrollment)) {
						$err = "<br>Invalid Max Enrollment.  Must be an integer.";
					}
					
				if($_POST['action'] == "new" and $err=="") {
					//Add New Course Record
					$sql = "INSERT INTO tbl_course (courseNum,description,title,maxEnrollment,prerequisiteID,sessionID,currentEnrollment) VALUES ('" . $courseNum . "','" . $courseDescription . "','" . $courseTitle . "','" . $maxEnrollment . "','" . $prerequisiteID . "','" . $sessionID . "',0)";
					$query = mysqli_query($con, $sql);
					
					$sql = "SELECT * FROM tbl_course ORDER BY courseID DESC LIMIT 1";
					$query = mysqli_fetch_array(mysqli_query($con,$sql));
					$courseID = $query['courseID'];
					header('Location: updateClass.php?courseID=' . $courseID);
				}
				if($_POST['action'] =="edit" and $err=="") {
					//Update Course Information
					$sql = "UPDATE tbl_course SET courseNum='" . $courseNum . "',description='" . $courseDescription . "',title='" . $courseTitle . "',maxEnrollment='" . $maxEnrollment . "',prerequisiteID='" . $prerequisiteID . "',sessionID='" . $sessionID . "' WHERE courseID='" . $courseID . "'";
					$query = mysqli_query($con,$sql);
					$err = "Course updated successfully.";
				}		
			}

			if ($_SERVER['REQUEST_METHOD'] == 'GET' and isset($_GET['courseID'])){
				// Update Variables based on what is stored in MySQL database.
				$courseID = $_GET['courseID'];
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
				
			}

	?>
<html>    
	<head>
		<title>Class Enrollment Portal -  <?php echo $courseTitle ?></title>
		<meta charset="utf-8">
		<meta name ="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="style.css">
		
	</head>
	
	<body>
	
	<?php require ("header.php") ?>
	
	<h1>Add / Edit Course</h1>
    <h3><font color="#990000"> <?php echo $courseNum . " -  " . $courseTitle ?></font></h3> 
    
    <?php
	
		// Check for administrator priveleges to make course changes.
		
		if (checkPermission() == false) {
    		echo "<font color='red'>You do not have permission to access this page.</font>";
		}
		else
		{
	?>
    
    		<font color='red'><?php echo $err; ?></font>
    
    		<form method='POST' action=''>
    		<input type='hidden' name='courseID' value="<?php echo $courseID ?>">
    		<input type='hidden' name='action' value='<?php if(empty($_GET['courseID'])) { echo "new"; } else { echo "edit"; };?>'>
    
    		<table>
    			<tr>
        			<td><label for="courseNum">Course Number</td>
            		<td><input type='text' name='courseNum' value='<?php echo $courseNum ?>'></td>
        		</tr><tr>
        			<td><label for='courseTitle'>Course Title</td>
            		<td><input type='text' name='courseTitle' value ='<?php echo $courseTitle ?>'></td>
        		</tr><tr>
        			<td><label for='maxEnrollment'>Max Enrollment</td>
            		<td><input type='text' name='maxEnrollment' value='<?php echo $maxEnrollment ?>'></td>
        		</tr><tr>
        			<td><label for='prerequisiteID'>Prerequisite</td>
            		<td><select name='prerequisiteID'><option value=''> </option>
            		<?php
						$sql = "SELECT DISTINCT courseNum,title FROM tbl_course WHERE courseNum <> '" . $courseNum . "' ORDER by courseNum";
						$query = mysqli_query($con,$sql);
						foreach ($query as $row) {
							echo "<option";
							if ($row['courseNum'] . " - " . $row['title'] == $prerequisiteID) {  
								echo " selected";
							}
								
							echo ">" . $row['courseNum'] . " - " . $row['title'] . "</option>";
						}
					?>
            		</select></td>
        		</tr><tr>
       				<td><label for='sessionID'>Session</td>
            		<td><select name='sessionID'><option value=' '></option>
            		<?php
						$sql = "SELECT * FROM tbl_session";
						$query = mysqli_query($con,$sql);
						foreach ($query as $row) {
							echo "<option value='" . $row['sessionID'] . "'";
							if ($row['sessionID'] == $sessionID) {  
								echo " selected";
							}
							echo ">" . $row['year'] . " " . $row['description'] . "</option>";						
						}
					?>
				</select></td>
        	</tr><tr>
        		<td valign='top'><label for='courseDescription'>Description</td>
            	<td><textarea name='courseDescription' rows=10 cols=60><?php echo $courseDescription ?></textarea></td>
        	</tr>
    	</table>
    
    	<input type='submit' value='Submit'>
    	</form>
        
	<?php    
    	}
	?>

	<?php require_once ("footer.php") ?>
	
	</body>
</html>