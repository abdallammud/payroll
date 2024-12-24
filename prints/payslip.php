<?php 
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
$payrollDetId = $_GET['rec_id'] ?? 0;
$query = $GLOBALS['conn']->query("SELECT * FROM `payroll_details` WHERE `id` = '$payrollDetId'");
$rec = [];
$emp = [];
if($query) {
	while($row = $query->fetch_assoc()) {
		$rec = $row;
		$full_name = $row['full_name'];
		$emp_id = $row['emp_id'];
		$month = $row['month'];
		$base_salary = $row['base_salary'];
		$added_date = $row['added_date'];
		$month = date('F Y', strtotime($month));
		$added_date = date('F d, Y', strtotime($added_date));



		$emp = $employee = $GLOBALS['employeeClass']->read($emp_id);
		$state_id = $employee['state_id'];
		$taxPercentage = getTaxPercentage($base_salary, $state_id);
		// var_dump($employee);
		if(!$employee['avatar']) {
			if(strtolower($employee['gender']) == 'female')  {
				$employee['avatar'] = 'female_avatar.png';
			} else {
				$employee['avatar'] = 'male_avatar.png';
			}
		}

		$avatar = $employee['avatar'];
	}
}

// Create new PDF document
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Hawlkar it solutions');
$pdf->SetTitle('Payslip');
$pdf->SetSubject('Payslip');

// Disable default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetMargins(10, 10, 10); // left, top, right
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(20);

$pdf->SetAutoPageBreak(TRUE, 15);

// Add a page
$pdf->AddPage();

$pdf->SetFont('dejavusans', '', 12);
$pdf->Image('./assets/images/banner.png', 0, 0, 190); // Adjust size as needed

// Set header rectangle
$pdf->SetFillColor(80, 184, 72);
$pdf->SetDrawColor(80, 184, 72);
$pdf->Rect(20, 40, 170, 0.2); // Adjusted position and width for landscape

$pdf->SetFont('dejavusans', 'B', 13);
$pdf->SetXY(15, 45);
$pdf->Cell(0, 10, strtoupper("Payslip for the month of $month"), 0, 1, 'C');

$y = $pdf->getY();
$y += 5;

$pdf->Image('./assets/images/avatars/'.$avatar, 20, $y, 35);


$pdf->SetFillColor(255, 255, 255);
$pdf->SetDrawColor(000, 000, 000);

$pdf->SetFont('dejavusans', '', 9);
$pdf->SetXY(60, $y);

$pdf->Rect(60, $y, 65, 40);
$pdf->Rect(130, $y, 57, 40);

$pdf->Cell(15, 6, strtoupper("Employee name"), "", 0, '', 0);
$pdf->SetXY(130, $y);
$pdf->Cell(15, 6, strtoupper("Payment method"), "", 0, '', 0);
$pdf->SetFont('dejavusans', 'B', 9);

$y += 4;
$pdf->SetXY(60, $y);
$pdf->Cell(65, 6, strtoupper($rec['full_name']), "B", 0, '', 0);
$pdf->SetXY(130, $y);
$pdf->Cell(57, 6, strtoupper($emp['payment_bank'] .", ". $emp['payment_account']), "B", 0, '', 0);

$y += 6;
$pdf->SetFont('dejavusans', '', 9);
$pdf->SetXY(60, $y);
$pdf->Cell(65, 6, strtoupper("Employee ID / Staff No."), "", 0, '', 0);

$pdf->SetXY(130, $y);
$pdf->Cell(65, 6, strtoupper("Days Worked."), "", 0, '', 0);

$pdf->SetFont('dejavusans', 'B', 9);

$y += 4;
$pdf->SetXY(60, $y);
$pdf->Cell(65, 6, strtoupper($rec['emp_id'] .", ". $rec['staff_no']), "B", 0, '', 0);

$pdf->SetXY(130, $y);
$pdf->Cell(57, 6, strtoupper($rec['days_worked'] ."/". $rec['required_days'] ." days"), "B", 0, '', 0);

