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

// Create new PDF document
$pdf = new MYPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Author Name');
$pdf->SetTitle('All Employees Report');
$pdf->SetSubject('Employees Report');

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
$pdf->Image('./assets/images/logo.png', 125, 10, 40);
// Set header rectangle
$pdf->SetFillColor(80, 184, 72);
$pdf->SetDrawColor(80, 184, 72);
$pdf->Rect(15, 40, 270, 0.2); // Adjusted position and width for landscape

$pdf->SetFont('aefurat', 'B', 13);
$pdf->SetXY(15, 45);
$pdf->Cell(0, 10, "All Employees Report", 0, 1, 'C');

$pdf->SetFont('aefurat', '', 10);
$pdf->SetXY(15, 50);
$pdf->Cell(0, 10, "Print date  " . date('F d, Y h:i:s A'), 0, 1, 'C');

$y = 60;

// Table Header
$pdf->SetFont('aefurat', 'B', 10);
$pdf->SetXY(15, $y);
$pdf->Cell(15, 7, "Staff No.", 1, 0, 'L', true);
$pdf->Cell(70, 7, "Full name", 1, 0, 'L', true);
$pdf->Cell(40, 7, "Phone number", 1, 0, 'L', true);
$pdf->Cell(60, 7, "Email", 1, 0, 'L', true);
$pdf->Cell(40, 7, "Department", 1, 0, 'L', true);
$pdf->Cell(40, 7, "Location", 1, 1, 'L', true);

$y += 7;

$get_employees = "SELECT * FROM `employees` WHERE `status` = 'Active'";
$employees = $GLOBALS['conn']->query($get_employees);
$num = 1;

if ($employees->num_rows > 0) {
    while ($row = $employees->fetch_assoc()) {
        $staff_no 	= $row['staff_no'];
        $full_name 	= $row['full_name'];
        $email 		= $row['email'];
        $phone_number = $row['phone_number'];
        $branch 	= $row['branch'];
        $location_name = $row['location_name'];
        $added_date = new DateTime($row['added_date']);
        $added_date = $added_date->format('F d Y');

        // Check if we need to add a new page
        if ($y + 7 > 180) { // Adjust this value based on your layout
            $pdf->AddPage();
            $pdf->SetFont('aefurat', '', 10);
            $y = 10; // Reset Y position after adding a new page

            // Re-add table header on the new page
            $pdf->SetFont('aefurat', 'B', 10);
            $pdf->SetXY(15, $y);
			$pdf->Cell(15, 7, "Staff No.", 1, 0, 'L', true);
			$pdf->Cell(70, 7, "Full name", 1, 0, 'L', true);
			$pdf->Cell(40, 7, "Phone number", 1, 0, 'L', true);
			$pdf->Cell(60, 7, "Email", 1, 0, 'L', true);
			$pdf->Cell(40, 7, "Department", 1, 0, 'L', true);
			$pdf->Cell(40, 7, "Location", 1, 1, 'L', true);

            $y += 7;
        }

        // Add row
        $pdf->SetFont('aefurat', '', 10);
        $pdf->SetXY(15, $y);
        $pdf->Cell(15, 7, $staff_no, 1, 0, 'L');
        $pdf->Cell(70, 7, $full_name, 1, 0, 'L');
        $pdf->Cell(40, 7, $phone_number, 1, 0, 'L');
        $pdf->Cell(60, 7, $email, 1, 0, 'L');
        $pdf->Cell(40, 7, $branch, 1, 0, 'L');
        $pdf->Cell(40, 7, $location_name, 1, 1, 'L');

        $num++;
        $y += 7;
    }

    // Draw footer line on the last page
    $pdf->Rect(15, $y, 265, 0.1);
}

// Output the PDF
$pdf->Output("All Employees Report" . ".pdf", 'I');
?>
