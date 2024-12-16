<?php
include('db_connection.php');

$period = $_GET['period'] ?? '';
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

$query = "SELECT b.InvoiceNo, c.FullName, b.DueDate, b.DueAmount, b.Status 
          FROM tblbilling b 
          JOIN tblclient c ON b.ClientID = c.ClientID
          WHERE 1=1";

$params = [];
if (!empty($period)) {
    $query .= " AND b.Period = ?";
    $params[] = $period;
}
if (!empty($status)) {
    if ($status === 'Paid') {
        $query .= " AND b.Status = ?";
        $params[] = 'Paid';
    } elseif ($status === 'Unpaid') {
        $query .= " AND b.Status IN (?, ?, ?)";
        $params = array_merge($params, ['Half Paid', 'Partially Paid', 'Not Yet Paid']);
    }
}
if (!empty($search)) {
    $query .= " AND c.FullName LIKE ?";
    $params[] = '%' . $search . '%';
}

$query .= " ORDER BY b.DueDate DESC";
$stmt = $conn->prepare($query);

if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Status = $row['Status'];
        $buttonText = 'Pay';
        $buttonClass = 'pay-btn';
        $buttonIcon = '<i class="fas fa-money-bill-wave"></i>'; // Pay icon
        $disabled = '';

        // Check for "Waiting" status in tblpayment
        $paymentCheckQuery = "SELECT PaymentStatus FROM tblpayment WHERE InvoiceNo = ? AND PaymentStatus = 'Waiting' LIMIT 1";
        $paymentStmt = $conn->prepare($paymentCheckQuery);
        $paymentStmt->bind_param("s", $row['InvoiceNo']);
        $paymentStmt->execute();
        $paymentResult = $paymentStmt->get_result();

        if ($Status === 'Paid') {
            $buttonText = 'Paid';
            $buttonClass = 'paid-btn';
            $buttonIcon = '<i class="fas fa-check-circle"></i>'; // Check icon
            $disabled = 'disabled';
        } elseif ($paymentResult->num_rows > 0) {
            $buttonText = 'Waiting';
            $buttonClass = 'waiting-btn';
            $buttonIcon = '<i class="fas fa-hourglass-half"></i>'; // Hourglass icon
            $disabled = 'disabled';
        }

        echo "<tr>";
        echo "<td>{$row['InvoiceNo']}</td>";
        echo "<td>{$row['FullName']}</td>";
        echo "<td>" . date("M d, Y", strtotime($row['DueDate'])) . "</td>";
        echo "<td>₱ {$row['DueAmount']}</td>";
        echo "<td>{$row['Status']}</td>";
        echo "<td>
                <button class='action-viewbtn' onclick='viewDetails(\"{$row['InvoiceNo']}\")'> 
                    <i class='fas fa-eye'></i> View
                </button>
                <button class='action-btn $buttonClass' onclick='openPaymentModal(\"{$row['InvoiceNo']}\")' $disabled>
                    $buttonIcon $buttonText
                </button>
              </td>";
        echo "</tr>";

        $paymentStmt->close();
    }
} else {
    echo "<tr><td colspan='6'>No records found.</td></tr>";
}

$stmt->close();
?>
