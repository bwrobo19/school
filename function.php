<?php

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	function checkPermission() {
		if (isset($_SESSION['userType']) And !empty($_SESSION['userType'])) {
			if ($_SESSION['userType'] == "administrator") {
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	function cancelEnrollment($userID,$courseID) {
	
		// GEt the enrollment status for the current user.
		$sql = "SELECT * FROM tbl_enrollment WHERE courseID='" . $courseID . "' AND userID='" . $userID . "'";
		$query= mysqli_query($GLOBALS['con'],$sql);
		$e = mysqli_fetch_array($query);
	
		// Remove student from Enrollment Database.
		$sql = "DELETE FROM tbl_enrollment WHERE courseID = '" . $courseID . "' AND userID='" . $userID . "'";
		$query = mysqli_query($GLOBALS['con'],$sql);
		
		//Get student Information
		$sql = "SELECT * FROM tbl_user WHERE userID = '" . $userID . "' LIMIT 1";
		$query = mysqli_query($GLOBALS['con'],$sql);
		$q = mysqli_fetch_array($query);
		$msg = "Student - " . $q['firstName'] . " " . $q['lastName'] . " has cancelled the enrollment to the class.";
		
		// Check the enrollment database for any student enrolled whose status is not active.
		$sql = "SELECT * FROM tbl_enrollment WHERE courseID = '" . $courseID . "' AND status='wait' ORDER BY requestDate DESC LIMIT 1";
		$query = mysqli_query($GLOBALS['con'],$sql);
		$result = mysqli_fetch_array($query);
		
		if (mysqli_num_rows($query) > 0 and $e['status'] <> "wait") {
			
			//Enrolls the waitlist student into the class.
			$sql = "UPDATE tbl_enrollment SET status='active' WHERE courseID = '" . $courseID . "' AND userID='" . $result['userID'] . "'";
			$query = mysqli_query($GLOBALS['con'],$sql);
			$sql = "SELECT * FROM tbl_user WHERE userID='" . $result['userID'] . "'";
			$query = mysqli_query($GLOBALS['con'],$sql);
			$r = mysqli_fetch_array($query);
			$msg = $msg . chr(10) . $r['firstName'] . " " . $r['lastName'] . " has been added to the class.";
		}
		elseif ($e['status'] <> "wait") {
			$sql = "UPDATE tbl_course SET currentEnrollment = currentEnrollment-1 WHERE courseID='" . $courseID . "'";
			$query = mysqli_query($GLOBALS['con'],$sql);
		}
		return $msg;
	}
	
	function enrollStudent($userID,$courseID) {
	
		// Confirm student is not already enrolled in class.
			$sql = "SELECT * FROM tbl_enrollment WHERE userID='" . $userID . "' AND courseID='" . $courseID . "'";
			$query = mysqli_query($GLOBALS['con'],$sql);
			If (mysqli_num_rows($query) > 0) {
				$msg = "Student already enrolled in class.";
			}
			else
			{

				// Check the course database to see if the class is filled.
					$sql = "SELECT * from tbl_course WHERE courseID='" . $courseID . "' LIMIT 1";
					$query = mysqli_query($GLOBALS['con'],$sql);
					$result = mysqli_fetch_array($query);
					$maxEnrollment = $result['maxEnrollment'];
					If ($result['currentEnrollment'] >= $maxEnrollment) {
						$status = "wait";
					}
					else
					{
						$status = "active";
					}
		
				// Add the student to the enrollment list database.
					$sql = "INSERT INTO tbl_enrollment (userID,courseID,status,requestDate) VALUES ('" . $userID . "','" . $courseID . "','" . $status . "',NOW())";
					$query = mysqli_query($GLOBALS['con'],$sql);
		
				//Update the current enrollment number for the class.
					$sql = "SELECT * FROM tbl_enrollment WHERE courseID = '" . $courseID . "'";
					$query = mysqli_query($GLOBALS['con'],$sql);
					if (mysqli_num_rows($query) >= $maxEnrollment) {
						$currentEnrollment = $maxEnrollment;
					}
					else 
					{
						$currentEnrollment = mysqli_num_rows($query);
					}
					$sql = "UPDATE tbl_course SET currentEnrollment='" . $currentEnrollment . "' WHERE courseID='" . $courseID . "'";
					$query = mysqli_query($GLOBALS['con'],$sql);
		
					$msg = "You have been successfully added to this class.  Thank you.";
			}		
		return $msg;
		
	}

	function addCourseHistory($courseID) {
			// Add courseID to previously viewed classes.			
			if (!empty($_GET)) {
				if (empty($_SESSION['courseID'])) {
					$_SESSION['courseID'] = $courseID;
				}
				else
				{
					$courseList = explode(",",$_SESSION['courseID']);
					$_SESSION['courseID'] = $courseID;
					$x=0;
					$cnt = 1;
					while ($cnt <= 5 and $cnt<=count($courseList)) {
						if (intval($courseList[$x]) <> intval($courseID)) {
							$_SESSION['courseID'] .= "," . $courseList[$x];	
						}
						$x++;
						$cnt++;
					}
				}
			}
	}

?>