<?php 
function escapeStr($str) {
	return $GLOBALS['conn']->real_escape_string($str);
}
function escapePostData($postData) {
    // Create an array to hold the escaped data
    $escapedData = [];

    // Loop through each key-value pair in $_POST
    foreach ($postData as $key => $value) {
        // Check if the value is an array
        if (is_array($value)) {
            // Recursively escape array values
            $escapedData[$key] = escapePostData($value);
        } else {
            // Escape the string and store it
            $escapedData[$key] = escapeStr($value);
        }
    }

    // Return the array of escaped data
    return $escapedData;
}

function get_data($table, array $fields) {
    // Ensure the table name is safe
    // $allowedTables = ['company', 'branches', 'states']; // Define allowed tables
    // if (!in_array($table, $allowedTables)) {
    //     return false; // Prevent SQL injection by checking allowed tables
    // }

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

function checkForeignKey($primaryId, $primaryName, $tables) {
    // Loop through each table and check if the foreign key exists
    foreach ($tables as $table) {
        // Query to check if the primary ID exists as a foreign key in the table
        $query = "SELECT COUNT(*) FROM `$table` WHERE `$primaryName` = '$primaryId'";

        // Prepare and execute the query
        $stmt = $GLOBALS['conn']->query($query);

        // Check if the ID exists in the table (1 or more rows)
        if ($stmt->num_rows > 0) {
            // If the ID exists in any table, return error message in JSON format and exit
            echo json_encode(['error' => true, 'msg' => 'Cannot delete, record is in use']);
            exit();
        }
    }

    // If no foreign key was found, return true
    return true;
}

// Settings
function get_setting($type) {
    $defaultValue = settingsArray();
    $setting = get_data('sys_settings', array('type' => $type));
    if(!$setting) {
        $setting = $defaultValue[$type];
    } else {
        $setting = $setting[0];
    }

    return $setting;
}

function settingsArray() {
    $defaultValue = array(
        'staff_prefix' => [
            'type' => 'staff_prefix',
            'value' => 'SB', 
            'section' => 'employees', 
            'details' => 'Staff number prefix', 
            'remarks' => ''
        ],
        'other' => [
            'type' => 'other',
            'value' => 'Testing', 
            'section' => 'employees', 
            'details' => 'Here and there', 
            'remarks' => ''
        ],
        'working_hours' => [
            'type' => 'working_hours',
            'value' => '8', 
            'section' => 'payroll', 
            'details' => 'Working hours per day', 
            'remarks' => 'required'
        ],
        'working_days' => [
            'type' => 'working_days',
            'value' => '8', 
            'section' => 'payroll', 
            'details' => 'Working days per week', 
            'remarks' => 'required'
        ],
    );

    // Retrieve settings from the database
    $dbSettings = getSettingsFromDb();

    // Merge the database settings with the default values
    // Override default values with those from the database if they exist
    foreach ($defaultValue as $key => &$setting) {
        if (isset($dbSettings[$key])) {
            $setting['value'] = $dbSettings[$key]['value']; // Override with DB value
        }
    }

    return $defaultValue;
}

function getSettingsFromDb() {
    $settings = [];
    $fromDb = $GLOBALS['settingsClass']->read_all();
    
    foreach ($fromDb as $setting) {
        // Use the type as the key for merging with default settings
        $settings[$setting['type']] = array(
            'type' => $setting['type'],
            'value' => $setting['value'],
            'section' => $setting['section'],
            'details' => $setting['details'],
            'remarks' => $setting['remarks'],
        );
    }

    return $settings;
}

function getSettingsBySection($section) {
    // Get the default settings and database settings merged together
    $settings = settingsArray();
    
    // Filter settings based on the section
    $filteredSettings = [];
    foreach ($settings as $key => $setting) {
        if ($setting['section'] === $section) {
            $filteredSettings[$key] = $setting;
        }
    }

    return $filteredSettings;
}

function sys_setting($type) {
    // Get the default settings and database settings merged together
    $settings = settingsArray();
    if (isset($settings[$type])) {
        echo $settings[$type]['value'];
    } else {
       return false;
    }
}

function select_active($table, $array = array('value' => 'id', 'text' => 'name')) {
    $options = '';
    $sql = "SELECT * FROM `$table` WHERE `status` = ?";
    $params = ['Active'];  
    $types = 's'; 

    // Execute the query
    $activeRows = $GLOBALS['branchClass']->query($sql, $params, $types);

    // Check if any rows are returned
    if (count($activeRows) > 0) {
        // Loop through active rows and build the options
        foreach ($activeRows as $row) {
            $options .= '<option value="'.$row[$array['value']].'">'.$row[$array['text']].'</option>';
        }
    } else {
        // If no active rows are found, display a "No records found" option
        $options .= '<option value="" disabled>No records found</option>';
    }

    // Echo the generated options
    echo $options;
}







?>