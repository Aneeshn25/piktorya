<?php 

function OpenCon(){
	$dbhost = 'localhost';
	$dbuser = 'aneeshn2_aneesh';
	$dbpswd = 'zaq12wsx';
	$db = 'aneeshn2_piktorya';

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