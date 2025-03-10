<?php
include('db_connection.php');

$invoiceNo = $_GET['invoiceNo'] ?? '';

if (!empty($invoiceNo)) {
    $query = "SELECT b.InvoiceNo, b.ClientID, b.DueDate, b.Period, p.Plan, b.DueAmount, b.Discount, 
                     b.AmountPaid, b.OutstandingBalance, b.Status
              FROM tblbilling b
              INNER JOIN tblplan p ON b.PlanID = p.PlanID
              WHERE b.InvoiceNo = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $invoiceNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $billingDetails = $result->fetch_assoc();
        echo json_encode($billingDetails);
    } else {
        echo json_encode([]);
    }

    $stmt->close();
}
