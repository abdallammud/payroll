<?php 
// $servername = "localhost";
// $username   = "root";
// $password   = "";
// $db = "asheeri";

$servername = "localhost";
$username   = "u138037914_payroll";
$password   = "Hooyomcn94#";
$db = "u138037914_payroll";

$GLOBALS['conn'] = $conn = new mysqli($servername, $username, $password, $db);

if($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}


// Added hrm


?>
