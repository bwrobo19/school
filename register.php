<! DOCTYPE HTML >
<html lan = en">

<?php

	include ("db.php");
	
	// define variables and set to empty values
		$username = $firstName = $lastName = $email = $address = $password1 = $password2 = $phone = $salary = "";
		$ssn = $err = $city = $state = $zip = "";

	if (!empty($_POST)) {

	  // Grab values from POST information
	  	  $username = test_input($_POST['username']);
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
	  
	  // Confirm Values are filled and in the proper format.
	    	if (Empty($firstName) or Empty($lastName) or Empty($email) or Empty($address) or Empty($password1) or Empty($password2) or Empty($ssn)) {
			    $err = "<br>All fields must be filled out before submitting.";
	    	}
	    	if ($password1 != $password2) {
			    $err = $err . "<br> Passwords do not match.";
		    }
		    if (!is_numeric($zip)) {
    			$err = $err . "<br> Invalid zip code, only enter 5-digit zip.";
		    }
		    if (!is_numeric($ssn)) {
    			$err = $err . "<br> Invalid Social Security Number.";
		    }
			If (!Empty($email)) {
				$sql = "SELECT * FROM tbl_user WHERE email = '" . $email . "'";
				$result = mysqli_query($con,$sql);
				$row = mysqli_num_rows($result);
				If ($row >0) {
						$err = $err . "<br> E-Mail Address already in use, please select another email.";
				}
			}
			If (!Empty($username)) {
				$sql = "SELECT * FROM tbl_user WHERE username = '" . $username . "'";
				$result = mysqli_query($con,$sql);
				$row = mysqli_num_rows($result);
				If ($row >0) {
						$err = $err . "<br> Username already in use, please select another username.";
				}
			}
		
		// If there are no errors, the user information is added to the database.
		
		    if (Empty($err)) {
		        $sql = "INSERT INTO tbl_user (username,email,firstName,lastName,address,password,phone,ssn,city,state,zip,userType) VALUES ('". $username ."','". $email . "','" . $firstName . "','" . $lastName . "','" . $address . "','" . md5($password1) . "'," . $phone . "," . $ssn . ",'" . $city . "','" . $state . "'," . $zip . ",'student')";
		        mysqli_query($con, $sql);
		        
		    }
    }



?>

	
	<head>
		<title>Employee Portal New User Registration - Brian Wroblewski</title>
		<meta charset="utf-8">
		<meta name ="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="style.css">
	</head>
	
	<body>
	
	<?php require ("header.php") ?>
	
	<h1>New User Registration</h1>
	
	<?php
	
	if (!empty($err) or empty($_POST)) {

	// Display UserForm.
	?>

	<div align="left"><font color="red"><b><?php echo $err ?></font></div>
	
	<!--- Begin Registration Form -->
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
				<td><label for="username">Username:</label></td>
				<td><input type="text" id="username" name="username" value="<?php echo $username?>"></td>
			</tr><tr>
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
	} else {
		echo "New User Successfully Added. (" . $firstName . " " . $lastName . ")";
	}
	
	require_once ("footer.php") 
?>
	
	</body>
</html>