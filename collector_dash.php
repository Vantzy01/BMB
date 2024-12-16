<?php
session_start(); // Start the session

// Include the database connection
include('db_connection.php');

// Check if the CollectorID is set in the session
if (!isset($_SESSION['CollectorID'])) {
    header("Location: collector_login.php"); // Redirect to login if not set
    exit;
}

$collectorID = $_SESSION['CollectorID'];

// Total Collected Amount for the collector (Waiting status)
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
    <title>Collector Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
/* Add styling for the collector dashboard */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f7fa;
}

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

.dashboard {
    padding: 2em;
    background-color: #F7F9FA;
}

.performance-overview {
    display: flex;
    justify-content: space-around;
}

.card {
    background-color: #3498DB;
    color: white;
    padding: 2em;
    text-align: center;
    border-radius: 10px;
    width: 20%;
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

    <!-- Performance Dashboard -->
    <main class="dashboard">
        <section class="performance-overview">
            <div class="card">
                <h2>Total Collected Amount</h2>
                <p>$<?php echo number_format($totalCollected, 2); ?></p>
            </div>
            <div class="card">
                <h2>Pending Approvals</h2>
                <p><?php echo $pendingCount; ?></p>
            </div>
            <div class="card">
                <h2>Unpaid Invoices</h2>
                <p><?php echo $unpaidCount; ?></p>
            </div>
        </section>
    </main>

    <!-- Bottom Navigation Bar -->
    <footer>
        <nav class="bottom-nav">
            <a href="collector_dash.php" class="active">Dashboard</a>
            <a href="collector_billing.php">Billing</a>
            <a href="collector_collection.php">Collection</a>
            <a href="collector_map.php">Map</a>
            <a href="collector_announcement.php">Announcements</a>
        </nav>
    </footer>

</body>
</html>

