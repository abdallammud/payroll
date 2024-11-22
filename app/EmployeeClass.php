<?php 
class Employee extends Model {
    public function __construct() {
        parent::__construct('employees', 'employee_id');
    }
}

$GLOBALS['employeeClass']   = $employeeClass = new Employee();