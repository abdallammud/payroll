<?php 
class Education extends Model {
    public function __construct() {
        parent::__construct('employee_education', 'education_id');
    }
}

$GLOBALS['educationClass']  = $educationClass = new Education();