$y += 6;
$pdf->SetFont('dejavusans', '', 9);
$pdf->SetXY(60, $y);
$pdf->Cell(65, 6, strtoupper("Job title"), "", 0, '', 0);

$pdf->SetXY(130, $y);
$pdf->Cell(65, 6, strtoupper("Job Status"), "", 0, '', 0);

$pdf->SetFont('dejavusans', 'B', 9);

$y += 4;
$pdf->SetXY(60, $y);
$pdf->Cell(65, 6, strtoupper($emp['position']), "B", 0, '', 0);

$pdf->SetXY(130, $y);
$pdf->Cell(57, 6, strtoupper($emp['contract_type']), "B", 0, '', 0);

$y += 6;
$pdf->SetFont('dejavusans', '', 9);
$pdf->SetXY(60, $y);
$pdf->Cell(65, 6, strtoupper("Department"), "", 0, '', 0);

$pdf->SetXY(130, $y);
$pdf->Cell(65, 6, strtoupper("Pay date"), "", 0, '', 0);
$pdf->SetFont('dejavusans', 'B', 9);

$y += 4;
$pdf->SetXY(60, $y);
$pdf->Cell(65, 6, strtoupper($emp['branch']), "B", 0, '', 0);
$pdf->SetXY(130, $y);
$pdf->Cell(57, 6, strtoupper($added_date), "B", 0, '', 0);

$y += 10;

$pdf->SetFont('dejavusans', 'B', 9);
$pdf->SetXY(15, $y);
$pdf->Cell(0, 10, strtoupper("Payroll details"), 0, 1, 'C');


$pdf->SetFillColor(200, 200, 200);

$y += 10;
$pdf->SetXY(20, $y);
$pdf->Cell(80, 7, strtoupper("EARNINGS"), "LBTR", 0, '', 'F');

$pdf->SetXY(105, $y);
$pdf->Cell(82, 7, strtoupper("DEDUCTIONS"), "LBTR", 0, '', 'F');

$pdf->SetFillColor(255, 255, 255);

$pdf->SetFont('dejavusans', '', 9);
$y += 7; $y2 = $y;
$pdf->SetXY(20, $y);
$pdf->Cell(40, 7, strtoupper("basic salary"), "LBTR", 0, '', 'F');

$pdf->SetXY(60, $y);
$pdf->Cell(40, 7, formatMoney($rec['base_salary']), "LBTR", 0, '', 'F');

$y += 7;
$pdf->SetXY(20, $y);
$pdf->Cell(40, 7, strtoupper("allwance"), "LBTR", 0, '', 'F');

$pdf->SetXY(60, $y);
$pdf->Cell(40, 7, formatMoney($rec['allowance']), "LBTR", 0, '', 'F');

$y += 7;
$pdf->SetXY(20, $y);
$pdf->Cell(40, 7, strtoupper("comission"), "LBTR", 0, '', 'F');

$pdf->SetXY(60, $y);
$pdf->Cell(40, 7, formatMoney($rec['commission']), "LBTR", 0, '', 'F');

$y += 7;
$pdf->SetXY(20, $y);
$pdf->Cell(40, 7, strtoupper("extra hours"), "LBTR", 0, '', 'F');

$pdf->SetXY(60, $y);
$pdf->Cell(40, 7, formatMoney($rec['extra_hours']), "LBTR", 0, '', 'F');

$y += 7;
$pdf->SetXY(20, $y);
$pdf->Cell(40, 7, strtoupper("bonus"), "LBTR", 0, '', 'F');

$pdf->SetXY(60, $y);
$pdf->Cell(40, 7, formatMoney($rec['bonus']), "LBTR", 0, '', 'F');

$total_earnings = $rec['base_salary']+$rec['allowance'] + $rec['bonus'] + $rec['extra_hours'] + $rec['commission'];

$pdf->SetFont('dejavusans', 'B', 9);
$y += 7;
$pdf->SetXY(20, $y);
$pdf->Cell(40, 7, strtoupper("total earnings"), "LBTR", 0, '', 'F');

