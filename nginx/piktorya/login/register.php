<?php 
			ob_start();
			session_start();
			include 'db_connnection.php';
			$error = TRUE;
			$lerror = false;
			$fcheck = $lcheck = $echeck = $echeck = $pcheck = $ccheck = $firstname = $lastname = $username = $email = $lecheck = $lpcheck = $loginemail = $loginPassword = $logincheck = $ucheck = $user_likes = $user_comments = "";
			
			//Creating a piktoria_users table if does not exist 
			$conn = openCon();
			$query = "CREATE TABLE IF NOT EXISTS piktorya_users(
							ID int NOT NULL AUTO_INCREMENT,
							fname varchar(50) NOT NULL,
							lname varchar(50) NOT NULL,
							user_name varchar(20) NOT NULL,
							email varchar(255) NOT NULL,
							pswd varchar(50) NOT NULL,
							PRIMARY KEY (ID)
						);";
			$conn->query($query);
			CloseCon();

			if (isset($_POST['Register'])) {
				$firstname = $_POST['fname'];
				$lastname = $_POST['lname'];
				$username = $_POST['uname'];
				$email = $_POST['email'];
				$password = $_POST['pswd'];
				$cpassword = $_POST['cpswd'];
				$error = false;
				$code = 1;

				if (empty($firstname)) {
					$error = TRUE;
					$fcheck = "Field is empty!";
				} else if (ctype_alpha($firstname)) {
					if (strlen($firstname)<=3) {
						$error = TRUE;
						$fcheck = "Must contain atleast 3 characters!";
					}
				} else{
					$error = TRUE;
					$fcheck = "Name must be filled with alphabets only!";
				}
				if (empty($lastname)) {
					$error = TRUE;
					$lcheck = "Field is empty!";
				} else if (!ctype_alpha($lastname)) {
					$error = TRUE;
					$lcheck = "Last name must be filled with characters only!";
				}
				if(empty($username)){
					$error = TRUE;
					$ucheck = "Field is empty!";
				} else{
					$conn = OpenCon();
					$query = "SELECT user_name FROM piktorya_users WHERE user_name='$username'";
					$conn->query($query);
					$count = $conn->affected_rows;
					if($count != 0) {
						$error = TRUE;
						$ucheck = "user already exists. Please provide another user name";
					}elseif(!preg_match("/(^[a-zA-Z]+[0-9]+$)/", $username)) {
						$error = TRUE;
						$randNum = rand ( 10, 999 );
						preg_match("/[a-zA-z]*/", $username, $matches, PREG_OFFSET_CAPTURE);
						$ucheck = "Must be aphanumeric. you can choose one : ".$matches[0][0].$randNum;
					}
					CloseCon();
				}
				if (empty($email)) {
					$error = TRUE;
					$echeck = "Field is empty!";
				} elseif (!preg_match("/([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})/", $email)) {
					$error = TRUE;
					$echeck = "E-Mail should be \"example@example.com\"!";
				} else{
					$conn = OpenCon();
					$query = "SELECT email FROM piktorya_users WHERE email='$email'";
					$conn->query($query);
					$count = $conn->affected_rows;
					if ($count != 0) {
						$error = TRUE;
						$echeck = "E-Mail already exists!";
					}
					CloseCon();
				}
				if (empty($password)) {
					$error = TRUE;
					$pcheck = "Field is empty!";
				}
				elseif (strlen($password)<5) {
					$error = TRUE;
					$pcheck = "Must be more than 5 characters!";
					// $password = password_hash($password, PASSWORD_DEFAULT);
				}
				if (password_verify($cpassword, $password)) {
					$error = TRUE;
					$ccheck = "Passwords do not match!";
				}
			}
		?>
