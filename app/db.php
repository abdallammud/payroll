<?php

function getSubdomain() {
    $host = $_SERVER['HTTP_HOST']; // More reliable than SERVER_NAME
    $parts = explode(".", $host);

    // Handle cases where domain has more than two levels (e.g., sub.example.co.uk)
    $numParts = count($parts);
    
    if ($numParts >= 3) {
        return $parts[0]; // First part is the subdomain
    }

    return null; // No subdomain found
}

$subdomain = getSubdomain();

// Define database credentials in an array
$dbConfig = [
   /* 'default' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'dbname' => 'asheeri'
    ],*/
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

// Create a database connection
$conn = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname']);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Store the connection in the global scope (if necessary)
$GLOBALS['conn'] = $conn;

?>
