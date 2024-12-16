<?php
session_start();
include('db_connection.php'); 

// Check if the CollectorID is set in the session
if (!isset($_SESSION['CollectorID'])) {
    header("Location: collector_login.php"); // Redirect to login if not set
    exit;
}

$collectorID = $_SESSION['CollectorID'];

// Fetch payments with "Waiting" status for approval, including client FullName
$query = "SELECT p.ReferenceNo, p.InvoiceNo, c.FullName, p.Period, p.Amount, p.PaymentDate, p.LastBill 
          FROM tblpayment p
          JOIN tblclient c ON p.ClientID = c.ClientID
          WHERE p.PaymentStatus = 'Waiting' 
          ORDER BY p.PaymentDate DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Collection</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; color: #333; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .top-nav {
            background-color: #2C3E50;
            color: white;
            display: flex;
            justify-content: space-between;
            padding: 1em;
            align-items: center;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 1em;
        }

        .bottom-nav {
            display: flex;
            justify-content: space-around;
            background-color: #2C3E50;
            padding: 1em;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .bottom-nav a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
        }

        .bottom-nav .active {
            border-top: 2px solid #3498DB;
            padding-top: 0.5em;
        }

        /* Table styling */
        .table-container {
            overflow-x: auto;
        }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        th, td { padding: 8px 10px; text-align: left; }
        th { background-color: #3498db; color: #fff; font-weight: bold; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        tr:hover { background-color: #f1f1f1; }

        .icon {
            color: #ffffff;
            margin-right: 1px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <header>
        <nav class="top-nav">
            <h1>Collector Dashboard</h1>
            <div class="profile">
                <span><?php echo $_SESSION['FullName']; ?></span>
                <a href="coll_logout.php">Logout</a>
            </div>
        </nav>
    </header>
    <div class="container">
        <h2><i class="fas fa-hand-holding-usd"></i> Collected Payments Awaiting Approval</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag icon"></i> Reference No</th>
                        <th><i class="fas fa-file-invoice icon"></i> Invoice No</th>
                        <th><i class="fas fa-user icon"></i> Client</th>
                        <th><i class="fas fa-calendar icon"></i> Period</th>
                        <th><i class="fas fa-money-bill-wave icon"></i> Amount</th>
                        <th><i class="fas fa-calendar-day icon"></i> Payment Date</th>
                        <!-- <th><i class="fas fa-wallet icon"></i> Last Bill</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ReferenceNo']); ?></td>
                                <td><?php echo htmlspecialchars($row['InvoiceNo']); ?></td>
                                <td><?php echo htmlspecialchars($row['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($row['Period']); ?></td>
                                <td>&#8369;<?php echo number_format($row['Amount'], 2); ?></td>
                                <td><?php echo date("M d, Y h:i A", strtotime($row['PaymentDate'])); ?></td>
                                <!-- <td>&#8369;<?php echo number_format($row['LastBill'], 2); ?></td> -->
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #666;">No payments are waiting for approval.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php $conn->close(); ?>
    </div>
    <!-- Bottom Navigation Bar -->
    <footer>
        <nav class="bottom-nav">
            <a href="collector_dash.php">Dashboard</a>
            <a href="collector_billing.php">Billing</a>
            <a href="collector_collection.php" class="active">Collection</a>
            <a href="collector_map.php">Map</a>
            <a href="collector_announcement.php">Announcements</a>
        </nav>
    </footer>    
</body>
</html>