<!DOCTYPE html>
<html>
	<head>
		<title>Register at piktorya</title>
		<link rel="stylesheet" href="css/maincss.css">
		<style type="text/css">
					/* Outer */
					.popup {
						width:100%;
						height:100%;
						display:none;
						position:fixed;
						top:0px;
						left:0px;
						background:rgba(0,0,0,0.75);
					}
					
					/* Inner */
					.popup-inner {
						max-width:700px;
						width:90%;
						padding:40px;
						position:absolute;
						top:50%;
						left:50%;
						-webkit-transform:translate(-50%, -50%);
						transform:translate(-50%, -50%);
						box-shadow:0px 2px 6px rgba(0,0,0,1);
						border-radius:3px;
						background:#fff;
					}
					
					/* Close Button */
					.popup-close {
						width:30px;
						height:30px;
						padding-top:4px;
						display:inline-block;
						position:absolute;
						top:0px;
						right:0px;
						transition:ease 0.25s all;
						-webkit-transform:translate(50%, -50%);
						transform:translate(50%, -50%);
						border-radius:1000px;
						background:rgba(0,0,0,0.8);
						font-family:Arial, Sans-Serif;
						font-size:20px;
						text-align:center;
						line-height:100%;
						color:#fff;
					}
					
					.popup-close:hover {
						-webkit-transform:translate(50%, -50%) rotate(180deg);
						transform:translate(50%, -50%) rotate(180deg);
						background:rgba(0,0,0,1);
						text-decoration:none;
					}
				</style>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" autocomplete="off" class="login-form">
				<table class="table">
				<tr>
					<td><label>First Name:</label></td>
					<td><input type="text" id="fname" name="fname" placeholder="First name" autofocus maxlength="20" value="<?php echo $firstname ?>"></td>
					<td style="color: red;"><?php echo $fcheck;?></td>
				</tr>
				<tr>
					<td><label>Last Name:</label></td>
					<td><input type="text" id="lname" name="lname" placeholder="Last name" maxlength="20" value="<?php echo $lastname ?>"></td>
					<td style="color: red;"><?php echo $lcheck;?></td>
				</tr>
				<tr>
					<td><label>User Name:</label></td>
					<td><input type="text" id="uname" name="uname" placeholder="User name" maxlength="20" value="<?php echo $username ?>"></td>
					<td style="color: red;"><?php echo $ucheck;?></td>
				</tr>
				<tr>
					<td><label>E-Mail:</label></td>
					<td><input type="text" id="email" name="email" placeholder="example@example.com" maxlength="30" value="<?php echo $email ?>"></td>
					<td style="color: red;"><?php echo $echeck;?></td>
				</tr>
				<tr>
					<td><label>Password:</label></td>
					<td><input type="Password" id="pswd" placeholder="Password" name="pswd"></td>
					<td style="color: red;"><?php echo $pcheck;?></td>
				</tr>
				<tr>
					<td><label>Confirm Password:</label></td>
					<td><input type="Password" id="cpswd" placeholder="Enter again" name="cpswd"></td>
					<td style="color: red;"><?php echo $ccheck;?></td>
				</tr>
				<tr>
					<td></td>
					<td><button class="btn" data-popup-open="popup" type="submit" id="reg" name="Register" value="Register">Register</button></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>Already a user?<a href="login.php" style="font-size: 1em">Login</a></td>
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
		
		<?php
				//login after registration
			if (isset($_POST['Login'])) {
				$loginemail = $_POST['loginemail'];
				$loginPassword = $_POST['loginPassword'];
				if (empty($loginemail)) {
					$lerror = TRUE;
					$code = 2;
					$lecheck = "Field is empty!";
				} elseif (!preg_match("/([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})/", $loginemail)) {
					$lerror = TRUE;
					$code = 2;
					$lecheck = "E-Mail should be \"example@example.com\"!";
				}
				if (empty($loginPassword)) {
					$lerror = TRUE;
					$code = 2;
					$lpcheck = "Field is empty!";
				}
				if ($lerror == false) {
					$conn = OpenCon();
					$query = "SELECT email,pswd FROM piktorya_users WHERE email='$loginemail' AND pswd='$loginPassword'";
					$conn->query($query);
					// echo $query;
					$count = $conn->affected_rows;
					// echo "this is count".$count;
					if ($count == 0) {
						$lerror = TRUE;
						$code = 2;
						$logincheck = "E-Mail or Password is wrong!";
					}elseif($count != 0){
						header("Location: home.php");
					}
				CloseCon();
				}
			}
		?>
		<div class="popup" data-popup="popup">
			<div class="popup-inner">
			<?php if ($code == 1) {
					echo "<h2>Thank You for registering!</h2>";
				}else{
					echo "<h2>Oops! Try again...</h2>";
				}
			?>
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" autocomplete="off">
					<table>
						<tr>
							<td><label>E-Mail</label></td>
							<td><input type="text" name="loginemail" placeholder="Enter your E-Mail ID" value="<?php echo $loginemail ?>" autofocus></td>
							<td style="color: red;"><?php echo $lecheck?></td>
						</tr>
						<tr>
							<td><label>Password</label></td>
							<td><input type="Password" name="loginPassword" placeholder="Enter your Password"></td>
							<td style="color: red;"><?php echo $lpcheck?></td>
						</tr>
						<tr>
							<td></td>
							<td style="color: red;"><?php echo $logincheck?></td>
						</tr>
						<tr>	
							<td><button class="btn" data-popup-open="popup" type="submit" name="Login" value="login">Login</button></td>
						</tr>
					</table>
				</form>
				<a class="popup-close" data-popup-close="popup" href="#">x</a>
			</div>
		</div>
		<!-- <div class="popup" data-popup="logged_in">
		    <div class="popup-inner">
		        <h2>Logged in!!!</h2>
		        <p><a data-popup-close="logged_in" href="#">Close</a></p>
		        <a class="popup-close" data-popup-close="logged_in" href="#">x</a>
		    </div>
		</div> -->
		<script>
			//----- OPEN
			$('button').hover(function()  {
					obj = this;
				// obj = this;
				// alert("you clicked");
				value();
				// console.log(jQuery(this));
				// console.log("obj test");
				});
			$('p').on('click',function()  {
				value();
			});
			function value(){
				console.log(obj);
			}
			function popupopen(){
			var targeted_popup_class = jQuery('button.btn').attr('data-popup-open');
			$('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);
			}
				// popupopen();
			//----- CLOSE
			$('[data-popup-close]').on('click', function(e) {
				var targeted_popup_class = jQuery(this).attr('data-popup-close');
				$('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);
			
				e.preventDefault();
			});
		</script>
		<?php
		//registration
			if ($error == false){

				$conn = OpenCon();

				$query = "CREATE TABLE IF NOT EXISTS $username(
								post_id int NOT NULL AUTO_INCREMENT,
								user_name varchar(20) not null,
								userID varchar(50),
								image_location varchar(256) not null,
								registered_date timestamp not null DEFAULT CURRENT_TIMESTAMP,
								description varchar(256) DEFAULT null,
								PRIMARY key(post_id)
								);";
				if(!$conn->query($query)) {
					echo("Multi query failed: (" . $conn->errno . ") " . $conn->error );
				}

				$user_likes = $username.'_likes';
				$query = "CREATE TABLE IF NOT EXISTS $user_likes(
								serial_no int not null AUTO_INCREMENT,
								user_name varchar(50),
								userID varchar(50),
								post_id int,
								status char(10) DEFAULT null,
								PRIMARY KEY(serial_no)
							);";
				if(!$conn->query($query)) {
					echo("Multi query failed: (" . $conn->errno . ") " . $conn->error );
				}

				$user_comments = $username.'_comments';
				$query = "CREATE TABLE IF NOT EXISTS $user_comments(
								serial_no int not null AUTO_INCREMENT,
								user_name varchar(50),
								userID varchar(50),
								post_id int,
								user_comment varchar(256),
								PRIMARY KEY(serial_no)
							);";
				if(!$conn->query($query)) {
					echo("Multi query failed: (" . $conn->errno . ") " . $conn->error );
				}

				$query = "INSERT INTO piktorya_users(fname, lname,user_name, email, pswd) VALUES ('$firstname','$lastname','$username','$email','$password')";
				if ($conn->query($query) === TRUE) {
					echo "<script> popupopen(); </script>";
					}else{
						echo "Unsuccessful querry: ".$query."<br>". $conn->error;
					}
					CloseCon();
					$error = TRUE;
				}
				//second popup
				if ($lerror == TRUE) {
					echo "<script> popupopen(); </script>";
					$lerror = TRUE;
				}else{
					$conn = OpenCon();
					$query = "SELECT * FROM piktorya_users WHERE email='$loginemail'";
					$result = $conn->query($query);
					$row = $result->fetch_array(MYSQLI_ASSOC);
					$_SESSION['userID'] = $row['user_name'];
					CloseCon();
				}
			?>
			
	</body>
</html>