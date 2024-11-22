<?php 
/*$servername = "localhost";
$username   = "root";
$password   = "";
$db = "asheeri";
*/
$servername = "localhost";
$username   = "u264673343_payroll";
$password   = "Hooyomcn94@";
$db = "u264673343_payroll";

$GLOBALS['conn'] = $conn = new mysqli($servername, $username, $password, $db);

if($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}





?>
