<?php 
session_start();
include './login/db_connnection.php';
$userID = $_SESSION['userID'];
function GetImageExtension($imagetype){
	if (empty($imagetype)) return false;
	switch ($imagetype) {
		case 'image/bmp': return '.bmp';
			break;
		case 'image/gif': return '.gif';
			break;
		case 'image/jpeg': return '.jpg';
			break;
		case 'image/jpg': return '.jpg';
			break;
		case 'image/png': return '.png';
			break;
		
		default: return false;
			break;
	}
}
if (!empty($_FILES["uploadedimage"]["name"])) {
	$imgtype=$_FILES["uploadedimage"]["type"];
	$ext= GetImageExtension($imgtype);
	$file_name=$_FILES["uploadedimage"]["name"];
	$temp_name=$_FILES["uploadedimage"]["tmp_name"];
	$imagename=date("d-m-Y")."-".time().$ext;
	$target_path = "images/".$imagename;
	$_SESSION['trgt_path'] = $target_path;
	if ($ext == '.bmp' || $ext == '.gif' || $ext == '.jpg' || $ext == '.png') {
		$conn = OpenCon();
		$query_user = "SELECT * FROM piktorya_users WHERE user_name = '$userID'";
		$result = $conn->query($query_user);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if(move_uploaded_file($temp_name, $target_path)) {
		    $query_upload="INSERT into $userID(user_name, userID, image_location, registered_date, description) VALUES('".$row['fname']."', '".$row['user_name']."','".$target_path."',CURRENT_TIMESTAMP,'description')";
		    if ($conn->query($query_upload)) {
		    	echo "uploaded successfully";
		    } else{
		    	echo "error in $query_upload == ----> ".mysql_error();
		    }
		}else{
		   exit("Error While uploading image on the server");
		}
		CloseCon();
	}else{
		echo "Please only upload images with .bmp, .gifm, .jpeg, .png format only";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Upload</title>
	<style type="text/css">
		body{
			font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
			font-size: 14px;
		}
	</style>
</head>
<body>
		
<form method="POST" action="upload.php" enctype="multipart/form-data">
 <input type="file" name="uploadedimage">
 <input type="submit" name="submit_image" value="Upload">
</form>
<form method="POST" action="upload.php" enctype="multipart/form-data">
	<table>
		<tr>
			<td><p>Wanna check it</p></td>
			<td><button type="submit" name="view_image" value="view">Click me!</button></td>
		</tr>
<?php 
	if (isset($_POST['view_image'])) {
		echo $_SESSION['trgt_path'];
?>
		<tr>
			<td><img src="<?php echo $_SESSION['trgt_path'] ?>" alt="<?php echo "Not uploaded any picture"?>" height="250"></td>
		</tr>
<?php
	}
?>
		<tr><td><a href="login/home.php">Home</a></td></tr>
	</table>
</form>
</body>
</html>