<?php
include('db_connection.php');

$data = json_decode(file_get_contents("php://input"), true);

$query = "INSERT INTO tblpayment (ReferenceNo, InvoiceNo, ClientID, PaymentMethod, Period, Amount, PaymentStatus, PaymentDate, LastBill)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param(
    "sssssdsss",
    $data['ReferenceNo'],
    $data['InvoiceNo'],
    $data['ClientID'],
    $data['PaymentMethod'],
    $data['Period'],
    $data['Amount'],
    $data['PaymentStatus'],
    $data['PaymentDate'],
    $data['LastBill']
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>