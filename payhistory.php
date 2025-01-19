<?php
session_start();

include('db_connection.php');

// Check if the client is logged in
if (!isset($_SESSION['clientID'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logged-in client's ID from the session
$clientID = $_SESSION['clientID'];

// Query to fetch payment history for the logged-in client
$query = "SELECT ReferenceNo, InvoiceNo, PaymentMethod, Period, Amount, PaymentStatus, PaymentDate, LastBill 
          FROM tblpayment 
          WHERE ClientID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $clientID);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History - BMB Internet Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .main-content h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            background-color: #fff;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .table thead th {
            background-color: #f3f4f6;
            font-weight: 600;
            color: #666;
        }
        .badge-confirmed {
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .badge-pending {
            background-color: #ffeeba;
            color: #856404;
            border-radius: 5px;
            padding: 5px 10px;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #eee;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <main class="main-content">
        <div class="container mt-5">
            <h2>Payment History</h2>
            <p>Review your payment history and keep track of your transactions.</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Reference No</th>
                        <th>Invoice No</th>
                        <th>Payment Method</th>
                        <th>Period</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows === 0): ?>
                        <tr>
                            <td colspan="7" class="text-center">No payment records found.</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ReferenceNo']); ?></td>
                                <td><?php echo htmlspecialchars($row['InvoiceNo']); ?></td>
                                <td><?php echo htmlspecialchars($row['PaymentMethod']); ?></td>
                                <td><?php echo htmlspecialchars($row['Period']); ?></td>
                                <td>$<?php echo number_format($row['Amount'], 2); ?></td>
                                <td>
                                    <span class="badge-confirmed">
                                        <?php echo htmlspecialchars($row['PaymentStatus']); ?>
                                    </span>
                                </td>
                                <td><?php echo date("d M Y H:i:s", strtotime($row['PaymentDate'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        &copy; 2024 BMB Internet Service. All rights reserved.
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
