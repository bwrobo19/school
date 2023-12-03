	<?php require("db.php");
	
	// Process login webform.
		$username = $password = $err = "";
	
		If (!empty($_POST)) {
			$username = $_POST['username'];
			$password = $_POST['pw'];
			$sql = "SELECT * FROM tbl_user WHERE username='" . $username . "' AND password='" . md5($password) . "' LIMIT 1";
			$query = mysqli_query($con,$sql);
			$row = mysqli_num_rows($query);
			if ($row > 0) {

				while ($row = mysqli_fetch_array($query)){
						$_SESSION['name'] = $row['firstName'] . " " . $row['lastName'];
						$_SESSION['userID'] = $row['userID'];
						$_SESSION['userType'] = $row['userType'];
						header('Location: index.php');
				}
			}
			else {
				$err = "Incorrect Username or Password Entered.";
			}
		}
	?>
<! DOCTYPE HTML >
<html lan = en">
	
	<head>
		<title>Class Enrollment Portal - Login</title>
		<meta charset="utf-8">
		<meta name ="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="style.css">
	</head>
	
	<body>
	
	<?php require ("header.php") ?>
	<h1>Login</h1>

<?php
	if (!isset($_SESSION['userID']) or empty($_SESSION['userID'])) {
		echo "<b><font color='red'>" . $err . "</font></b><br><br>";
?>
		<form method="POST" action="">
			<table><tr>
				<td>Username</td>
				<td><input type='text' name='username' value ="<?php echo $username?>">
			</tr><tr>
				<td>Password</td>
				<td><input type='password' name='pw' value="">
			</tr>
			</table><Br>
		<input type="Submit" value="Submit">
		</form>
<?php	
	}
	else {
		echo "Already logged in, re-directing to home page. <br><br> <a href='index.php'>Click here to Return to Homepage.</a>";
	}
?>
	<?php require_once ("footer.php") ?>	
	</body>
</html>