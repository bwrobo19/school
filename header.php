<table width='100%' class='header_section'><tr>
	<td><img src='images/logo.png'></td>
	<td align='right' valign='top' class='header_links'>
		<a href='index.php'>Home</a> |
		<a href='contact.php'>Contact Us</a> |

<?php	
		If (isset($_SESSION['userID']) and !empty($_SESSION['userID'])) {
			
			echo "<a href='profile.php'>Edit Profile</a> | ";
			if ($_SESSION['userType'] == "administrator") {
				echo "<a href='updateClass.php'>Add New Class </a> | ";
			}
			echo "<a href='logout.php'>Log Out</a> <br>";
			echo "Welcome, " .  $_SESSION['name']; 
		}
		else {
			echo "<a href='login.php'>Login</a> | ";
			echo "<a href='register.php'>Register</a>";
		}

?>
	</td></tr></table>

</div>


<table width='100%'><tr>

	<td width="20%" valign='top' class='leftnavbar'>
    	<p><a href='coursecatalog.php' style='font-size: 12px'>View Full Course Catalog</a></p>
        
        	<?php
			
				if (isset($_SESSION['userID']) and !empty($_SESSION['userID'])) {
					if ($_SESSION['userType'] == "student") { 
        			echo "<p><b>My Classes</b></p>";
					echo "<ul>";	
        			$sql = "SELECT * FROM tbl_enrollment WHERE userID='" . $_SESSION['userID'] . "'";
					$query = mysqli_query($con,$sql);
					foreach ($query as $row) {
						$sql = "SELECT * FROM tbl_course WHERE courseID='" . $row['courseID'] . "' LIMIT 1";
						$query = mysqli_query($con,$sql);
						$course = mysqli_fetch_array($query);
						echo "<li><a href='course.php?courseID=" . $course['courseID'] . "'>". $course['courseNum'] . "- " . $course['title'] . "</a></li>";						
					}
					}
					echo "</ul>";
				}
				
				echo "<p><b>Recently Viewed Courses</b></p>";
				If (isset($_SESSION['courseID']) and !empty($_SESSION['courseID'])) {
					$courseList = explode(",",$_SESSION['courseID']);
					echo "<ul>";
					foreach ($courseList as $cID) {
						$sql = "SELECT * FROM tbl_course WHERE courseID='" . $cID . "' LIMIT 1";
						$query = mysqli_query($con,$sql);
						$row = mysqli_fetch_array($query);
						echo "<li><a href='course.php?courseID=" . $cID . "'>". $row['courseNum'] . "- " . $row['title'] . "</a></li>";
					}
					echo "</ul>";
				}
				

				
        	?>
    </td>
    <td valign='top' align='left' style='padding:10px;' class='maincontent'>
    