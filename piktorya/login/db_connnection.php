<?php 

function OpenCon(){
	$dbhost = '127.0.0.1';
	$dbuser = 'root';
	$dbpswd = 'zaq12wsx';
	$db = 'piktorya';

	$conn = new mysqli($dbhost, $dbuser, $dbpswd, $db);

	if ($conn->connect_error) {
		$s=$conn->error;
		die("Connection failed: $s.");
	}

	return $conn;
}

function CloseCon(){
	global $conn;
	$conn->close();
}

?>
