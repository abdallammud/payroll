<?php

function getSubdomain() {
  $serverName = $_SERVER['SERVER_NAME'];
  $parts = explode(".", $serverName);

  // Check if there is a subdomain
  if (count($parts) > 2) {
    return $parts[0]; 
  } else {
    return null; // No subdomain found
  }
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

// Create a database connection
$conn = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname']);

// Check for connection errors
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Store the connection in the global scope (if necessary)
$GLOBALS['conn'] = $conn;

?>