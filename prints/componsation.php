<?php
$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
// Extend the TCPDF class to create a custom footer
class MYPDF extends TCPDF {
    // Page footer
    public function Footer() {
        // Set position to 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Create new PDF document
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Author Name');
$pdf->SetTitle('Componsation and Benfits Reports');
$pdf->SetSubject('Componsation and Benfits Reports');

$primary_color = return_setting('primary_color');
$secondary_color = return_setting('secondary_color');

$primary_color = explode(",", hexToRgb($primary_color));
$secondary_color =explode(",", hexToRgb($secondary_color));

// Disable default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetMargins(10, 10, 10); // left, top, right
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(20);

$pdf->SetAutoPageBreak(TRUE, 15);

// Add a page
$pdf->AddPage();

$pdf->SetFont('aefurat', '', 12);
// $pdf->Image('./assets/images/banner.png', 0, 0, 280, 40); // Adjust size as needed
$pdf->Image('./assets/images/logo.png', 10, 10, 30);
// Set header rectangle
$pdf->SetFillColor(80, 184, 72);
$pdf->SetDrawColor(80, 184, 72);

$y = 10;
$companyInfo = get_data('company', ['id' => 1])[0];
$pdf->SetTextColor(80, 184, 72);
$pdf->SetXY(70, $y);
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(130, 7, strtoupper($companyInfo['name']), 0, 0, 'R');
$pdf->SetTextColor(000, 000, 000);

$pdf->SetFont('helvetica', 'B', 10);

$y += 8;
$pdf->SetXY(70, $y);
$pdf->Cell(130, 7, $companyInfo['contact_phone'], 0, 0, 'R');

$y += 5;
$pdf->SetXY(70, $y);
$pdf->Cell(130, 7, $companyInfo['contact_email'], 0, 0, 'R');

$pdf->SetFont('helvetica', 'B', 12);
$y += 6;
$pdf->SetXY(70, $y);
$pdf->Cell(130, 7, strtoupper("Payroll Reports"), 0, 0, 'R');

$pdf->SetFont('helvetica', '', 10);
$y += 6;
$pdf->SetXY(70, $y);
$pdf->Cell(130, 7, date('F Y', strtotime($month)), 0, 0, 'R');
$y += 6;
$pdf->SetXY(70, $y);
$pdf->Cell(130, 7, "Print date " . date("F d Y h:i:s A"), 0, 0, 'R');

$y += 10;

$pdf->Rect(10, $y, 190, 0.2);

$pdf->SetDrawColor(0, 0, 0);
$y += 5;
// Table Header
$pdf->SetFont('aefurat', 'B', 10);
$pdf->SetXY(10, $y);
$pdf->Cell(15, 7, "Staff No.", 1, 0, 'L', true);
$pdf->Cell(60, 7, "Full name", 1, 0, 'L', true);
$pdf->Cell(20, 7, "Base salary", 1, 0, 'L', true);
$pdf->Cell(20, 7, "Allowance", 1, 0, 'L', true);
$pdf->Cell(20, 7, "Bonus", 1, 0, 'L', true);
$pdf->Cell(20, 7, "Deductions", 1, 0, 'L', true);
$pdf->Cell(20, 7, "Tax", 1, 0, 'L', true);
$pdf->Cell(20, 7, "Net pay", 1, 1, 'L', true);


$y += 7;

$query = "SELECT  `id`, `payroll_id`, `emp_id`, `staff_no`, `full_name`, `email`, `contract_type`, `job_title`, `month`, `required_days`, `days_worked`, `unpaid_days`, `unpaid_hours`, `bank_name`, `bank_number`, `pay_date`, `paid_by`,  `status`, `base_salary`, `allowance`, `bonus`, `commission`, (`allowance` + `bonus` + `commission`) AS earnings, (`loan` + `advance` + `deductions`) AS `total_deductions`, `tax`, (`base_salary` + (`allowance` + `bonus` + `commission`) - (`loan` + `advance` + `deductions`) - `tax`) AS net_salary FROM `payroll_details` WHERE `month` LIKE '$month'";
$employees = $GLOBALS['conn']->query($query);
$num = 1;

if ($employees->num_rows > 0) {
    while ($row = $employees->fetch_assoc()) {
        $emp_id = $row['emp_id'];
        $staff_no = $row['staff_no'];
        $full_name = $row['full_name'];
        $base_salary = $row['base_salary'];
        $allowance = $row['allowance'];
        $bonus = $row['bonus'];
        $commission = $row['commission'];
        $tax = $row['tax'];
        $earnings = $row['earnings'];
        $total_deductions = $row['total_deductions'];
        $net_salary = $row['net_salary'];

        $employeeInfo = get_data('employees', ['employee_id' => $emp_id]);
        $taxPercentage = 0;
        if($employeeInfo) {
            $employeeInfo = $employeeInfo[0];
            $state_id = $employeeInfo['state_id'];
            $taxPercentage = getTaxPercentage($net_salary, $state_id);
        }

        // Check if we need to add a new page
        if ($y + 7 > 280) { // Adjust this value based on your layout
            $pdf->AddPage();
            $pdf->SetFont('aefurat', '', 10);
            $y = 10; // Reset Y position after adding a new page

            // Re-add table header on the new page
            $pdf->SetFont('aefurat', 'B', 10);
            $pdf->SetXY(10, $y);
			$pdf->Cell(15, 7, "Staff No.", 1, 0, 'L', true);
			$pdf->Cell(60, 7, "Full name", 1, 0, 'L', true);
			$pdf->Cell(20, 7, "Base salary", 1, 0, 'L', true);
			$pdf->Cell(20, 7, "Allowance", 1, 0, 'L', true);
			$pdf->Cell(20, 7, "Bonus", 1, 0, 'L', true);
			$pdf->Cell(20, 7, "Deductions", 1, 0, 'L', true);
			$pdf->Cell(20, 7, "Tax", 1, 0, 'L', true);
			$pdf->Cell(20, 7, "Net pay", 1, 1, 'L', true);
            

            $y += 7;
        }

        // Add row
        $pdf->SetFont('aefurat', '', 10);
        $pdf->SetXY(10, $y);
		$pdf->Cell(15, 7, "$staff_no", 1, 0, 'L', 0);
		$pdf->Cell(60, 7, "$full_name", 1, 0, 'L', 0);
		$pdf->Cell(20, 7, formatMoney($base_salary), 1, 0, 'L', 0);
		$pdf->Cell(20, 7, formatMoney($allowance), 1, 0, 'L', 0);
		$pdf->Cell(20, 7, formatMoney($bonus), 1, 0, 'L', 0);
        $pdf->Cell(20, 7, formatMoney($total_deductions), 1, 0, 'L', 0);
		$pdf->Cell(20, 7, formatMoney($tax) . " (" . $taxPercentage . "%)", 1, 0, 'L', 0);
		$pdf->Cell(20, 7, formatMoney($net_salary), 1, 1, 'L', 0);


        $num++;
        $y += 7;
    }

    // Draw footer line on the last page
    $pdf->Rect(10, $y, 190, 0.1);
} else {
    $pdf->SetXY(10, $y+10);
    $pdf->Cell(190, 7, "No records were found", 0, 0, 'C', 0);
}

// Output the PDF
$pdf->Output("Componsation and Benfits Reports" . date('F Y', strtotime($month)) .".pdf", 'I');
?>
