<?php 
function escapeStr($str) {
	return $GLOBALS['conn']->real_escape_string($str);
}

function get_data($table, array $fields) {
    // Ensure the table name is safe
    $allowedTables = ['company', 'branches']; // Define allowed tables
    if (!in_array($table, $allowedTables)) {
        return false; // Prevent SQL injection by checking allowed tables
    }

    // Start building the query
    $query = "SELECT * FROM `$table` WHERE ";
    $conditions = [];
    $params = [];

    // Build conditions based on the provided fields
    foreach ($fields as $key => $value) {
        $conditions[] = "`$key` = ?";
        $params[] = $value; // Store the value for binding
    }

    // Combine conditions into the query
    $query .= implode(' AND ', $conditions);

    // Prepare the statement
    if ($stmt = $GLOBALS['conn']->prepare($query)) {
        // Bind parameters dynamically
        $types = str_repeat('s', count($params)); // Assuming all values are strings; adjust if needed
        $stmt->bind_param($types, ...$params);
        
        // Execute the query
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        // Fetch data
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    return false; // Return false if no records are found or if an error occurs
}

?>