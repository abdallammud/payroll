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

function check_exists($table, $columns, $not = array()) {
    // Ensure the connection variable is set
    if (!isset($GLOBALS['conn'])) {
        echo json_encode(['error' => true, 'msg' => 'Database connection not established']);
        exit();
    }

    $conn = $GLOBALS['conn'];

    // Build the query
    $query = "SELECT * FROM $table WHERE ";
    $conditions = [];
    foreach ($columns as $column => $value) {
        $conditions[] = "$column = '$value'";
    }
    $query .= implode(' AND ', $conditions);

    if(count($not) > 0) {
        $query .= " AND ";
        $conditions = [];
        foreach ($not as $column => $value) {
            $conditions[] = "$column <> '$value'";
        }
        $query .= implode(' AND ', $conditions);
    }

    // Execute the query
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo json_encode(['error' => true, 'msg' => 'Record already exists']);
            exit();
        } else {
            return true;
        }
    } else {
        echo json_encode(['error' => true, 'msg' => 'Database query error: ' . mysqli_error($conn)]);
        exit();
    }

    return true;
}

?>