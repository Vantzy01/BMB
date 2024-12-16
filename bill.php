<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$clientID = $_SESSION['clientID'];
$fullName = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : null;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbinternet";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch billing records for the logged-in client
$sql = "SELECT b.InvoiceNo, b.DueDate, b.Period, p.Plan, b.DueAmount, b.Discount, b.AmountPaid, b.OutstandingBalance, b.Status 
        FROM tblbilling b 
        JOIN tblplan p ON b.PlanID = p.PlanID
        WHERE b.ClientID = ?
        ORDER BY b.DueDate DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $clientID);
$stmt->execute();
$result = $stmt->get_result();
$bills = $result->fetch_all(MYSQLI_ASSOC);


$announcementSql = "SELECT * FROM tblannouncements ORDER BY DateCreated DESC";
$announcementResult = $conn->query($announcementSql);
$announcements = $announcementResult->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Billing - BMB Internet Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
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

        .wrapper {
            display: flex;
            flex: 1;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            background-color: #ffffff;
            padding-top: 50px;
            position: fixed;
            left: -250px;
            transition: left 0.3s ease-in-out;
            box-shadow: 3px 0 5px rgba(0,0,0,0.2);
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
            z-index: 1000;
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
            background: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }

        

        .card {
            border-radius: 10px;
            border: none;
        }

        .card-header {
            border-bottom: none;
            font-weight: bold;
        }

        .card-body {
            font-size: 1rem;
            line-height: 1.6;
        }

        .text-primary {
            color: #343a40 !important;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 10px 0;
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

        /* Modal Header */
        .modal-header.bg-primary {
            background-color: #007bff;
            border-bottom: 2px solid #0056b3;
        }

        /* Payment Timeline Items */
        #paymentTimeline .list-group-item {
            border-left: 5px solid #17a2b8;
            padding: 15px 20px;
            margin-bottom: 5px;
            transition: all 0.3s ease-in-out;
        }

        /* Outstanding Balance Styling */
        .outstanding-balance {
            font-weight: bold;
            font-size: 0.9em;
            /* Reduced font size */
            color: #dc3545;
        }

        /* Hover Effects */
        #paymentTimeline .list-group-item:hover {
            background-color: #f1f1f1;
            border-left-width: 10px;
        }

        .table-borderless {
            border-collapse: separate;
            border-spacing: 0 10px;
            width: 1100px;
        }

        .table-borderless thead th {
            border: none;
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 10px;
            text-align: left;
        }

        .table-borderless tbody tr {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .table-borderless tbody td {
            padding: 10px;
            border: none;
        }

        .table-borderless tbody tr:hover {
            background-color: #f1f1f1;
        }


        .badge {
            padding: 0.5em 0.75em;
            /* Padding for badges */
            border-radius: 0.5rem;
            /* Rounded corners */
            font-size: 0.8em;
            /* Smaller text */
        }

        .badge-paid {
            background-color: #28a745;
            color: #fff;
        }

        .badge-not-paid {
            background-color: #dc3545;
            color: #fff;
        }

        .badge-partially-paid {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-half-paid {
            background-color: #17a2b8;
            color: #fff;
        }

        /* Responsive Table */
        .table-responsive {
            border: none;
            overflow-x: auto;
            margin-top: 15px;
        }

        /* Additional Styling */
        .table td {
            vertical-align: middle;
            padding: 12px;
            /* Add padding for a clean look */
        }

        /* Center align for the no records found message */
        .text-center {
            text-align: center;
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
        @media (max-width: 768px) {
            .modal-content {  
                margin: 10px;  
            }  
        }
    </style>
</head>
 
<body>
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
            <a> BMB CLIENT PORTAL</a>
        </div>
        <!-- <div>
            <i class="fas fa-user-circle"></i> <span id="client-name"><?php echo htmlspecialchars($fullName); ?></span> 
        </div> -->
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
    <div class="wrapper">
        <!-- Main Content Area -->
        <main class="main-content">
            <div class="container mt-5">
                <h2>Billing History</h2>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Due Date</th>
                                <th>Period</th>
                                <!-- <th>Plan</th> -->
                                <th>Due Amount</th>
                                <th>Discount</th>
                                <th>Amount Paid</th>
                                <!-- <th>Outstanding Balance</th> -->
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bills)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No billing records found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bills as $bill): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($bill['InvoiceNo']); ?></td>
                                        <td><?php echo date("d M Y", strtotime($bill['DueDate'])); ?></td>
                                        <td><?php echo htmlspecialchars($bill['Period']); ?></td>
                                        <!-- <td><?php echo htmlspecialchars($bill['Plan']); ?></td> -->
                                        <td>₱<?php echo number_format($bill['DueAmount'], 2); ?></td>
                                        <td>₱<?php echo number_format($bill['Discount'], 2); ?></td>
                                        <td>₱<?php echo number_format($bill['AmountPaid'], 2); ?></td>
                                        <!-- <td>₱<?php echo number_format($bill['OutstandingBalance'], 2); ?></td> -->
                                        <td>
                                            <?php
                                            // Fetch and classify the status
                                            $status = htmlspecialchars($bill['Status']);
                                            $statusClass = '';

                                            switch ($status) {
                                                case 'Paid':
                                                    $statusClass = 'badge badge-paid';
                                                    break;
                                                case 'Not Yet Paid':
                                                    $statusClass = 'badge badge-not-paid';
                                                    break;
                                                case 'Partially Paid':
                                                    $statusClass = 'badge badge-partially-paid';
                                                    break;
                                                case 'Half Paid':
                                                    $statusClass = 'badge badge-half-paid';
                                                    break;
                                                default:
                                                    $statusClass = 'badge badge-secondary';
                                            }
                                            ?>
                                            <span class="<?php echo $statusClass; ?> payment-status"
                                                data-invoice-no="<?php echo htmlspecialchars($bill['InvoiceNo']); ?>"
                                                style="cursor: pointer;">
                                                <?php echo $status; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
        <a href="bill.php" class="nav-item active">
            <i class="fas fa-file-invoice"></i>
            <span>Bill</span>
        </a>
        <a href="dashboard.php" class="nav-item">
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

    <!-- Payment Timeline Modal -->
    <div class="modal fade" id="paymentTimelineModal" tabindex="-1" aria-labelledby="paymentTimelineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentTimelineModalLabel">
                        <i class="fas fa-receipt"></i> Payment History
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="paymentTimeline">
                        <!-- Payment details will be loaded here via AJAX -->
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
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        });
        document.getElementById('closeSidebarBtn').addEventListener('click', function () {
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
    <script>
        $(document).ready(function() {
            // Listen for clicks on status badges
            $('.payment-status').on('click', function() {
                const invoiceNo = $(this).data('invoice-no');

                // AJAX request to fetch payment history for the clicked invoice
                $.ajax({
                    url: 'fetch_payment_details.php',
                    type: 'POST',
                    data: {
                        invoiceNo: invoiceNo
                    },
                    success: function(response) {
                        // Populate the modal with the payment timeline data
                        $('#paymentTimeline').html(response);
                        // Show the modal
                        $('#paymentTimelineModal').modal('show');
                    },
                    error: function() {
                        alert('Error fetching payment details. Please try again later.');
                    }
                });
            });
        });
    </script>
</body>
</html>