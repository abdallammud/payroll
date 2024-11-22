<?php
require('init.php');

if(isset($_GET['action'])) {
	if(isset($_GET['endpoint'])) {
		// Save data
		if($_GET['action'] == 'save') {
			if($_GET['endpoint'] == 'company') {
				try {
				    // Prepare data from POST request
				    $data = array(
				        'name' => $_POST['name'], 
				        'address' => $_POST['address'], 
				        'contact_phone' => $_POST['phones'], 
				        'contact_email' => $_POST['emails']
				    );

				    // Call the create method
				    $result['id'] = $companyClass->create($data);

				    // If the company is created successfully, return a success message
				    if($result['id']) {
				        $result['msg'] = 'Company created successfully';
				        $result['error'] = false;
				    } else {
				        $result['msg'] = 'Something went wrong, please try again';
				        $result['error'] = true;
				    }

				} catch (Exception $e) {
				    // Catch any exceptions from the create method and return an error message
				    $result['msg'] = 'Error: Something went wrong';
				    $result['sql_error'] = $e->getMessage(); // Get the error message from the exception
				    $result['error'] = true;
				}

				// Return the result as a JSON response (for example in an API)
				echo json_encode($result);
			} else if($_GET['endpoint'] == 'branch') {
				try {
				    // Prepare data from POST request
				    $data = array(
				        'name' => $_POST['name'], 
				        'address' => $_POST['address'], 
				        'contact_phone' => $_POST['phones'], 
				        'contact_email' => $_POST['emails']
				    );

				    // Call the create method
				    $result['id'] = $branchClass->create($data);

				    // If the branch is created successfully, return a success message
				    if($result['id']) {
				        $result['msg'] = $GLOBALS['branch_keyword']['sing'].' created successfully';
				        $result['error'] = false;
				    } else {
				        $result['msg'] = 'Something went wrong, please try again';
				        $result['error'] = true;
				    }

				} catch (Exception $e) {
				    // Catch any exceptions from the create method and return an error message
				    $result['msg'] = 'Error: Something went wrong';
				    $result['sql_error'] = $e->getMessage(); // Get the error message from the exception
				    $result['error'] = true;
				}

				// Return the result as a JSON response (for example in an API)
				echo json_encode($result);
			}

			exit();
		} 


		// Update data
		else if($_GET['action'] == 'update') {
			if($_GET['endpoint'] == 'company') {
				try {
				    // Prepare data from POST request
				    $data = array(
				    	'id' => $_POST['id'], 
				        'name' => $_POST['name'], 
				        'address' => $_POST['address'], 
				        'contact_phone' => $_POST['phones'], 
				        'contact_email' => $_POST['emails']
				    );

				    // Call the create method
				    $updated = $companyClass->update($_POST['id'], $data);

				    // If the company is created successfully, return a success message
				    if($updated) {
				        $result['msg'] = 'Company editted successfully';
				        $result['error'] = false;
				    } else {
				        $result['msg'] = 'Something went wrong, please try again';
				        $result['error'] = true;
				    }

				} catch (Exception $e) {
				    // Catch any exceptions from the create method and return an error message
				    $result['msg'] = 'Error: Something went wrong';
				    $result['sql_error'] = $e->getMessage(); // Get the error message from the exception
				    $result['error'] = true;
				}

				// Return the result as a JSON response (for example in an API)
				echo json_encode($result);
			} else if($_GET['endpoint'] == 'branch') {
				try {
				    // Prepare data from POST request
				    $data = array(
				    	'id' => $_POST['id'], 
				        'name' => $_POST['name'], 
				        'address' => $_POST['address'], 
				        'contact_phone' => $_POST['phones'], 
				        'contact_email' => $_POST['emails']
				    );

				    // Call the create method
				    $updated = $branchClass->update($_POST['id'], $data);

				    // If the company is created successfully, return a success message
				    if($updated) {
				        $result['msg'] = $GLOBALS['branch_keyword']['sing'].' editted successfully';
				        $result['error'] = false;
				    } else {
				        $result['msg'] = 'Something went wrong, please try again';
				        $result['error'] = true;
				    }

				} catch (Exception $e) {
				    // Catch any exceptions from the create method and return an error message
				    $result['msg'] = 'Error: Something went wrong';
				    $result['sql_error'] = $e->getMessage(); // Get the error message from the exception
				    $result['error'] = true;
				}

				// Return the result as a JSON response (for example in an API)
				echo json_encode($result);
			}
		}



		// Load data
		else if($_GET['action'] == 'load') {
			$role = '';
			$status = '';
			$length = isset($_POST['length']) ? (int)$_POST['length'] : 20;
			$searchParam = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
			$orderBy = 'name'; // Default sorting
			$order = 'ASC';
			$draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
			$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;

			if (isset($_POST['role'])) $role = $_POST['role'];
			if (isset($_POST['status'])) $status = $_POST['status'];

			if (isset($_POST['order']) && isset($_POST['order'][0])) {
			    $orderColumnMap = ['name', 'contact_phone', 'contact_email', 'address'];
			    $orderByIndex = (int)$_POST['order'][0]['column'];
			    $orderBy = $orderColumnMap[$orderByIndex] ?? $orderBy;
			    $order = strtoupper($_POST['order'][0]['dir']) === 'DESC' ? 'DESC' : 'ASC';
			}

			$result = [
			    'status' => 201,
			    'error' => false,
			    'data' => [],
			    'draw' => $draw,
			    'iTotalRecords' => 0,
			    'iTotalDisplayRecords' => 0,
			    'msg' => ''
			];

			if ($_GET['endpoint'] === 'company') {
			    // Base query
			    $query = "SELECT * FROM `company` WHERE `id` IS NOT NULL";

			    // Add search functionality
			    if ($searchParam) {
			        $query .= " AND (`name` LIKE '%" . escapeStr($searchParam) . "%'  OR `contact_phone` LIKE '%" . escapeStr($searchParam) . "%'  OR `contact_email` LIKE '%" . escapeStr($searchParam) . "%'  OR `address` LIKE '%" . escapeStr($searchParam) . "%')";
			    }

			    // Add ordering
			    $query .= " ORDER BY `$orderBy` $order LIMIT $start, $length";

			    // Execute query
			    $company = $GLOBALS['conn']->query($query);

			    // Count total records for pagination
			    $countQuery = "SELECT COUNT(*) as total FROM `company` WHERE `id` IS NOT NULL";
			    if ($searchParam) {
			        $countQuery .= " AND (`name` LIKE '%" . escapeStr($searchParam) . "%' OR `contact_phone` LIKE '%" . escapeStr($searchParam) . "%' OR `contact_email` LIKE '%" . escapeStr($searchParam) . "%' OR `address` LIKE '%" . escapeStr($searchParam) . "%')";
			    }

			    // Execute count query
			    $totalRecordsResult = $GLOBALS['conn']->query($countQuery);
			    $totalRecords = $totalRecordsResult->fetch_assoc()['total'];

			    if ($company->num_rows > 0) {
			        while ($row = $company->fetch_assoc()) {
			            $result['data'][] = $row;
			        }
			        $result['iTotalRecords'] = $totalRecords;
			        $result['iTotalDisplayRecords'] = $totalRecords;
			        $result['msg'] = $company->num_rows . " records were found.";
			    } else {
			        $result['msg'] = "No records found";
			    }
			} else if ($_GET['endpoint'] === 'branches') {
			    // Base query
			    $query = "SELECT * FROM `branches` WHERE `id` IS NOT NULL";

			    // Add search functionality
			    if ($searchParam) {
			        $query .= " AND (`name` LIKE '%" . escapeStr($searchParam) . "%'  OR `contact_phone` LIKE '%" . escapeStr($searchParam) . "%'  OR `contact_email` LIKE '%" . escapeStr($searchParam) . "%'  OR `address` LIKE '%" . escapeStr($searchParam) . "%')";
			    }

			    // Add ordering
			    $query .= " ORDER BY `$orderBy` $order LIMIT $start, $length";

			    // Execute query
			    $branches = $GLOBALS['conn']->query($query);

			    // Count total records for pagination
			    $countQuery = "SELECT COUNT(*) as total FROM `branches` WHERE `id` IS NOT NULL";
			    if ($searchParam) {
			        $countQuery .= " AND (`name` LIKE '%" . escapeStr($searchParam) . "%' OR `contact_phone` LIKE '%" . escapeStr($searchParam) . "%' OR `contact_email` LIKE '%" . escapeStr($searchParam) . "%' OR `address` LIKE '%" . escapeStr($searchParam) . "%')";
			    }

			    // Execute count query
			    $totalRecordsResult = $GLOBALS['conn']->query($countQuery);
			    $totalRecords = $totalRecordsResult->fetch_assoc()['total'];

			    if ($branches->num_rows > 0) {
			        while ($row = $branches->fetch_assoc()) {
			            $result['data'][] = $row;
			        }
			        $result['iTotalRecords'] = $totalRecords;
			        $result['iTotalDisplayRecords'] = $totalRecords;
			        $result['msg'] = $branches->num_rows . " records were found.";
			    } else {
			        $result['msg'] = "No records found";
			    }
			}

			echo json_encode($result);

			exit();

		} 


		// Get data
		else if($_GET['action'] == 'get') {
			if ($_GET['endpoint'] === 'company') {
				json(get_data('company', array('id' => $_POST['id'])));
			} else if ($_GET['endpoint'] === 'branch') {
				json(get_data('branches', array('id' => $_POST['id'])));
			}

			exit();
		}


		// Delete data
		else if($_GET['action'] == 'delete') {
			if ($_GET['endpoint'] === 'company') {
				try {
				    // Delete company
				    $deleted = $companyClass->delete($_POST['id']);

				    // Company deleted
				    if($deleted) {
				        $result['msg'] = 'Company record has been  deleted successfully';
				        $result['error'] = false;
				    } else {
				        $result['msg'] = 'Something went wrong, please try again';
				        $result['error'] = true;
				    }

				} catch (Exception $e) {
				    // Catch any exceptions from the create method and return an error message
				    $result['msg'] = 'Error: Something went wrong';
				    $result['sql_error'] = $e->getMessage(); // Get the error message from the exception
				    $result['error'] = true;
				}

				// Return the result as a JSON response (for example in an API)
				echo json_encode($result);
			} else if ($_GET['endpoint'] === 'branch') {
				try {
				    // Delete branchClass
				    $deleted = $branchClass->delete($_POST['id']);

				    // Company deleted
				    if($deleted) {
				        $result['msg'] = $GLOBALS['branch_keyword']['sing'].' record has been  deleted successfully';
				        $result['error'] = false;
				    } else {
				        $result['msg'] = 'Something went wrong, please try again';
				        $result['error'] = true;
				    }

				} catch (Exception $e) {
				    // Catch any exceptions from the create method and return an error message
				    $result['msg'] = 'Error: Something went wrong';
				    $result['sql_error'] = $e->getMessage(); // Get the error message from the exception
				    $result['error'] = true;
				}

				// Return the result as a JSON response (for example in an API)
				echo json_encode($result);
			}

			exit();
		}
	}
}

?>