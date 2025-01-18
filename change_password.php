<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$clientID = $_SESSION['clientID'];
$fullName = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : null;
$message = "";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbinternet";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the password change request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if new passwords match
    if ($newPassword !== $confirmPassword) {
        $message = "New passwords do not match.";
    } else {
        // Fetch the current password from the database
        $sql = "SELECT Password FROM tblclient WHERE ClientID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $clientID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify the current password
        if ($user && $currentPassword === $user['Password']) {
            // Update the new password without hashing
            $updateSql = "UPDATE tblclient SET Password = ? WHERE ClientID = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ss", $newPassword, $clientID);

            if ($updateStmt->execute()) {
                $message = "Password changed successfully.";
            } else {
                $message = "Error updating password.";
            }

            $updateStmt->close();
        } else {
            $message = "Current password is incorrect.";
        }

        $stmt->close();
    }
}

$announcementSql = "SELECT * FROM tblannouncements ORDER BY DateCreated DESC";
$announcementResult = $conn->query($announcementSql);
$announcements = $announcementResult->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - BMB Internet Service</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
</head>
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
.main-content.sidebar-open {
    margin-left: 250px;
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
                <h2>Change Password</h2>
                <?php if (!empty($message)): ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>
                <form method="post" action="change_password.php">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
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
        <a href="complaint.php" class="nav-item">
            <i class="fas fa-comments"></i>
            <span>Complaint</span>
        </a>
        <a href="change_password.php" class="nav-item active">
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
</body>
</html>
