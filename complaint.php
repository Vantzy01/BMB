<?php
session_start();

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

include('db_connection.php');

$complaintSql = "SELECT * FROM tblcomplaints WHERE ClientID = ? ORDER BY DateReported DESC";
$complaintStmt = $conn->prepare($complaintSql);
$complaintStmt->bind_param("s", $clientID);
$complaintStmt->execute();
$complaintResult = $complaintStmt->get_result();
$complaints = $complaintResult->fetch_all(MYSQLI_ASSOC);
$complaintStmt->close();

$announcementSql = "SELECT * FROM tblannouncements ORDER BY DateCreated DESC";
$announcementResult = $conn->query($announcementSql);
$announcements = $announcementResult->fetch_all(MYSQLI_ASSOC);

$conn->close();

$hasPendingComplaint = false;
foreach ($complaints as $complaint) {
    if ($complaint['Status'] === 'Pending') {
        $hasPendingComplaint = true;
        break;
    }
}
$hasProcessingComplaint = false;
foreach ($complaints as $complaint) {
    if ($complaint['Status'] === 'Processing') {
        $hasProcessingComplaint = true;
        break;
    }
}

if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Images/logo.ico" />
    <title>Complaint - BMB Cell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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
            z-index: 100000;

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
            position: fixed;
            width: 100%;
            top: 0;
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background-color: #007bff;
            color: #fff;
            z-index: 10000;
        }

        .top-navbar .notification-icon {
            cursor: pointer;
        }

        .top-navbar .collapse-btn {
            cursor: pointer;
        }

        .wrapper {
            display: flex;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 50px;
            padding: 20px;
            width: 100%;
        }

        .card {
            display: inline-block;
            width: 100%;
            padding: 20px;
            background: #f8f9fa;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-content {
            padding-right: 20px;
        }

        .modal-header {
            border-bottom: none;
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

        .btn {
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-outline-warning {
            border-color: #ffcc00;
            color: #ffcc00;
        }

        .btn-success {
            background-color: #25d366;
            color: #fff;
        }

        .btn-primary {
            background-color: #0088cc;
            color: #fff;
        }

        .btn-danger {
            background-color: #d93025;
            color: #fff;
        }

        .font-weight-bold {
            font-weight: 700;
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

        /* Responsive Design */
        @media (max-width: 600px) {
            .modal-content {
                margin: 10px;
            }

            .bottom-navbar span {
                display: none;
            }
        }

        .history {
            margin-top: 30px;
            width: 100%;
        }

        .table-container {
            display: inline-block;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .history h5 {
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .complaints-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .complaints-table th,
        .complaints-table td {
            padding: 15px 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .complaints-table th {
            background: #f1f1f1;
            color: gray;
        }

        .complaints-table tr:hover {
            background: #f1f1f1;
            cursor: pointer;
        }

        .status.pending {
            color: #ff9800;
            font-weight: bold;
        }

        .status.done {
            color: #4caf50;
            font-weight: bold;
        }

        .status.processing {
            color: #f44336;
            font-weight: bold;
        }

        .action-cell {
            text-align: center;
        }

        .complaints-table th:nth-child(1),
        .complaints-table td:nth-child(1) {
            display: none;
        }

        .complaints-table td:nth-child(4) {
            font-size: 0.8rem;
        }

        @media (max-width: 460px) {
            .card {
                padding: 10px;
                margin-bottom: 10px;
            }

            .card-title {
                font-size: 1rem;
            }

            .card-body {
                font-size: 0.8rem;
            }

            .btn {
                font-size: 0.8rem;
            }

            .history {
                margin-top: 10px;
            }

            .history h5 {
                font-size: 1rem;
            }

            .complaints-table td,
            .complaints-table th {
                font-size: 0.6rem;
            }

            .complaints-table td:nth-child(4) {
                font-size: 0.6rem;
            }
        }

        /* timeline */
        .modal-timeline {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10055;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
            background: linear-gradient(45deg, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4));
        }

        .modal-timeline .modal-dialogt {
            padding: 0;
            margin: 0;
            bottom: 0;
            position: fixed;
            width: 100%;
            min-width: 100%;
            z-index: 19000;
        }

        .modal-dialogt {
            position: relative;
            width: auto;
            margin: .5rem;
            pointer-events: none;
        }

        .modal-timeline .modal-contentt {
            box-shadow: none;
            border: 0;
            border-radius: 0;
            padding-bottom: env(safe-area-inset-bottom);
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .2);
            border-radius: .3rem;
            outline: 0;

        }





        .modal-timeline .modal-contentt .modal-headert {
            display: block;
            padding: 2px 20px;
        }

        .modal-headert {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1rem;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: calc(.3rem - 1px);
            border-top-right-radius: calc(.3rem - 1px);
        }

        .modal-timeline .modal-contentt .modal-headert .modal-titlet {
            padding: 0;
            margin: 0;
            text-align: center;
            display: block;
            font-size: 15px;
            padding: 10px 0;
            color: #27173E;
            font-weight: 500;
            line-height: 1.5;
        }

        .modal-bodyt {
            position: relative;
            flex: 1 1 auto;
            padding: 1rem;
        }

        .modal.action-sheet .modal-content .action-sheet-content {
            padding: 20px 16px;
            max-height: 460px;
            overflow: auto;
        }

        .cardt {
            background: #ffffff;
            border-radius: 10px;
            border: 0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.09);
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: .25rem;
        }

        .timeline.timed {
            padding-left: 80px;
        }

        .timeline {
            position: relative;
            padding: 24px 0;
        }

        .timeline.timed:before {
            left: 80px;
        }

        .timeline:before {
            content: '';
            display: block;
            position: absolute;
            width: 2px;
            left: 0;
            bottom: 0;
            top: 0;
            background: #DCDCE9;
            z-index: 1;
        }

        .timeline .item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline.timed .time {
            font-size: 11px;
            position: absolute;
            left: -80px;
            line-height: 1.5em;
            width: 70px;
            text-align: right;
            top: 1px;
            z-index: 20;
        }

        .timeline .dot {
            width: 12px;
            height: 12px;
            border-radius: 100%;
            position: absolute;
            background: #A9ABAD;
            left: -5px;
            top: 4px;
            z-index: 10;
            background: #6236ff;
            color: #FFF;
        }

        .timeline .content {
            padding-left: 20px;
        }

        .timeline .content .title {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 8px;
            line-height: 1.3em;
        }

        .timeline .content .text {
            font-size: 13px;
            line-height: 1.4em;
        }
    </style>
</head>

<body>
    <div id="spinner" class="spinner" style="display: none;">
        <div class="loader"></div>
    </div>
    <!-- Top Navbar -->
    <header class="top-navbar">
        <div class="collapse-btn" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </div>
        <div class="top-title">
            <a> BMB Cell and Computer Shop</a>
        </div>
        <div class="notification-icon">
            <i class="fas fa-bell" id="notificationBell"></i>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <button class="close-btn" id="closeSidebarBtn">&times;</button>
        <div class="client-info">
            <i class="fas fa-user"></i> <span id="client-fullname"><?php echo htmlspecialchars($fullName); ?></span>
        </div>
        <a href="dashboard.php"><i class="fas fa-home" class="nav-item"></i> Home</a>
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
        <main class="main-content" id="mainContent">
            <!-- Report Disturbance Section -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Report Disturbance</h5>
                    <p class="card-text">The solution solves your problem</p>
                    <button class="btn btn-secondary" id="sendComplaintBtn">Send Complaint</button>
                </div>
            </div>
            <!-- Complaint History -->
            <div class="history">
                <h5>Complaint History</h5>
                <div class="table-container">
                    <?php if (empty($complaints)): ?>
                        <p class="no-complaints">No complaints found</p>
                    <?php else: ?>
                        <table class="complaints-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Report</th>
                                    <th>Status</th>
                                    <th>Date Reported</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($complaints as $complaint): ?>
                                    <tr class="complaint-row" data-id="<?php echo $complaint['ComplaintID']; ?>">
                                        <td><?php echo htmlspecialchars($complaint['ComplaintID']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['Type']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['Report']); ?></td>
                                        <td class="status <?php echo strtolower(htmlspecialchars($complaint['Status'])); ?>">
                                            <?php echo htmlspecialchars($complaint['Status']); ?>
                                        </td>
                                        <td><?php echo date("M d, Y", strtotime($complaint['DateReported'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Timeline Modal -->
    <div class="modal-timeline " id="timeline-modal" tabindex="-1" role="dialog" aria-modal="true" style="display: none;">
        <div class="modal-dialogt animate__animated animate__fadeInUp" role="document">
            <div class="modal-contentt">
                <div class="modal-headert">
                    <h5 class="modal-titlet">Timeline</h5>
                    <span class="close-modal"></span>
                </div>
                <div class="modal-bodyt">
                    <div class="action-sheet-contentt">
                        <div class="cardt" id="timeline-content"></div>
                    </div>
                </div>
            </div>
        </div>
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
        <a href="dashboard.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="complaint.php" class="nav-item active">
            <i class="fas fa-comments"></i>
            <span>Complaint</span>
        </a>
        <a href="change_password.php" class="nav-item">
            <i class="fas fa-key"></i>
            <span>Password</span>
        </a>
    </nav>

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

    <!-- Custom Message Box Modal -->
    <div class="modal fade" id="customMessageBox" tabindex="-1" aria-labelledby="customMessageBoxLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customMessageBoxLabel">Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="customMessageBoxBody">
                    <!-- Message content will go here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
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

        $(document).ready(function() {
            $('#notificationBell').on('click', function() {
                $('#announcementDropdown').toggle();
            });

            // Hide the dropdown if clicking outside of it
            $(document).on('click', function(event) {
                if (!$(event.target).closest('#notificationBell, #announcementDropdown').length) {
                    $('#announcementDropdown').hide();
                }
            });

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

        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll(".complaint-row");
            const modal = document.getElementById("timeline-modal");
            const closeModal = document.querySelector(".close-modal");
            const timelineContent = document.getElementById("timeline-content");

            rows.forEach(row => {
                row.addEventListener("click", function() {
                    const complaintId = this.getAttribute("data-id");
                    fetch(`view_timeline.php?id=${complaintId}`)
                        .then(response => response.text())
                        .then(data => {
                            timelineContent.innerHTML = data;
                            modal.style.display = "block";
                        });
                });
            });

            closeModal.addEventListener("click", function() {
                modal.style.display = "none";
            });

            window.addEventListener("click", function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        });
    </script>
    <script>
        // Add event listener to the Send Complaint button
        document.getElementById('sendComplaintBtn').addEventListener('click', function() {
            <?php if ($hasPendingComplaint): ?>
                // If a Pending complaint exists
                $('#customMessageBoxLabel').text('Pending Complaint');
                $('#customMessageBoxBody').text('You already have a pending complaint. Please wait until it is resolved.');
                $('#customMessageBox').modal('show');
            <?php elseif ($hasProcessingComplaint): ?>
                // If a complaint is under processing
                $('#customMessageBoxLabel').text('Processing Complaint');
                $('#customMessageBoxBody').text('You have a complaint currently being processed. Please check your portal or email for updates.');
                $('#customMessageBox').modal('show');
            <?php else: ?>
                // Redirect to the file complaint page if no complaints are pending or processing
                window.location.href = `filecomplaint.php`;
            <?php endif; ?>
        });
    </script>
</body>

</html>