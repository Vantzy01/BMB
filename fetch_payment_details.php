<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo 'You need to be logged in to view payment details.';
    exit();
}

$clientID = $_SESSION['clientID'];
$invoiceNo = isset($_POST['invoiceNo']) ? $_POST['invoiceNo'] : '';

include('db_connection.php');

// Fetch payment history for the given invoice number
$sql = "SELECT ReferenceNo, PaymentMethod, Amount, PaymentStatus, PaymentDate 
        FROM tblpayment 
        WHERE InvoiceNo = ? AND ClientID = ?
        ORDER BY PaymentDate ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $invoiceNo, $clientID);
$stmt->execute();
$result = $stmt->get_result();
$payments = $result->fetch_all(MYSQLI_ASSOC);

// Fetch the outstanding balance for the given invoice number
$balanceSql = "SELECT OutstandingBalance 
               FROM tblbilling 
               WHERE InvoiceNo = ? AND ClientID = ?";
$balanceStmt = $conn->prepare($balanceSql);
$balanceStmt->bind_param("ss", $invoiceNo, $clientID);
$balanceStmt->execute();
$balanceResult = $balanceStmt->get_result();
$balanceData = $balanceResult->fetch_assoc();
$outstandingBalance = $balanceData['OutstandingBalance'];

$stmt->close();
$balanceStmt->close();
$conn->close();

// Build the payment timeline HTML response
if (empty($payments)) {
    echo '<li class="list-group-item text-center">No payment records found for this invoice.</li>';
} else {
    foreach ($payments as $payment) {
        echo '<li class="list-group-item">';
        echo '<strong>Reference No:</strong> ' . htmlspecialchars($payment['ReferenceNo']) . '<br>';
        echo '<strong>Method:</strong> ' . htmlspecialchars($payment['PaymentMethod']) . '<br>';
        echo '<strong>Amount:</strong> ₱' . number_format($payment['Amount'], 2) . '<br>';
        echo '<strong>Status:</strong> ' . htmlspecialchars($payment['PaymentStatus']) . '<br>';
        echo '<strong>Date:</strong> ' . date("F d, Y h:i A", strtotime($payment['PaymentDate'])) . '<br>';
        echo '</li>';
    }
    // Display the outstanding balance at the end of the list
    echo '<li class="list-group-item list-group-item-info">';
    echo '<strong>Outstanding Balance:</strong> ₱' . number_format($outstandingBalance, 2);
    echo '</li>';
}