$pdf->SetXY(60, $y);
$pdf->Cell(40, 7, formatMoney($total_earnings), "LBTR", 0, '', 'F');


$pdf->SetFont('dejavusans', '', 9);

$pdf->SetXY(105, $y2);
$pdf->Cell(40, 7, strtoupper("UN-PAID DAYS"), "LBTR", 0, '', 'F');

$pdf->SetXY(145, $y2);
$pdf->Cell(42, 7, formatMoney($rec['unpaid_days']), "LBTR", 0, '', 'F');

$y2 += 7;
$pdf->SetXY(105, $y2);
$pdf->Cell(40, 7, strtoupper("UN-PAID hours"), "LBTR", 0, '', 'F');

$pdf->SetXY(145, $y2);
$pdf->Cell(42, 7, formatMoney($rec['unpaid_hours']), "LBTR", 0, '', 'F');

$y2 += 7;
$pdf->SetXY(105, $y2);
$pdf->Cell(40, 7, strtoupper("ADVANCE"), "LBTR", 0, '', 'F');

$pdf->SetXY(145, $y2);
$pdf->Cell(42, 7, formatMoney($rec['advance']), "LBTR", 0, '', 'F');

$y2 += 7;
$pdf->SetXY(105, $y2);
$pdf->Cell(40, 7, strtoupper("Loan"), "LBTR", 0, '', 'F');

$pdf->SetXY(145, $y2);
$pdf->Cell(42, 7, formatMoney($rec['loan']), "LBTR", 0, '', 'F');

$y2 += 7;
$pdf->SetXY(105, $y2);
$pdf->Cell(40, 7, strtoupper("other DEDUCTIONS"), "LBTR", 0, '', 'F');

$pdf->SetXY(145, $y2);
$pdf->Cell(42, 7, formatMoney($rec['deductions']), "LBTR", 0, '', 'F');

$y2 += 7;
$pdf->SetXY(105, $y2);
$pdf->Cell(40, 7, strtoupper("tax"), "LBTR", 0, '', 'F');

$pdf->SetXY(145, $y2);
$pdf->Cell(42, 7, formatMoney($rec['tax']) .", ($taxPercentage %)", "LBTR", 0, '', 'F');

$total_deductions = $rec['advance']+$rec['loan'] + $rec['deductions'] + $rec['unpaid_days'] + $rec['unpaid_hours']+$rec['tax'];

$pdf->SetFont('dejavusans', 'B', 9);
$y2 += 7;
$pdf->SetXY(105, $y2);
$pdf->Cell(40, 7, strtoupper("total deductions"), "LBTR", 0, '', 'F');

$pdf->SetXY(145, $y2);
$pdf->Cell(42, 7, formatMoney($total_deductions), "LBTR", 0, '', 'F');


$net_salary = $total_earnings - $total_deductions;

$pdf->SetFillColor(220, 220, 220);
$y2 += 10;
$pdf->SetXY(105, $y2);
$pdf->Cell(40, 7, strtoupper("net salary"), "LBTR", 0, '', 'F');

$pdf->SetXY(145, $y2);
$pdf->Cell(42, 7, formatMoney($net_salary), "LBTR", 0, '', 'F');

$y = $y2;

