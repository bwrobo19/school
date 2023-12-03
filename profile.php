<?php require "db.php"; 

	// define variables and set to empty values
		$username = $firstName = $lastName = $email = $address = $password1 = $password2 = $phone = $ssn = $err = "";
		$city = $state = $zip = "";
	
	if (!empty($_POST)){
		
		// Process Employee Information Change.
			
		// Grab values from POST information
		  $email= test_input($_POST["email"]);
		  $firstName = test_input($_POST["firstName"]);
		  $lastName = test_input($_POST["lastName"]);
		  $address = test_input($_POST["address"]);
		  $city = test_input($_POST['city']);
		  $state = test_input($_POST['state']);
		  $zip = test_input($_POST['zip']);
		  $password1 = test_input($_POST["password1"]);
		  $password2 = test_input($_POST["password2"]);
		  $phone = test_input($_POST["phone"]);
		  $ssn = str_replace("-","",test_input($_POST["ssn"]));

		// Grab the old values stored in the database to compare to the new entered values.
			$sql = "SELECT * FROM tbl_user WHERE userID='" . $_SESSION['userID'] . "'";
			$result = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($result);
			
		// Confirm Values are filled and in the proper format.
	    	if (Empty($firstName) or Empty($lastName) or Empty($email) or Empty($address) or Empty($phone) or Empty($ssn)) {
			    $err = "All fields must be filled out before submitting.";
	    	}
	    	if ($password1 != $password2 and !empty($password1) and !empty($password2)) {
			    $err = $err . "<br>Passwords do not match.";
		    }
		    if (!filter_var($phone, FILTER_VALIDATE_INT)) {
    			$err = $err . "<br>Invalid phone number format.";
		    }
		    if (!is_numeric($ssn)) {
    			$err = $err . "<br>Invalid Social Security Number.";
		    }
		    if (!is_numeric($zip)) {
    			$err = $err . "<br>Invalid Zip Code.";
		    }			
			if (!Empty($email) && $row['email'] != $email) {
				$sql = "SELECT * FROM tbl_user WHERE email = '" . $email . "'";
				$result = mysqli_query($con,$sql);
				$row = mysqli_num_rows($result);
				If ($row >0) {
						$err = $err . "<br>Email already in use, please select another email.";
				}
			}			
			
			// If there is no error messages, then all the information submitted can be entered into the database.
			if ($err == "") {
					$sql = "UPDATE tbl_user SET firstName='" . $firstName . "',lastName='" . $lastName . "',email='" . $email . "',address='" . $address . "',phone='" . $phone . "',SSN='" . $ssn . "',city='" . $city . "',state='" . $state . "',zip='" . $zip . "' WHERE userID='" . $_SESSION['userID'] . "'";
					$result = mysqli_query($con,$sql);
					if ($row['password'] <> md5($password1) and !empty($password1) and !empty($password2)) {
							$sql = "UPDATE tbl_user SET password='" . md5($password1) . "' WHERE userID='" . $_SESSION['userID'] . "'";
							$result = mysqli_query($con,$sql);
					}
					
					//Update session values.
						$_SESSION['name'] = $row['firstName'] . " " . $row['lastName'];
						$_SESSION['userID'] = $row['userID'];
						$_SESSION['userType'] = $row['userType'];
						
				$err = "Fields updated successfully.";
			}
	}
	elseif (!isset($_SESSION['userID']) or empty($_SESSION['userID'])) {
		$err = "No user logged in, please log in.";
	}
	else {
		$sql = "SELECT * FROM tbl_user WHERE userID='" . $_SESSION['userID'] . "'";
		$result = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($result);
	  
	  // Grab values from database information
		  $firstName = $row['firstName'];
		  $lastName = $row['lastName'];
		  $address = $row['address'];
		  $email = $row['email'];
		  $phone = $row['phone'];
		  $ssn = $row['ssn'];
		  $city = $row['city'];
		  $state = $row['state'];
		  $zip = $row['zip'];
		  
	}
?>

<! DOCTYPE HTML >
<html lan = en">
	
	<head>
		<title>Class Enrollment Portal - Edit Profile</title>
		<meta charset="utf-8">
		<meta name ="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="style.css">
	</head>
	
	<body>
	
<?php require ("header.php") ?>

	<h1>Edit Profile Information</h1>
	<font color="red"><b><?php echo $err ?></font>
	
<?php
	
		if (isset($_SESSION['userID']) and !empty($_SESSION['userID'])) {
?>			

	<!--- Begin student Information Form -->
		<form action="" method="POST">
			<table><tr>
				<td><label for="fname">First Name:</label></td>
				<td><input type="text" id="firstName" name="firstName" value="<?php echo $firstName?>"></td>
			</tr><tr>
				<td><label for="lname">Last Name:</label></td>
				<td><input type="text" id="lastName" name="lastName" value="<?php echo $lastName?>"></td>
			</tr>
            </tr><tr>
				<td><label for="email">E-mail:</label></td>
				<td><input type="text" id="email" name="email" value="<?php echo $email?>"></td>
			</tr>
			<tr height = "10"></tr>
            <tr>
				<td><label for="password1">Password:</label></td>
				<td><input type="password" id="password1" name="password1" value=""></td>
			</tr><tr>
				<td><label for="password2">Confirm Password:</label></td>
				<td><input type="password" id="password2" name="password2"  value=""></td>
			</tr>
			<tr height = "10"></tr>
			<tr>
				<td><label for="address">Address:</label></td>
				<td><input type="text" id="address" name="address" value="<?php echo $address?>"></td>
            </tr><tr>
				<td><label for="city">City:</label></td>
				<td><input type="text" id="city" name="city" value="<?php echo $city?>"></td>
            </tr><tr>
				<td><label for="state">State:</label></td>
				<td><input type="text" id="state" name="state" value="<?php echo $state?>"></td>
			</tr><tr>
            </tr><tr>
				<td><label for="zip">Zip:</label></td>
				<td><input type="text" id="zip" name="zip" value="<?php echo $zip?>"></td>
			</tr><tr>
				<td><label for="phone">Phone:</label></td>
				<td><input type="text" id="phone" name="phone" value="<?php echo $phone?>"></td>
            </tr><tr>
				<td><label for="ssn">Social Security Number:</label></td>
				<td><input type="text" id="ssn" name="ssn" value="<?php echo $ssn?>"></td>
			</tr>
			</tr></table>
			<br>
			<input type="submit" value="Submit">
		</form>


<?php
	
		}
?>
	
	
	
	
	<?php require_once ("footer.php") ?>
	
	</body>
</html>