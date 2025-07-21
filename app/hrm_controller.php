<?php
require('init.php');

$myUserId = $_SESSION['user_id'];
if(isset($_GET['action'])) {
	if(isset($_GET['endpoint'])) {
		// Save data
		if($_GET['action'] == 'save') {
			if($_GET['endpoint'] == 'employee') {
				try {
				    // Begin a transaction
				    $GLOBALS['conn']->begin_transaction();
				    $post = escapePostData($_POST);
				    $data = array();

				    $employeeData = $post;
				    unset($employeeData['degree']);
				    unset($employeeData['institution']);
				    unset($employeeData['startYear']);
				    unset($employeeData['endYear']);

				    foreach ($employeeData as $index => $value) {
				    	$data[$index] = isset($employeeData[$index]) ? $employeeData[$index]: "";
				    }

				    $data['added_by'] = $_SESSION['user_id'];

				    check_exists('employees', ['full_name' => $post['full_name'], 'email' => $post['email']]);
				    check_auth('add_employee');

				    // Call the create method for employee
				    $result['id'] = $employeeClass->create($data);

				    // If the employee was created successfully, handle salary and education
				    if ($result['id']) {
				    	// Handle staff number
				    	if(!isset($post['staff_no']) || $post['staff_no'] == return_setting('staff_prefix')) {
				    		$staff_no = return_setting('staff_prefix').$result['id'];
				    		$staffNo = array('staff_no' => $staff_no);
				    		$employeeClass->update($result['id'], $staffNo);
				    	}
				        // Education data
				        $degree 		= isset($post['degree']) ? $post['degree'] : [];
				        $institution 	= isset($post['institution']) ? $post['institution'] : [];
				        $startYear 		= isset($post['startYear']) ? $post['startYear'] : [];
				        $endYear 		= isset($post['endYear']) ? $post['endYear'] : [];
				        if (is_array($degree) && count($degree) > 0) {
				            foreach ($degree as $index => $value) {
				                $degree         = escapeStr($degree[$index]);
				                $institution    = escapeStr($institution[$index]);
				                $startYear      = escapeStr($startYear[$index]);
				                $endYear        = escapeStr($endYear[$index]);

				                $educationData = array(
				                    'employee_id'      => $result['id'],
				                    'degree'           => $degree,
				                    'institution'      => $institution,
				                    'start_year'       => $startYear,
				                    'graduation_year'  => $endYear,
				                );

				                // Create education records
				                $educationClass->create($educationData);
				            }
				        }

				        $password = password_hash($post['phone_number'], PASSWORD_DEFAULT);

				        // Create user
				        $userData = array(
					        'full_name' => $post['full_name'],
					        'phone'   	=> $post['phone_number'],
					        'email'     => $post['email'],
					        'emp_id'    => $result['id'],
					        'branch_id'         => $post['branch_id'],
					        'username'  	=> usernameFromEmail($post['email']),
					        'password'      => $password,
					        'role'     		=> 'employee',
					        'added_by' 		=> $_SESSION['user_id']
					    );

					    // $user_id = $userClass->create($userData);

				        // Commit the transaction if everything is successful
				        $GLOBALS['conn']->commit();

				        // Return success response
				        $result['msg'] = 'Employee created successfully';
				        $result['error'] = false;
				    } else {
				        // If employee creation failed, roll back the transaction
				        $GLOBALS['conn']->rollback();
				        $result['msg'] = 'Something went wrong, please try again';
				        $result['error'] = true;
				    }
				} catch (Exception $e) {
				    // If any exception occurs, rollback the transaction
				    $GLOBALS['conn']->rollback();

				    // Return error response
				    $result['msg'] = 'Error: Something went wrong';
				    $result['sql_error'] = $e->getMessage(); // Get the error message from the exception
				    $result['error'] = true;
				}

				// Return the result as a JSON response
				echo json_encode($result);
			} else if($_GET['endpoint'] == 'upload_employees') {
				try {
				    $result = ['error' => false, 'msg' => '', 'errors' => '', 'progress' => 0, 'total' => 0, 'processed' => 0];

				    check_auth('add_employee'); // Authorization check

				    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
				        $fileTmpPath = $_FILES['file']['tmp_name'];
				        $fileName = $_FILES['file']['name'];
				        $fileSize = $_FILES['file']['size'];
				        $fileType = $_FILES['file']['type'];

				        // Validate file type and size
				        if ($fileType != 'text/csv') {
				            $result['error'] = true;
				            $result['msg'] = "Invalid file type. Please upload a valid CSV file.";
				            echo json_encode($result);
				            exit();
				        }

				        // First pass: Count total rows for progress tracking
				        $totalRows = 0;
				        if (($file = fopen($fileTmpPath, 'r')) !== false) {
				            while (fgetcsv($file, 1000, ',') !== false) {
				                $totalRows++;
				            }
				            fclose($file);
				            $totalRows--; // Subtract header row
				        }

				        $result['total'] = $totalRows;

				        if (($file = fopen($fileTmpPath, 'r')) !== false) {
				            $row = 0;
				            $processedCount = 0;
				            $successCount = 0;
				            $errorCount = 0;
				            $batchSize = 50; // Process in batches for better performance
				            $batchData = [];
				            
				            // Prepare entity caches to reduce database queries
				            $entityCache = [
				                'branches' => [],
				                'states' => [],
				                'locations' => [],
				                'designations' => [],
				                'contract_types' => [],
				                'budget_codes' => []
				            ];

				            while (($line = fgetcsv($file, 1000, ',')) !== false) {
				                $row++;
				                if ($row == 1) continue; // Skip header row

				                $processedCount++;

				                // Ensure the row has the correct number of columns
				                if (count($line) < 27) {
				                    $result['errors'] .= "Skipping invalid row at line $row: ";
				                    $errorCount++;
				                    continue;
				                }

				                list(
				                    $staff_no, $full_name, $phone_number, $email, $gender,
				                    $national_id, $date_of_birth, $city, $address,
				                    $payment_bank, $payment_account, $branch, 
				                    $designation, $state, $location, $hire_date,
				                    $contract_start, $contract_end, $contract_type, $salary,
				                    $tax_exempt, $budget_code, $moh_contract, $work_days,
				                    $work_hours, $grade, $seniority
				                ) = array_map('escapeStr', $line);

				                $position = $designation;

				                // Check for missing required fields
				                if (!$full_name) {
				                    $result['errors'] .= " Missing required fields at line $row.";
				                    $errorCount++;
				                    continue;
				                }

				                // Validate and format dates
				                try {
				                    $date_of_birth = date('Y-m-d', strtotime($date_of_birth));
				                    $hire_date = date('Y-m-d', strtotime($hire_date));
				                    $contract_start = date('Y-m-d', strtotime($contract_start));
				                    $contract_end = date('Y-m-d', strtotime($contract_end));
				                } catch (Exception $dateEx) {
				                    $result['errors'] .= " Invalid date format at line $row.";
				                    $errorCount++;
				                    continue;
				                }

								// Check for duplicate employees using prepared statement for better performance
				                $check_stmt = $GLOBALS['conn']->query("SELECT employee_id FROM employees WHERE full_name = '$full_name' AND phone_number = '$phone_number'");
				                if($check_stmt && $check_stmt->num_rows > 0) {
				                    $result['errors'] .= " Record already exists at line $row.";
				                    $errorCount++;
				                    // $check_stmt->close();
				                    continue;
				                }
				                // $check_stmt->close();
				                // Use cached entities or create new ones
				                $branch_id = getCachedOrCreateEntity('branches', $branch, $myUserId, $branchClass, $entityCache);
				                $state_id = getCachedOrCreateEntity('states', $state, $myUserId, $statesClass, $entityCache);
				                $location_id = getCachedOrCreateEntity('locations', $location, $myUserId, $locationsClass, $entityCache);
				                $designation_id = getCachedOrCreateEntity('designations', $designation, $myUserId, $designationsClass, $entityCache);
				                $contract_type_id = getCachedOrCreateEntity('contract_types', $contract_type, $myUserId, $contractTypesClass, $entityCache);
				                $budget_code_id = getCachedOrCreateEntity('budget_codes', $budget_code, $myUserId, $budgetCodesClass, $entityCache);

				                // Add to batch
				                $batchData[] = [
				                    'full_name' => $full_name,
				                    'phone_number' => $phone_number,
				                    'email' => $email,
				                    'gender' => $gender,
				                    'staff_no' => $staff_no,
				                    'national_id' => $national_id,
				                    'date_of_birth' => $date_of_birth,
				                    'state_id' => $state_id,
				                    'state' => $state,
				                    'city' => $city,
				                    'address' => $address,
				                    'branch_id' => $branch_id,
				                    'branch' => $branch,
				                    'location_id' => $location_id,
				                    'location_name' => $location,
				                    'position' => $position,
				                    'designation' => $designation,
				                    'hire_date' => $hire_date,
				                    'contract_start' => $contract_start,
				                    'contract_end' => $contract_end,
				                    'work_days' => $work_days,
				                    'work_hours' => $work_hours,
				                    'contract_type' => $contract_type,
				                    'salary' => $salary,
				                    'budget_code' => $budget_code,
				                    'moh_contract' => $moh_contract,
				                    'payment_bank' => $payment_bank,
				                    'payment_account' => $payment_account,
				                    'grade' => $grade,
				                    'tax_exempt' => $tax_exempt,
				                    'seniority' => $seniority,
				                    // 'row_number' => $row
				                ];

								// var_dump($batchData);
								// exit;

				                // Process batch when it reaches the batch size
				                if (count($batchData) >= $batchSize) {
				                    $batchResult = processBatchEmployees($batchData, $employeeClass, $myUserId);
				                    $successCount += $batchResult['success'];
				                    $errorCount += $batchResult['errors'];
				                    if (!empty($batchResult['error_messages'])) {
				                        $result['errors'] .= $batchResult['error_messages'];
				                    }
				                    $batchData = [];
				                }
				            }

				            // Process remaining batch
				            if (!empty($batchData)) {
				                $batchResult = processBatchEmployees($batchData, $employeeClass, $myUserId);
				                $successCount += $batchResult['success'];
				                $errorCount += $batchResult['errors'];
				                if (!empty($batchResult['error_messages'])) {
				                    $result['errors'] .= $batchResult['error_messages'];
				                }
				            }

				            fclose($file);
				            
				            $result['processed'] = $processedCount;
				            $result['success_count'] = $successCount;
				            $result['error_count'] = $errorCount;
				            $result['progress'] = 100;
				            
				            if ($successCount > 0) {
				                $result['msg'] = "Upload completed. Successfully processed $successCount employees.";
				                if ($errorCount > 0) {
				                    $result['msg'] .= " $errorCount records had errors.";
				                }
				            } else {
				                $result['error'] = true;
				                $result['msg'] = "No employees were successfully processed.";
				            }
				        } else {
				            throw new Exception("File read error.");
				        }
				    } else {
				        throw new Exception("Please select a file.");
				    }
				} catch (Exception $e) {
				    $result['error'] = true;
				    $result['msg'] = $e->getMessage();
				    error_log($e->getMessage());
				}

				echo json_encode($result);
			} 

			exit();
		} 


		// Update data
		else if($_GET['action'] == 'update') {
			$updated_date = date('Y-m-d H:i:s');
			if($_GET['endpoint'] == 'employee') {
				try {
				    // Begin a transaction
				    $GLOBALS['conn']->begin_transaction();
				    $post = escapePostData($_POST);
				    $data = array();

				    $employeeData = $post;
				    unset($employeeData['employee_id']);
				    unset($employeeData['degree']);
				    unset($employeeData['institution']);
				    unset($employeeData['startYear']);
				    unset($employeeData['endYear']);

				    foreach ($employeeData as $index => $value) {
				    	$data[$index] = isset($employeeData[$index]) ? $employeeData[$index]: "";
				    }

				    $data['updated_by'] = $_SESSION['user_id'];
				    $data['updated_date'] = $updated_date;

				    check_exists('employees', ['full_name' => $post['full_name'], 'email' => $post['email']], ['employee_id' => $post['employee_id'], 'staff_no' => $post['staff_no']]);
				    check_auth('edit_employee');

				    // Call the create method for employee
				    $result['id'] = $employeeClass->update($post['employee_id'], $data);

				    // If the employee was created successfully, handle salary and education
				    if ($result['id']) {
				    	// Handle staff number
				    	if(!isset($post['staff_no']) || $post['staff_no'] == return_setting('staff_prefix')) {
				    		$staff_no = return_setting('staff_prefix').$result['id'];
				    		$staffNo = array('staff_no' => $staff_no);
				    		$employeeClass->update($result['id'], $staffNo);
				    	}
				        // Education data
				        $degree 		= isset($post['degree']) ? $post['degree'] : [];
				        $institution 	= isset($post['institution']) ? $post['institution'] : [];
				        $startYear 		= isset($post['startYear']) ? $post['startYear'] : [];
				        $endYear 		= isset($post['endYear']) ? $post['endYear'] : [];

				        $deleted = $educationClass->delete($post['employee_id']);

				        if (is_array($degree) && count($degree) > 0) {
				            foreach ($degree as $index => $value) {
				                $degree         = escapeStr($degree[$index]);
				                $institution    = escapeStr($institution[$index]);
				                $startYear      = escapeStr($startYear[$index]);
				                $endYear        = escapeStr($endYear[$index]);

				                $educationData = array(
				                    'employee_id'      => $post['employee_id'],
				                    'degree'           => $degree,
				                    'institution'      => $institution,
				                    'start_year'       => $startYear,
				                    'graduation_year'  => $endYear,
				                );

				                // Create education records
				                $educationClass->create($educationData);
				            }
				        }

				        $password = password_hash($post['phone_number'], PASSWORD_DEFAULT);

				        // Create user
				        $userData = array(
					        'full_name' => $post['full_name'],
					        'phone'   	=> $post['phone_number'],
					        'email'     => $post['email'],
					        'emp_id'    => $result['id'],
					        'branch_id'         => $post['branch_id'],
					        'username'  	=> usernameFromEmail($post['email']),
					        'password'      => $password,
					        'role'     		=> 'employee',
					        'updated_by' 	=> $_SESSION['user_id'],
					        'updated_date' 	=> $updated_date
					    );

					    $user = $employeeClass->get_user($post['employee_id']);
					    if($user) {
					    	$id = $user[0]['emp_id'];
					    	// $user_id = $userClass->update($id, $userData);
					    } else {
					    	// $user_id = $userClass->create($userData);
					    }

				        // Commit the transaction if everything is successful
				        $GLOBALS['conn']->commit();

				        // Return success response
				        $result['msg'] = 'Employee info updated  successfully';
				        $result['error'] = false;
				    } else {
				        // If employee creation failed, roll back the transaction
				        $GLOBALS['conn']->rollback();
				        $result['msg'] = 'Something went wrong, please try again';
				        $result['error'] = true;
				    }
				} catch (Exception $e) {
				    // If any exception occurs, rollback the transaction
				    $GLOBALS['conn']->rollback();

				    // Return error response
				    $result['msg'] = 'Error: Something went wrong';
				    $result['sql_error'] = $e->getMessage(); // Get the error message from the exception
				    $result['error'] = true;
				}

				// Return the result as a JSON response
				echo json_encode($result);
			} else if ($_GET['endpoint'] == 'employee_avatar') {
			    // Ensure user has the correct permissions
			    check_auth('edit_employee');
			    
			    $image = '';
			    $uploadOk = false;
			    $employee_id = $_POST['employee_id'];

			    // Check if a file is uploaded
			    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
			        // Get file information
			        $target_dir = "../assets/images/avatars/";
			        $file_name = basename($_FILES["avatar"]["name"]);

			        // Generate a unique file name to prevent overwriting
			        $temp = explode(".", $_FILES["avatar"]["name"]);
			        $newfilename = round(microtime(true)) . '.' . end($temp);

			        $target_file = $target_dir . $newfilename;
			        $uploadOk = true;

			        // Check if the uploaded file is a valid image
			        $check = getimagesize($_FILES["avatar"]["tmp_name"]);
			        if ($check === false) {
			            $result['error'] = true;
			            $result['msg'] = "Please select a valid image.";
			            echo json_encode($result);
			            exit();
			        }

			        // Check file size (max 5MB)
			        if ($_FILES["avatar"]["size"] > 5000000) {  // 5MB limit
			            $uploadOk = false;
			            $result['error'] = true;
			            $result['msg'] = "File is too large. Maximum size is 5MB.";
			            echo json_encode($result);
			            exit();
			        }

			        // Allow certain file formats
			        $allowed_extensions = array("jpg", "jpeg", "png", "gif", "webp");
			        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
			        if (!in_array($file_extension, $allowed_extensions)) {
			            $uploadOk = false;
			            $result['error'] = true;
			            $result['msg'] = "Invalid file type. Please upload an image (jpg, jpeg, png, gif, webp).";
			            echo json_encode($result);
			            exit();
			        }

			        // Proceed with uploading the image if everything is ok
			        if ($uploadOk) {
			            $image = $newfilename;
			            if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
			                $result['error'] = true;
			                $result['msg'] = "Something went wrong while uploading the image. Please try again.";
			                echo json_encode($result);
			                exit();
			            }
			        } else {
			            $result['error'] = true;
			            $result['msg'] = "Could not upload the file. Please try again.";
			            echo json_encode($result);
			            exit();
			        }
			    } else {
			        $result['error'] = true;
			        $result['msg'] = "No file uploaded or there was an upload error.";
			        echo json_encode($result);
			        exit();
			    }
			    $data = ['avatar' => $image];
			    $result['id'] = $employeeClass->update($_POST['employee_id'], $data);
			 	
			    // Return success response
			    $result['msg'] = 'Employee avatar updated successfully';
			    $result['error'] = false;
			    echo json_encode($result);
			}
 
		}



		// Load data
		else if($_GET['action'] == 'load') {
			$role = '';
			$status = '';
			$length = isset($_POST['length']) ? (int)$_POST['length'] : 20;
			$searchParam = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
			$orderBy = ''; // Default sorting
			$order = 'ASC';
			$draw = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
			$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;

			if (isset($_POST['role'])) $role = $_POST['role'];
			if (isset($_POST['status'])) $status = $_POST['status'];

			$result = [
			    'status' => 201,
			    'error' => false,
			    'data' => [],
			    'draw' => $draw,
			    'iTotalRecords' => 0,
			    'iTotalDisplayRecords' => 0,
			    'msg' => ''
			];

			if ($_GET['endpoint'] === 'employees') {
				if (isset($_POST['order']) && isset($_POST['order'][0])) {
				    $orderColumnMap = ['full_name', 'phone_number', 'email', 'position', 'hire_date', 'salary', 'status'];
				    $orderByIndex = (int)$_POST['order'][0]['column'];
				    $orderBy = $orderColumnMap[$orderByIndex] ?? $orderBy;
				    $order = strtoupper($_POST['order'][0]['dir']) === 'DESC' ? 'DESC' : 'ASC';
				}

				$department = $state = $location = $status = '';	
				if(isset($_POST['department'])) $department = $_POST['department'];
				if(isset($_POST['state'])) $state = $_POST['state'];
				if(isset($_POST['location'])) $location = $_POST['location'];
				if(isset($_POST['status'])) $status = $_POST['status'];


			    // Base query
			    $query = "SELECT * FROM `employees` WHERE `employee_id` IS NOT NULL";

			    // Add search functionality
			    if ($searchParam) {
			        $query .= " AND (`full_name` LIKE '%" . escapeStr($searchParam) . "%'  OR `phone_number` LIKE '%" . escapeStr($searchParam) . "%'  OR `email` LIKE '%" . escapeStr($searchParam) . "%'  OR `address` LIKE '%" . escapeStr($searchParam) . "%' OR `designation` LIKE '%" . escapeStr($searchParam) . "%' OR `position` LIKE '%" . escapeStr($searchParam) . "%')";
			    }

			    if($department) {
			    	$query .= " AND `branch_id` LIKE '$department'";
			    }

			    if($state) {
			    	$query .= " AND `state_id` LIKE '$state'";
			    }

			    if($location) {
			    	$query .= " AND `location_id` LIKE '$location'";
			    }

			    if($status) {
			    	$query .= " AND `status` LIKE '$status'";
			    } else if (!$searchParam) {
			    	$query .= " AND `status` LIKE 'Active'";
			    }

			    // Add ordering
			    $query .= " ORDER BY `$orderBy` $order LIMIT $start, $length";



			    // Execute query
			    $employees = $GLOBALS['conn']->query($query);

			    // Count total records for pagination
			    $countQuery = "SELECT COUNT(*) as total FROM `employees` WHERE `employee_id` IS NOT NULL";
			    if ($searchParam) {
			        $countQuery .= " AND (`full_name` LIKE '%" . escapeStr($searchParam) . "%' OR `phone_number` LIKE '%" . escapeStr($searchParam) . "%' OR `email` LIKE '%" . escapeStr($searchParam) . "%' OR `address` LIKE '%" . escapeStr($searchParam) . "%' OR `designation` LIKE '%" . escapeStr($searchParam) . "%' OR `position` LIKE '%" . escapeStr($searchParam) . "%')";
			    }

			    // Execute count query
			    $totalRecordsResult = $GLOBALS['conn']->query($countQuery);
			    $totalRecords = $totalRecordsResult->fetch_assoc()['total'];

			    if ($employees->num_rows > 0) {
			        while ($row = $employees->fetch_assoc()) {
			            $result['data'][] = $row;
			        }
			        $result['iTotalRecords'] = $totalRecords;
			        $result['iTotalDisplayRecords'] = $totalRecords;
			        $result['msg'] = $employees->num_rows . " records were found.";
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
			if ($_GET['endpoint'] === 'employee') {
				try {
				    // Delete company
				    check_auth('delete_employee');
				    $post = escapePostData($_POST);
				    $employeeId = $post['id'];

				    // Delete payroll info
				    $deleted = "DELETE FROM `payroll_details` WHERE `emp_id` LIKE '$employeeId'";
					if(!mysqli_query($GLOBALS["conn"], $deleted)) {
						throw new Exception('Error: ' . mysqli_error($GLOBALS["conn"]));
					}
				    // Delete leave info
				    $deleted = "DELETE FROM `employee_leave` WHERE `emp_id` LIKE '$employeeId'";
					if(!mysqli_query($GLOBALS["conn"], $deleted)) {
						throw new Exception('Error: ' . mysqli_error($GLOBALS["conn"]));
					}
				    // Delete attendance info
				    $deleted = "DELETE FROM `atten_details` WHERE `emp_id` LIKE '$employeeId'";
					if(!mysqli_query($GLOBALS["conn"], $deleted)) {
						throw new Exception('Error: ' . mysqli_error($GLOBALS["conn"]));
					}
				    // Delete timesheet info
				    $deleted = "DELETE FROM `timesheet_details` WHERE `emp_id` LIKE '$employeeId'";
					if(!mysqli_query($GLOBALS["conn"], $deleted)) {
						throw new Exception('Error: ' . mysqli_error($GLOBALS["conn"]));
					}
				    // Delete trans info
				    $deleted = "DELETE FROM `employee_transactions` WHERE `emp_id` LIKE '$employeeId'";
					if(!mysqli_query($GLOBALS["conn"], $deleted)) {
						throw new Exception('Error: ' . mysqli_error($GLOBALS["conn"]));
					}

					// Delete employee
					$deleted = "DELETE FROM `employees` WHERE `employee_id` LIKE '$employeeId'";
					if(!mysqli_query($GLOBALS["conn"], $deleted)) {
						throw new Exception('Error: ' . mysqli_error($GLOBALS["conn"]));
					}

				    // Company deleted
				    if($deleted) {
				        $result['msg'] = 'Employee info has been  deleted successfully';
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

// Helper function for cached entity creation
function getCachedOrCreateEntity($table, $name, $userId, $classInstance, &$cache) {
    if (empty($name)) return null;
    
    $cacheKey = strtolower(trim($name));
    
    // Check cache first
    if (isset($cache[$table][$cacheKey])) {
        return $cache[$table][$cacheKey];
    }
    
    // Check database
    $stmt = $GLOBALS['conn']->prepare("SELECT id FROM `$table` WHERE name = ?");
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $stmt->close();
    } else {
        $stmt->close();
        // Create new entity
        $data = ['name' => $name, 'added_by' => $userId];
        $id = $classInstance->create($data);
    }
    
    // Cache the result
    $cache[$table][$cacheKey] = $id;
    return $id;
}

// Helper function for batch processing employees
function processBatchEmployees($batchData, $employeeClass, $userId) {
    $result = ['success' => 0, 'errors' => 0, 'error_messages' => ''];
    
    try {
        $GLOBALS['conn']->begin_transaction();
        
        foreach ($batchData as $employeeData) {
            try {
                $employeeData['added_by'] = $userId;
                $empId = $employeeClass->create($employeeData);
                
                if ($empId) {
                    // Handle staff number
                    if (!isset($employeeData['staff_no']) || $employeeData['staff_no'] == return_setting('staff_prefix')) {
                        $staff_no = return_setting('staff_prefix') . $empId;
                        $employeeClass->update($empId, ['staff_no' => $staff_no]);
                    }
                    $result['success']++;
                } else {
                    $result['errors']++;
                    $result['error_messages'] .= " Failed to create employee at line {$employeeData['row_number']}.";
                }
            } catch (Exception $e) {
                $result['errors']++;
                $result['error_messages'] .= " Error at line {$employeeData['row_number']}: " . $e->getMessage();
            }
        }
        
        $GLOBALS['conn']->commit();
    } catch (Exception $e) {
        $GLOBALS['conn']->rollback();
        $result['errors'] += count($batchData);
        $result['error_messages'] .= " Batch processing failed: " . $e->getMessage();
    }
    
    return $result;
}

?>