$pdf->SetFont('dejavusans', '', 9.5);
$y += 20;
$pdf->SetXY(20, $y);
$pdf->MultiCell(169, 5, "I, EMPLOYEE NAME, HEREBY DECLARE THAT I GOT AND AGREED THE NET SALARY MENTIONED
ABOVE, AND I AM THE EMPLOYEE WHO SIGNED THIS DOCUMENT. ", "", "L");


$y += 20; $y2 = $y3 = $y;
$pdf->Rect(20, $y, 50, 60);
$pdf->SetFillColor(255, 255, 255);

$pdf->SetFont('dejavusans', 'B', 9.5);
$pdf->SetXY(20, $y+1);
$pdf->MultiCell(50, 10, "EMPLOYEE NAME & SIGNATURE", "LBR", "C", 'C', 'F');

$y += 12;
$pdf->SetXY(20, $y);
$pdf->MultiCell(50, 7, strtoupper($rec['full_name']), "LBR", "C", 'C', 'F');

$y += 10;
$pdf->SetXY(20, $y);
$pdf->MultiCell(50, 7, strtoupper("SIGNATURE"), "LBR", "C", 'C', 'F');

$y += 10;
$pdf->SetXY(20, $y);
$pdf->MultiCell(50, 7, strtoupper(''), "LBR", "C", 'C', 'F');


$y += 10;
$pdf->SetXY(20, $y);
$pdf->MultiCell(50, 7, strtoupper("date"), "LBR", "C", 'C', 'F');


$y += 10;
$pdf->SetXY(20, $y);
$pdf->MultiCell(50, 7.7, strtoupper("_____/_____/".date('Y')), "LBR", "C", 'C', 'F');

$hrUserInfo = $GLOBALS['userClass']->get($rec['added_by'])['full_name'];

$pdf->Rect(75, $y2, 50, 60);
$pdf->SetFillColor(255, 255, 255);

$pdf->SetFont('dejavusans', 'B', 10);
$pdf->SetXY(75, $y2+1);
$pdf->MultiCell(50, 10, "HRM NAME & SIGNATURE", "LBR", "C", 'C', 'F');
$pdf->SetFont('dejavusans', 'B', 9.5);

$y2 += 12;
$pdf->SetXY(75, $y2);
$pdf->MultiCell(50, 7, strtoupper($hrUserInfo), "LBR", "C", 'C', 'F');

$y2 += 10;
$pdf->SetXY(75, $y2);
$pdf->MultiCell(50, 7, strtoupper("SIGNATURE"), "LBR", "C", 'C', 'F');

$y2 += 10;
$pdf->SetXY(75, $y2);
$pdf->MultiCell(50, 7, strtoupper(''), "LBR", "C", 'C', 'F');


$y2 += 10;
$pdf->SetXY(75, $y2);
$pdf->MultiCell(50, 7, strtoupper("date"), "LBR", "C", 'C', 'F');


$y2 += 10;
$pdf->SetXY(75, $y2);
$pdf->MultiCell(50, 7.7, strtoupper("_____/_____/".date('Y')), "LBR", "C", 'C', 'F');


$fnUserInfo = $GLOBALS['userClass']->get($rec['paid_by']);
if($fnUserInfo) {
	$fnUserInfo = $fnUserInfo['full_name'];
}

$pdf->Rect(130, $y3, 50, 60);
$pdf->SetFillColor(255, 255, 255);

$pdf->SetFont('dejavusans', 'B', 9.5);
$pdf->SetXY(130, $y3+1);
$pdf->MultiCell(50, 10, "FINANCER NAME &SIGNATURE", "LBR", "C", 'C', 'F');

$y3 += 12;
$pdf->SetXY(130, $y3);
$pdf->MultiCell(50, 7, strtoupper($fnUserInfo), "LBR", "C", 'C', 'F');

$y3 += 10;
$pdf->SetXY(130, $y3);
$pdf->MultiCell(50, 7, strtoupper("SIGNATURE"), "LBR", "C", 'C', 'F');

$y3 += 10;
$pdf->SetXY(130, $y3);
$pdf->MultiCell(50, 7, strtoupper(''), "LBR", "C", 'C', 'F');


$y3 += 10;
$pdf->SetXY(130, $y3);
$pdf->MultiCell(50, 7, strtoupper("date"), "LBR", "C", 'C', 'F');


$y3 += 10;
$pdf->SetXY(130, $y3);
$pdf->MultiCell(50, 7.7, strtoupper("_____/_____/".date('Y')), "LBR", "C", 'C', 'F');

// Output the PDF
$pdf->Output("Payslip.pdf", 'I');
?>