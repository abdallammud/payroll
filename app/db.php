<?php 

/*$servername = "localhost";
$username   = "root";
$password   = "";
$db = "asheeri";*/

function getSubdomain() {
	// Get the current server name (e.g., "subdomain.example.com")
	$server_name = $_SERVER['SERVER_NAME'];

	// Extract the subdomain 
	$parts = explode(".", $server_name);
	$subdomain = array_shift($parts); // Remove and return the first element 

	// Sanitize subdomain (optional)
	return $sanitized_subdomain = preg_replace('/[^a-z0-9\-]/i', '', $subdomain); 
}

$subdomain = getSubdomain();


// Define database credentials in an array
$dbConfig = [
    'default' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'dbname' => 'asheeri'
    ],
    'gamaas' => [
        'host' => 'localhost', 
        'user' => 'u138037914_gamaas',
        'password' => ';9lZgpiFqxOA',
        'dbname' => 'u138037914_gamaas'
    ],
    'marsuus' => [
        'host' => 'localhost', 
        'user' => 'u138037914_marsuus', 
        'password' => '/0zVtAUu', 
        'dbname' => 'u138037914_marsuus'
    ]
];

// Get the database configuration based on the subdomain
$config = $dbConfig[$subdomain] ?? $dbConfig['default'];

$servername = $config["host"];
$username   = $config["user"];
$password   = $config["password"];
$db 		= $config["dbname"];

$GLOBALS['conn'] = $conn = new mysqli($servername, $username, $password, $db);

if($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}





?>
