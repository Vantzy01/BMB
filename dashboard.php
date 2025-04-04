<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['clientID'])) {
    $_SESSION['clientID'] = $_GET['clientID'];
}

$clientID = isset($_SESSION['clientID']) ? $_SESSION['clientID'] : null;
$fullName = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : null;

if (!$clientID) {
    die("Client ID is not available.");
}

// Fetch plan details based on the client's current plan
$planSql = "SELECT p.* FROM tblplan p
            JOIN tblclient c ON c.PlanID = p.PlanID
            WHERE c.ClientID = ?";
$planStmt = $conn->prepare($planSql);
$planStmt->bind_param("s", $clientID);
$planStmt->execute();
$planResult = $planStmt->get_result();
$plan = $planResult->fetch_assoc();
// Get the current date
$currentDate = date('Y-m-d');
// Fetch the latest bill based on the current period relative to today
$billSql = "SELECT * FROM tblbilling 
            WHERE ClientID = ? 
            AND STR_TO_DATE(CONCAT('01-', Period), '%d-%M-%Y') <= ? 
            ORDER BY STR_TO_DATE(CONCAT('01-', Period), '%d-%M-%Y') DESC 
            LIMIT 1";
$billStmt = $conn->prepare($billSql);
$billStmt->bind_param("ss", $clientID, $currentDate);
$billStmt->execute();
$billResult = $billStmt->get_result();
$latestBill = $billResult->fetch_assoc();

$announcementSql = "SELECT * FROM tblannouncements ORDER BY DateCreated DESC";
$announcementResult = $conn->query($announcementSql);
$announcements = $announcementResult->fetch_all(MYSQLI_ASSOC);

