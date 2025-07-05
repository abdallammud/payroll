<?php
require('./asset_config.php');
require('./assets/tcpdf/tcpdf.php');
require('./app/init.php');
if (!authenticate()) {
    header("Location: ".baseUri()."/login ");
    exit; // Important to exit to prevent further execution
}

if(isset($_GET['print'])) {
    $print = $_GET['print'];
    if($print == 'componsation') {
        // Write download csv code for componsation
        $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
        $query = "SELECT  `id`, `payroll_id`, `emp_id`, `staff_no`, `full_name`, `email`, `contract_type`, `job_title`, `month`, `required_days`, `days_worked`, `unpaid_days`, `unpaid_hours`, `bank_name`, `bank_number`, `pay_date`, `paid_by`,  `status`, `base_salary`, `allowance`, `bonus`, `commission`, (`allowance` + `bonus` + `commission`) AS earnings, (`loan` + `advance` + `deductions`) AS `total_deductions`, `tax`, (`base_salary` + (`allowance` + `bonus` + `commission`) - (`loan` + `advance` + `deductions`) - `tax`) AS net_salary FROM `payroll_details` WHERE `month` LIKE '$month'";
        $employees = $GLOBALS['conn']->query($query);
        
        $data[] = array("Staff No.", "Full name", "Base salary", "Allowance", "Bonus", "Deductions", "Tax", "Net pay");
        while ($row = $employees->fetch_assoc()) {
            $data[] = array($row['staff_no'], $row['full_name'], formatMoney($row['base_salary']), formatMoney($row['allowance']), formatMoney($row['bonus']), formatMoney($row['total_deductions']), formatMoney($row['tax']), formatMoney($row['net_salary']));
        }

        $filename = "Payroll Reports for $month.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $fp = fopen('php://output', 'w');
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

    }
}
