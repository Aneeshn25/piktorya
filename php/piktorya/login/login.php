<?php 
	ob_start();
	session_start();
	if ( isset($_SESSION['userID'])!="" ) {
		header("Location: home.php");
		exit;
	}

	include 'db_connnection.php';
	$loginemail = "";
	$lecheck = "";
	$lpcheck = "";
	$logincheck = "";
	$lerror = false;
	if (isset($_POST['login'])) {
		$loginemail = $_POST['email'];
		$loginPassword = $_POST['pswd'];


		if (empty($loginemail)) {
			$lerror = TRUE;
			$lecheck = "Field is empty!";
		} elseif (!preg_match("/([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})/", $loginemail)) {
			$lerror = TRUE;
			$lecheck = "E-Mail should be \"example@example.com\"!";
		}
		if (empty($loginPassword)) {
			$lerror = TRUE;
			$lpcheck = "Field is empty!";
		}
		if ($lerror == false) {
			$conn = OpenCon();
			$query = "SELECT ID,email,user_name,pswd FROM piktorya_users WHERE email='$loginemail' AND pswd='$loginPassword'";
			$result = $conn->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			// echo $query;
			$count = $conn->affected_rows;
			// echo "this is count".$count;
			if ($count == 0) {
				$lerror = TRUE;
				$logincheck = "E-Mail or Password is wrong!";
			}elseif($count != 0){
				$_SESSION['userID'] = $row['user_name'];
				header("Location: home.php");
			}
		CloseCon();
		}
	}
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Login to piktorya</title>
	<link rel="stylesheet" href="./css/maincss.css"/>
</head>
<body>
 	<div id="wrapper">
 		<div id="header">
 			<div id="brand">
 				<img>
 				<span id="piktorya">Piktorya</span>
 			</div>
 			<nav id="navbar">
 				
 			</nav>
 		</div>
 		<div id="section">
 			<div class="form">
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" autocomplete="off" class="login-form" name="login">
					<table class="table">
						<tr>
							<td><label>E-Mail</label></td>
							<td><input type="text" name="email" autofocus placeholder="E-Mail" value="<?php echo $loginemail ?>"></td>
							<td style="color: red;"><?php echo $lecheck ?></td>
						</tr>
						<tr>
							<td><label>Password</label></td>
							<td><input type="password" name="pswd" placeholder="password"></td>
							<td style="color: red;"><?php echo $lpcheck ?></td>
						</tr>
						<tr>
							<td></td>
							<td style="color: red;"><?php echo $logincheck ?></td>
						</tr>
						<tr>
							<td></td>
							<td><button type="submit" name="login">Login</button></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td><a href="register.php" style="font-size: 0.85em">Not registered? Do it Here...</a></td>
							<td></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
 		<div id="footer">
 			<center><p id="copy">&copy; Piktorya 2018 All Rights Reserved.</p></center>
 		</div>
 	</div>
</body>
</html>