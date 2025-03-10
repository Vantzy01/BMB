<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['CollectorID'])) {
    header("Location: collector_login.php");
    exit;
}
$collectorID = $_SESSION['CollectorID'];
$totalCollectedQuery = "
    SELECT SUM(Amount) as totalCollected 
    FROM tblpayment 
    WHERE PaymentStatus = 'Waiting'";
$stmt = $conn->prepare($totalCollectedQuery);
$stmt->execute();
$totalCollectedResult = $stmt->get_result()->fetch_assoc();
$totalCollected = $totalCollectedResult['totalCollected'] ?? 0;
// Pending Approvals count for payments with 'Waiting' status
$pendingApprovalsQuery = "
    SELECT COUNT(*) as pendingCount 
    FROM tblpayment 
    WHERE PaymentStatus = 'Waiting'";
$pendingApprovalsResult = $conn->query($pendingApprovalsQuery)->fetch_assoc();
$pendingCount = $pendingApprovalsResult['pendingCount'] ?? 0;
// Unpaid Invoices count
$unpaidInvoicesQuery = "
    SELECT COUNT(*) as unpaidCount 
    FROM tblbilling 
    WHERE Status = 'Not Yet Paid'";
$unpaidInvoicesResult = $conn->query($unpaidInvoicesQuery)->fetch_assoc();
$unpaidCount = $unpaidInvoicesResult['unpaidCount'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Images/logo.ico" />
    <title>Collector Dashboard - BMB Cell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;

        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

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

        /* Dashboard Section */
        .dashboard {
            margin-top: 100px;
            padding: 2em;
            background-color: #f4f7fa;
            margin-bottom: 50px;
        }

        .performance-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5em;
        }

        .card {
            background: linear-gradient(135deg, #3498DB, #6DD5FA);
            color: white;
            padding: 2em;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card h2 {
            font-size: 1.2em;
            margin-bottom: 0.5em;
        }

        .card p {
            font-size: 1.8em;
            font-weight: bold;
        }

        .card i {
            font-size: 3em;
            position: absolute;
            top: -20px;
            right: -20px;
            color: rgba(255, 255, 255, 0.2);
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

        /* Responsive Design */
        @media (max-width: 560px) {
            .dashboard {
                margin-top: 60px;
            }


            .top-nav h1 {
                font-size: 1em;
            }

            .card p {
                font-size: 1.5em;
            }

            .card h2 {
                font-size: 1em;
            }

            .bottom-nav a span {
                display: none;
            }

            .bottom-nav a i {
                font-size: 1.8em;
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
                <a href="coll_logout.php">
                    <i class="fas fa-sign-out-alt"> Logout</i>
                </a>
            </div>
        </nav>
    </header>

    <!-- Performance Overview -->
    <main class="dashboard">
        <section class="performance-overview">
            <div class="card">
                <h2>Total Collected Amount</h2>
                <p>₱<?php echo number_format($totalCollected, 2); ?></p>
                <i class="fas fa-wallet"></i>
            </div>
            <div class="card">
                <h2>Pending Approvals</h2>
                <p><?php echo $pendingCount; ?></p>
                <i class="fas fa-clock"></i>
            </div>
            <div class="card">
                <h2>Unpaid Invoices</h2>
                <p><?php echo $unpaidCount; ?></p>
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </section>
    </main>

    <!-- Bottom Navigation Bar -->
    <footer>
        <nav class="bottom-nav">
            <a href="collector_dash.php" class="active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="collector_billing.php">
                <i class="fas fa-file-invoice"></i>
                <span>Billing</span>
            </a>
            <a href="collector_collection.php">
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