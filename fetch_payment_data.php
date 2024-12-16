<?php
include('db_connection.php');

$invoiceNo = $_GET['invoiceNo'];

$query = "SELECT b.InvoiceNo, b.Period, b.OutstandingBalance, c.ClientID 
          FROM tblbilling b
          JOIN tblclient c ON b.ClientID = c.ClientID 
          WHERE b.InvoiceNo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $invoiceNo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode([]);
}
?>
