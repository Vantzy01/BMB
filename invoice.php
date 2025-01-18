<?php
require_once('fpdf/fpdf.php');

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbinternet";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$invoiceNo = $_GET['invoiceNo'];

// Fetch invoice details from tblbilling
$invoiceSql = "SELECT tblbilling.*, tblclient.FullName, tblclient.Address, tblclient.MobileNumber, tblclient.Email, tblplan.Plan, tblplan.MonthlyCost
               FROM tblbilling
               JOIN tblclient ON tblbilling.ClientID = tblclient.ClientID
               JOIN tblplan ON tblbilling.PlanID = tblplan.PlanID
               WHERE InvoiceNo = ?";
$invoiceStmt = $conn->prepare($invoiceSql);
$invoiceStmt->bind_param("s", $invoiceNo);
$invoiceStmt->execute();
$invoiceResult = $invoiceStmt->get_result();
$invoice = $invoiceResult->fetch_assoc();

// Close the statement and connection
$invoiceStmt->close();
$conn->close();

// Create a new PDF document using FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Set the font for the text
$pdf->SetFont('Arial', '', 12);

$pdf->AddFont("CenturyGothic", "", "07558_CenturyGothic.php");

// Set background image
$img_file = 'C:/xampp/htdocs/BMB/Images/invoice2.jpg';
if (!file_exists($img_file)) {
    die('Image not found: ' . $img_file);
}
$pdf->Image($img_file, 0, 0, 210, 297); // Width and height adjusted for A4 size



// Place text fields over the image, adjusting positions as needed
$pdf->SetXY(11, 85);
$pdf->SetFont("Arial", "B", 22);
$pdf->Cell(0, 10, $invoice['FullName'], 0, 1, 'L');

$pdf->SetFont('Arial', 'I', 12);
$pdf->SetXY(11, 92);
$pdf->Cell(0, 10, $invoice['MobileNumber'], 0, 1, 'L');

$pdf->SetFont('Arial', 'I', 12);
$pdf->SetXY(11, 97);
$pdf->Cell(0, 10, $invoice['Address'], 0, 1, 'L');

$pdf->SetFont('Arial', 'I', 12);
$pdf->SetXY(11, 102);
$pdf->Cell(0, 10, $invoice['Email'], 0, 1, 'L');
// INVOICE
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(155, 76);
$pdf->Cell(0, 10, $invoice['ClientID'], 0, 1, 'L');

$pdf->SetXY(155, 84.8);
$pdf->Cell(0, 10, $invoice['InvoiceNo'], 0, 1, 'L');

$invoiceDate = date('m/d/Y');
$pdf->SetXY(155, 93.4); 
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $invoiceDate, 0, 1, 'L');

$pdf->SetXY(155, 102);
$pdf->Cell(0, 10, date('m/d/Y', strtotime($invoice['DueDate'])), 0, 1, 'L');
// INVOICE

$pdf->SetFont('Arial', '', 14);
$pdf->SetXY(28, 115);
$pdf->Cell(0, 10, date('F Y', strtotime($invoice['Period'])), 0, 1, 'L');

// Set the status color and text
$status = $invoice['Status'];

switch ($status) {
    case 'Paid':
        $img_paid = 'C:/xampp/htdocs/BMB/Images/paid.png';
        if (!file_exists($img_paid)) {
            die('Image not found: ' . $img_paid);
        }
        $pdf->Image($img_paid, 48, 195, 110, 70);

        $pdf->SetTextColor(0, 128, 0); // Green
        $statusText = 'Paid';
        break;
    case 'Half Paid':
    case 'Partially Paid':
        $pdf->SetTextColor(255, 165, 0); // Orange
        $statusText = $status;
        break;
    default:
        $pdf->SetTextColor(255, 0, 0); // Red
        $statusText = 'Not Yet Paid';
        break;
}
$pdf->SetXY(163.5, 115);
$pdf->Cell(36, 10, $statusText, 0, 1, 'C');

// Reset text color to black for further content
$pdf->SetTextColor(0, 0, 0);

$pdf->SetFont('Arial', '', 13);
$pdf->SetXY(19, 133);
$pdf->Cell(0, 10, '1', 0, 1, 'L');

$pdf->SetXY(33.5, 132.5);
$pdf->Cell(48.5, 10, $invoice['Plan'], 0, 1, 'C');

$pdf->SetXY(82.7, 132.5);
$pdf->Cell(45.5, 10, 'Php ' . number_format($invoice['DueAmount'], 2), 0, 1, 'C');

$pdf->SetXY(128.8, 132.5);
$pdf->Cell(34.1, 10, number_format($invoice['Discount']), 0, 1, 'C');

// Calculate the Total (DueAmount - Discount)
$totalAmount = $invoice['DueAmount'] - $invoice['Discount']; 

$pdf->SetXY(163.5, 132.5);
$pdf->Cell(36, 10, 'Php ' . number_format($totalAmount, 2), 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 13);
$pdf->SetXY(163.5, 143);
$pdf->Cell(36, 10, 'Php ' . number_format($totalAmount, 2), 0, 1, 'C');

// Output the PDF as a file or download
$pdf->Output('I', 'invoice_' . $invoiceNo . '.pdf');
