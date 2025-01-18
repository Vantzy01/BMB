<?php
session_start();

// Validate if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['clientID'])) {
    $_SESSION['clientID'] = $_GET['clientID'];
}

// Ensure clientID is set correctly
$clientID = isset($_SESSION['clientID']) ? $_SESSION['clientID'] : null;
$fullName = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : null;

// Check for a valid clientID before proceeding
if (!$clientID) {
    die("Client ID is not available.");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbinternet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch complaints based on the client's ID
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
    unset($_SESSION['success']); // Clear the success message from the session
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']); // Clear the error message from the session
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Complaints - BMB Internet Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
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
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background-color: #007bff;
            color: #fff;
        }
        .top-navbar .notification-icon {
            cursor: pointer;
        }

        .main-content {
            margin-left: 0;
            padding: 20px;
            flex-grow: 1;
            transition: margin-left 0.3s;
        }

        .card-container {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }

        .card {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .card-content {
            flex: 1;
            padding-right: 20px;
        }

        .card-icon {
            font-size: 3em;
            color: #007bff;
            transition: transform 0.3s ease, color 0.3s ease;
            cursor: pointer;
        }

        .card-icon:hover {
            transform: scale(1.2);
            color: #0056b3;
        }

        .bill-icon {
            font-size: 3em;
            color: #dc3545;
            transition: transform 0.3s ease, color 0.3s ease;
            cursor: pointer;
        }

        .bill-icon:hover {
            transform: scale(1.2);
            color: #c82333;
        }

        .bill-icon.disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
            justify-content: center;
        }

        /* Combined header styles */  
        .modal-header, .modal-footer {  
            border-bottom: none;  
        } 

        .btn-pay,  
        .btn-confirm,  
        .btn-invoice {  
            margin: 5px;  
            border: none;  
            cursor: pointer;  
        } 

        .btn-pay, .btn-confirm, .btn-invoice {
            margin: 5px;
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

        .btn-pay, .btn-confirm, .btn-invoice {
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

        .btn-pay:hover, .btn-confirm:hover, .btn-invoice:hover {
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

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-body {
            text-align: center;
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
            <a> BMB Internet Service Aurora</a>
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
            <div class="container mt-5">
                <!-- Report Disturbance Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold">Report Disturbance</h5>
                        <p class="card-text">The solution solves your problem</p>
                        <button class="btn btn-secondary mr-2" id="sendComplaintBtn">Send Complaint</button>
                        <button class="btn btn-outline-warning" id="historyBtn">History</button>
                    </div>
                </div>

                <!-- Contact Us Section -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold">Contact Us</h5>
                        <p class="card-text">Chat with us to find out information about services</p>
                        <button class="btn btn-success mr-2">WhatsApp</button>
                        <button class="btn btn-primary mr-2">Telegram</button>
                        <button class="btn btn-danger">Email</button>
                    </div>
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

    <!-- History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4A90E2;">
                    <h5 class="modal-title text-white" id="historyModalLabel">Complaint History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #f8f9fa;">
                    <?php if (empty($complaints)): ?>
                        <p class="text-center">No complaints found</p>
                    <?php else: ?>
                        <?php foreach ($complaints as $complaint): ?>
                            <?php
                            // Determine the card color based on the status
                            $cardColor = '';
                            switch (strtolower($complaint['Status'])) {
                                case 'pending':
                                    $cardColor = 'linear-gradient(90deg, #ffb74d, #f57c00)';
                                    break;
                                case 'processing':
                                    $cardColor = 'linear-gradient(90deg, #42a5f5, #1e88e5)';
                                    break;
                                case 'done':
                                    $cardColor = 'linear-gradient(90deg, #66bb6a, #43a047)';
                                    break;
                                default:
                                    $cardColor = 'linear-gradient(90deg, #e0e0e0, #bdbdbd)';
                                    break;
                            }
                            ?>
                            <div class="p-4 mb-3 text-white" style="background: <?php echo $cardColor; ?>; border-radius: 10px;">
                                <div class="mb-2">
                                    <h6 style="font-weight: bold; margin-bottom: 10px;">#<?php echo htmlspecialchars($complaint['ComplaintID']); ?></h6>
                                </div>
                                <div class="mb-2">
                                    <p style="font-weight: bold; margin-bottom: 5px;">Client ID</p>
                                    <p><?php echo htmlspecialchars($complaint['ClientID']); ?></p>
                                </div>
                                <div class="mb-2">
                                    <p style="font-weight: bold; margin-bottom: 5px;">Complaint</p>
                                    <p><?php echo htmlspecialchars($complaint['Complaint']); ?></p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p style="font-weight: bold; margin-bottom: 5px;">STATUS</p>
                                        <p><?php echo htmlspecialchars($complaint['Status']); ?></p>
                                    </div>
                                    <div>
                                        <p style="font-weight: bold; margin-bottom: 5px;">DATE</p>
                                        <p><?php echo date("d M Y H:i:s", strtotime($complaint['DateReported'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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

            $('#historyBtn').on('click', function() {
                $('#historyModal').modal('show');
            });
        });
    </script>
    <script>
        // Add event listener to the Send Complaint button
        document.getElementById('sendComplaintBtn').addEventListener('click', function () {
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