$planStmt->close();
$billStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Images/logo.ico" />
    <title>Dashboard - BMB Cell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            background-color: #ededf5;
        }

        :root {
            --primary-color: #007bff;
            --secondary-color: #343a40;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --purple-color: #6f42c1;
        }

        .notification-dropdown {
            position: absolute;
            top: 60px;
            right: 20px;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1050;
        }

        .notification-dropdown .list-group-item {
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .notification-dropdown .list-group-item:hover {
            background: #f0f0f0;
        }

        .notification-dropdown-header {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            font-size: 16px;
            background-color: #f8f9fa;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .sidebar {
            height: 100%;
            width: 300px;
            background-color: #ffffff;
            padding-top: 50px;
            position: fixed;
            left: -300px;
            transition: left 0.3s ease-in-out;
            box-shadow: 3px 0 5px rgba(0, 0, 0, 0.2);
        }

        .client-info {
            padding: 30px;
            font-size: 18px;
            text-align: left;
            color: black;
            padding-left: 20px;
        }

        .client-info i {
            margin-right: 10px;
        }

        .close-btn {
            background: none;
            border: none;
            color: black;
            font-size: 24px;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: #ff5c5c;
        }

        .sidebar.show {
            left: 0;
            z-index: 1009;
        }

        .sidebar a {
            color: black;
            padding: 15px 20px;
            display: block;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease, padding-left 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #007bff;
            padding-left: 25px;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar .active {
            background-color: #007bff;
            padding-left: 25px;
        }

        .top-navbar {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background-color: #007bff;
            color: #fff;
        }

        .top-navbar .notification-icon {
            cursor: pointer;
        }

        .top-navbar .collapse-btn {
            cursor: pointer;
        }

        .wrapper {
            display: flex;
            flex: 1;
        }

        .main-content {
            margin-left: 0;
            padding: 20px;
            flex-grow: 1;
            transition: margin-left 0.3s;
        }

        /* For Card Styling */
        .card-container {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px 0;
            border-style: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
        }

        .card {
            flex: 1;
            padding: 35px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            text-align: left;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card h3 {
            margin-top: 0;
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .card p {
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: 500;
        }

        .card small {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 400;
            margin-bottom: 20px;
        }

        .card-content {
            margin: 0;
        }

        .card-icon {
            font-size: 1.5em;
            color: #f8f9fa;
            cursor: pointer;
            padding: 10px 20px;
            background-color: #007bff;
            border: 1px solid #007bff;
            border-radius: 10px;
            transition: border-radius 0.2s ease-in;
        }

        .card-icon:hover {
            color: #f8f9fa;
            border-radius: 50%;
        }

        .bill-icon {
            font-size: 1.5em;
            color: #f8f9fa;
            cursor: pointer;
            padding: 10px 20px;
            background-color: #007bff;
            border: 1px solid #007bff;
            border-radius: 10px;
            transition: border-radius 0.2s ease-in;
        }

        .bill-icon:hover {
            color: #f8f9fa;
            border-radius: 50%;
        }

        .bill-icon.disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .modal-header {
            border-bottom: none;
            align-items: center;
        }

        .modal-footer {
            border-top: none;
            justify-content: center;
        }

        /* Combined header styles */
        .modal-header,
        .modal-footer {
            border-bottom: none;
        }

        .btn-pay,
        .btn-confirm,
        .btn-invoice {
            margin: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-pay,
        .btn-confirm,
        .btn-invoice {
            margin: 5px;
        }

        /* Media Query Card for Responsiveness */
        @media (max-width: 440px) {
            .card-container {
                padding: 0;
            }

            .card {
                padding: 20px 15px;
            }

            .card-content {
                padding: 0px;
            }

            .card-container .card .card-content h3 {
                font-size: 1rem;
            }

            .card-container .card p {
                font-size: 1.8rem;
            }

            .card-container .card small {
                font-size: 0.7rem;
            }

            .card-icon {
                font-size: 1rem;
                background-color: #007bff;
                padding: 10px 15px;
                border-radius: 10px;
            }

            .bill-icon {
                font-size: 1rem;
                background-color: #007bff;
                padding: 10px 15px;
                border-radius: 10px;
            }
        }

        @media (max-width: 550px) {
            .card-container .card h3 {
                font-size: 1.2rem;
            }
        }

        @media (min-width: 551px) and (max-width: 400px) {
            .card-container {
                flex-direction: column;
                gap: 20px;
            }
        }

        @media (min-width: 985px) {
            .card-container {
                flex-direction: row;
                gap: 20px;
            }
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background: #f9f9f9;
        }

        .modal-header {
            background-color: #4a90e2;
            color: white;
            border-bottom: none;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .modal-title {
            font-weight: bold;
        }

        .btn-pay,
        .btn-confirm,
        .btn-invoice {
            margin-right: 5px;
        }

        .btn-pay {
            background-color: var(--danger-color);
            border: none;
        }

        .btn-confirm {
            background-color: #f39c12;
            border: none;
        }

        .btn-invoice {
            background-color: #3498db;
            border: none;
        }

        .btn-pay:hover,
        .btn-confirm:hover,
        .btn-invoice:hover {
            opacity: 0.9;
        }

        .modal-body ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .modal-body ul li {
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        /* Bottom Navigation */
        .bottom-navbar {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #ffffff;
            display: flex;
            justify-content: space-around;
            padding: 5px;
            z-index: 1000;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.3);
            font-family: 'Poppins', sans-serif;
        }

        .nav-item {
            color: #a8a8a8;
            text-align: center;
            font-size: 14px;
            padding: 6px 0;
            flex-grow: 1;
            text-decoration: none;
            transition: color 0.3s, transform 0.3s;
        }

        .nav-item i {
            display: block;
            font-size: 20px;
            margin-bottom: 3px;
        }

        .nav-item:hover {
            color: #007bff;
            transform: scale(1.1);
            text-decoration: none;
        }

        .nav-item span {
            font-size: 12px;
        }

        .nav-item.active {
            color: #007bff;
            border-top: 3px solid #007bff;
            text-decoration: none;
        }


        /* Fullscreen spinner overlay */
        #spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Spinner animation */
        .loader {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: inline-block;
            border-top: 4px solid #FFF;
            border-right: 4px solid transparent;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        .loader::after {
            content: '';
            box-sizing: border-box;
            position: absolute;
            left: 0;
            top: 0;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border-left: 4px solid #007bff;
            border-bottom: 4px solid transparent;
            animation: rotation 0.5s linear infinite reverse;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }


        /* Media Query Example for Responsiveness */
        @media (max-width: 600px) {
            .modal-content {
                margin: 10px;
            }

            .bottom-navbar span {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Spinner -->
    <div id="spinner" class="spinner" style="display: none;">
        <div class="loader"></div>
    </div>
    <!-- Top Navbar -->
    <header class="top-navbar">
        <!-- Sidebar Toggle Button -->
        <div class="collapse-btn" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </div>
        <div class="top-title">
            <a> BMB Cell and Computer Shop</a>
        </div>
        <div class="notification-icon">
            <!-- Notification Bell Icon -->
            <i class="fas fa-bell" id="notificationBell"></i>
        </div>
    </header>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <button class="close-btn" id="closeSidebarBtn">&times;</button>
        <div class="client-info">
            <i class="fas fa-user"></i> <span id="client-fullname"><?php echo htmlspecialchars($fullName); ?></span>
        </div>
        <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
        <a href="package.php"><i class="fas fa-box"></i> Package</a>
        <a href="bill.php"><i class="fas fa-file-invoice"></i> Bill</a>
        <a href="complaint.php"><i class="fas fa-comments"></i> Complaint</a>
        <a href="change_password.php"><i class="fas fa-key"></i> Change Password</a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
    <!-- Notification Dropdown -->
    <div class="notification-dropdown" id="announcementDropdown">
        <div class="notification-dropdown-header">Announcements</div>
        <ul class="list-group">
            <?php if (empty($announcements)): ?>
                <li class="list-group-item text-center">No new announcements</li>
            <?php else: ?>
                <?php foreach ($announcements as $announcement): ?>
                    <li class="list-group-item"
                        data-title="<?php echo htmlspecialchars($announcement['Title']); ?>"
                        data-message="<?php echo nl2br(htmlspecialchars($announcement['Message'])); ?>"
                        data-date="<?php echo date("F d, Y h:i A", strtotime($announcement['DateCreated'])); ?>">
                        <h6><?php echo htmlspecialchars($announcement['Title']); ?></h6>
                        <small><?php echo date("F d, Y h:i A", strtotime($announcement['DateCreated'])); ?></small>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    <!-- Main Content Area -->
    <div class="wrapper">
        <!-- Main Content Area -->
        <main class="main-content">
            <div class="card-container">
                <!-- Package Card -->
                <div class="card">
                    <div class="card-content">
                        <h3>Your Package</h3>
                        <p style="color: #007bff;">1</p>
                        <small>Plan Subscription</small>
                    </div>
                    <a href="package.php" class="card-icon">
                        <i class="fas fa-box"></i>
                    </a>
                </div>
                <!-- Billing Card -->
                <div class="card">
                    <div class="card-content">
                        <h3>Monthly Bill
                            <?php echo $latestBill ? date("F Y", strtotime($latestBill['DueDate'])) : 'Not Available'; ?>
                        </h3>

                        <?php if ($latestBill && $latestBill['Status'] == 'Paid'): ?>
                            <!-- If the bill is paid, show this -->
                            <p class="text-success">Already Paid</p>
                        <?php else: ?>
                            <!-- If the bill is not paid, show outstanding balance and due date -->
                            <p style="color: green;"><?php echo $latestBill ? '₱' . number_format($latestBill['OutstandingBalance'], 2) : '0.00'; ?></p>
                            <small>
                                <?php echo $latestBill ? 'Please pay before ' . date("d F Y", strtotime($latestBill['DueDate'])) : ''; ?>
                            </small>
                        <?php endif; ?>
                    </div>
                    <span class="bill-icon <?php echo !$latestBill ? 'disabled' : ''; ?>"
                        data-toggle="modal"
                        data-target="#billModal"
                        style="<?php echo !$latestBill ? 'pointer-events: none; opacity: 0.5;' : ''; ?>">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                </div>
            </div>
        </main>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-navbar">
        <a href="package.php" class="nav-item">
            <i class="fas fa-box"></i>
            <span>Package</span>
        </a>
        <a href="bill.php" class="nav-item">
            <i class="fas fa-file-invoice"></i>
            <span>Bill</span>
        </a>
        <a href="dashboard.php" class="nav-item active">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="complaint.php" class="nav-item">
            <i class="fas fa-comments"></i>
            <span>Complaint</span>
        </a>
        <a href="change_password.php" class="nav-item">
            <i class="fas fa-key"></i>
            <span>Password</span>
        </a>
    </nav>

    <!-- Modal Structure -->
    <div class="modal fade" id="billModal" tabindex="-1" aria-labelledby="billModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="billModalLabel">
                        <?php echo $latestBill ? date("F Y", strtotime($latestBill['DueDate'])) : 'Bill Details'; ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="upper" style="display:flex; justify-content: space-between; margin:0px 15px; ">
                        <p>Account No: <br><strong><?php echo $latestBill['ClientID'] ?? 'N/A'; ?></strong></p>

                        <p>Status: <br>
                            <span class="text-danger">
                                <?php
                                switch ($latestBill['Status']) {
                                    case 'Paid':
                                        echo '<span class="text-success">Paid</span>';
                                        break;
                                    case 'Half Paid':
                                        echo '<span class="text-warning">Half Paid</span>';
                                        break;
                                    case 'Partially Paid':
                                        echo '<span class="text-warning">Partially Paid</span>';
                                        break;
                                    default:
                                        echo '<span class="text-danger">Not Yet Paid</span>';
                                        break;
                                }
                                ?>
                            </span>
                        </p>
                    </div>
                    <p style="margin-bottom: 0; color:#6c757d; font-size: 0.8rem;">Total</p>
                    <h2 style="margin: 5px 0px;"><?php echo '₱' . number_format($latestBill['OutstandingBalance'], 2); ?></h2>
                    <p style="color:#6c757d; font-size: 0.8rem;">Billing details #<?php echo $latestBill['InvoiceNo'] ?? 'N/A'; ?></p>
                    <div class="lower">
                        <p style="display:flex; justify-content: space-between; margin:0px 15px;"><?php echo $plan['Plan'] ?? 'N/A'; ?>
                            <span><?php echo number_format($latestBill['DueAmount'], 2) ?? '0.00'; ?> / month</span>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-pay" style="width: 100px">Pay</button>
                    <button type="button" class="btn btn-info btn-invoice" style="width: 100px">
                        <?php echo ($latestBill && $latestBill['Status'] == 'Paid') ? 'Receipt' : 'Invoice'; ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentMethodModal" tabindex="-1" aria-labelledby="paymentMethodLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentMethodLabel">Payment Method</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please make payment according to your bill. Payment can be made via transfer to the following account:</p>
                    <ul>
                        <li>GCash: 09979840470 A/N Valentin Lacar III</li>
                    </ul>
                    <p>You can also pay your bill through the home collector. Please wait for our assigned collector to visit you for your convenience. Contact customer service if you have any questions.</p>
                    <p><strong>Payment Confirmation:</strong></p>
                    <ul>
                        <li>Email: vallacar21@gmail.com</li>
                        <li>Phone Number: 09979840470</li>
                        <li>Facebook/Messenger: Valentin Lacar III</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcement Detail Modal -->
    <div class="modal fade" id="announcementDetailModal" tabindex="-1" aria-labelledby="announcementDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAnnouncementTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modalAnnouncementMessage"></p>
                    <small class="text-muted" id="modalAnnouncementDate"></small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        });
        document.getElementById('closeSidebarBtn').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.remove('show');
        });

        //spinner
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to navigation links
            const navLinks = document.querySelectorAll('.nav-item');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent immediate navigation

                    // Show the spinner
                    document.getElementById('spinner').style.display = 'flex';

                    // Simulate delay for loading (or handle actual navigation)
                    setTimeout(() => {
                        // Proceed with navigation after showing the spinner
                        window.location.href = this.href;
                    }, 1000); // Adjust delay time as needed
                });
            });
        });
        $('.btn-invoice').click(function() {
            window.open('invoice.php?invoiceNo=<?php echo $latestBill["InvoiceNo"]; ?>', '_blank');
        });
        $(document).ready(function() {
            $('.btn-pay').click(function() {
                $('#paymentMethodModal').modal('show');
            });
        });

        $(document).ready(function() {
            // Toggle the visibility of the notification dropdown
            $('#notificationBell').on('click', function() {
                $('#announcementDropdown').toggle();
            });

            // Hide the dropdown if clicking outside of it
            $(document).on('click', function(event) {
                if (!$(event.target).closest('#notificationBell, #announcementDropdown').length) {
                    $('#announcementDropdown').hide();
                }
            });

            // Populate the announcement details modal when an announcement is clicked
            $('#announcementDropdown').on('click', '.list-group-item', function() {
                const title = $(this).data('title');
                const message = $(this).data('message');
                const date = $(this).data('date');

                $('#modalAnnouncementTitle').text(title);
                $('#modalAnnouncementMessage').html(message);
                $('#modalAnnouncementDate').text(date);

                // Show the announcement detail modal
                $('#announcementDetailModal').modal('show');
            });
        });
    </script>
</body>

</html>