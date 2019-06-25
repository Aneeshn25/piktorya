<?php
    ob_start();
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>You are Logged out now!</title>
	<style type="text/css">
		body{
			font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
			font-size: 14px;
		}
	</style>
</head>
<body>
<h3>You are Logged out now!</h3>
<p>Thank you!</p>
<a href="login/login.php">Can't forget us?</a>
</body>
</html>

<?php
	if (!isset($_SESSION['userID'])) {
		header("location : login/login.php");
	}
	// elseif (isset($_SESSION['userID'])!="") {
	// 	header("location : home.php");
	// }
	if(isset($_GET['logout'])){
		unset($_SESSION['userID']);
		session_unset();
		session_destroy();
		exit;
	}
?>
