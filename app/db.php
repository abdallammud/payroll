<?php

function getSubdomain() {
    $host = $_SERVER['HTTP_HOST']; // Example: gamaas.waxfahan.com
    $domain = 'waxfahan.com'; // Set your main domain

    // Remove the main domain from the host to get the subdomain
    if (str_ends_with($host, ".$domain")) {
        return str_replace(".$domain", '', $host);
    }

    return null; // No subdomain found
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

var_dump($config);

// Create a database connection
$conn = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname']);

var_dump($conn);
// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Store the connection in the global scope (if necessary)
$GLOBALS['conn'] = $conn;


?>
