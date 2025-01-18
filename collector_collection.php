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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <title>Collector Collection</title>
    <style>
        /* General styling */
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; color: #333; margin: 0; padding: 0; }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        .container { max-width: 1800px; margin: auto; padding: 20px; margin-top: 120px; margin-bottom: 120px;}
        /* Top Navigation Bar */
        .top-nav {
            background-color: #2C3E50;
            color: white;
            display: flex;
            justify-content: space-between;
            padding: 1em 2em;
            align-items: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .top-nav h1 {
            font-size: 1.5em;
        }

        .profile a {
            color: #ecf0f1;
            text-decoration: none;
            padding: 0.5em 1em;
            background-color: #e74c3c;
            border-radius: 5px;
            font-size: 0.9em;
        }

        /* Table styling */
        .table-container {
            overflow-x: auto;
            padding: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #3498db;
            color: #fff;
            font-weight: bold;
            text-align: left;
            padding: 12px 15px;
            font-size: 1em;
            white-space: nowrap;
        }

        td {
            padding: 10px 15px;
            font-size: 0.95em;
            color: #555;
            vertical-align: middle;
            border-bottom: 1px solid #eaeaea;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f8ff;
        }

        tr:last-child td {
            border-bottom: none;
        }

        td, th {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Bottom Navigation Bar */
        .bottom-nav {
            display: flex;
            justify-content: space-around;
            background-color: #2C3E50;
            padding: 0.5em 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .bottom-nav a {
            color: white;
            text-decoration: none;
            font-size: 1em;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.3em;
        }

        .bottom-nav a i {
            font-size: 1.2em;
        }

        .bottom-nav .active {
            border-top: 2px solid #3498DB;
            padding-top: 0.5em;
        }

        .bottom-nav a span {
            font-size: 0.75em;
        }

                /* Responsive adjustments */
                @media (max-width: 560px) {
            .top-nav h1 {
                font-size: 1em;
            }
            .container h2{
                font-size: 0.9em;
            }

            .bottom-nav a span {
                display: none;
            }

            .bottom-nav a i {
                font-size: 1.8em;
            }

            table { width: 100%; background: #fff; border-radius: 2px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
            th{font-size: 7px;}
            td{font-size: 6px;}
            th, td { padding: 6px 4px; text-align: left;}
            tbody td {
                border: none;
                padding: 6px 2px;
            }
        }

    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <header>
        <nav class="top-nav">
            <h1><?php echo $_SESSION['FullName']; ?></h1>
            <div class="profile">
                <a href="coll_logout.php" style="color: white;">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h2>Collected Payments Awaiting Approval</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Reference No</th>
                        <th>Client</th>
                        <th>Period</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Last Bill</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ReferenceNo']); ?></td>
                                <td><?php echo htmlspecialchars($row['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($row['Period']); ?></td>
                                <td>&#8369;<?php echo number_format($row['Amount'], 2); ?></td>
                                <td><?php echo date("M d, Y h:i A", strtotime($row['PaymentDate'])); ?></td>
                                <td>&#8369;<?php echo number_format($row['LastBill'], 2); ?></td>
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
            <a href="collector_dash.php">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="collector_billing.php">
                <i class="fas fa-file-invoice"></i>
                <span>Billing</span>
            </a>
            <a href="collector_collection.php" class="active">
                <i class="fas fa-wallet"></i>
                <span>Collection</span>
            </a>
            <a href="collector_map.php">
                <i class="fas fa-map-marked-alt"></i>
                <span>Map</span>
            </a>
            <a href="collector_announcement.php">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
        </nav>
    </footer> 

</body>
</html>
