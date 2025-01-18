<?php
session_start();
include('db_connection.php'); // Include database connection

// Check if the CollectorID is set in the session
if (!isset($_SESSION['CollectorID'])) {
    header("Location: collector_login.php"); // Redirect to login if not set
    exit;
}

$collectorID = $_SESSION['CollectorID'];
// Fetch announcements from the database
$query = "SELECT Title, Message, DateCreated FROM tblannouncements ORDER BY DateCreated DESC";
$result = $conn->query($query);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Announcements</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        /* General styling */
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; color: #333; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
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

        /* Bottom Navigation Bar */
        .bottom-nav {
            display: flex;
            justify-content: space-around;
            background-color: #2C3E50;
            padding: 0.5em 0; /* Reduce padding for a smaller nav */
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1); /* Add a subtle shadow for modern design */
        }

        .bottom-nav a {
            color: white;
            text-decoration: none;
            font-size: 1em; /* Reduce font size for a smaller UI */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.3em; /* Space between icon and label */
        }

        .bottom-nav a i {
            font-size: 1.2em; /* Adjust icon size */
        }

        .bottom-nav .active {
            border-top: 2px solid #3498DB; /* Active indicator */
            padding-top: 0.5em; /* Slight padding to align with design */
        }

        .bottom-nav a span {
            font-size: 0.75em; /* Smaller label text */
        }

        /* Announcement card styling */
        .announcement-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px; /* Spacing between cards */
            padding: 20px;
            transition: transform 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            max-height: 80px; /* Default collapsed height */
            position: relative;
        }

        .announcement-card.expanded {
            max-height: 1000px; /* Expanded height */
            transition: max-height 1s ease;
        }

        .announcement-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .announcement-icon {
            font-size: 24px;
            color: #007bff;
            margin-right: 10px;
        }

        .announcement-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .announcement-date {
            font-size: 14px;
            color: #777;
            margin-left: auto;
        }

        .announcement-message {
            color: #555;
            line-height: 1.6;
            display: none; /* Hide message initially */
        }

        /* Show message when expanded */
        .announcement-card.expanded .announcement-message {
            display: block;
        }
        /* Responsive Design */
        @media (max-width: 600px) {
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
                <a href="coll_logout.php" style="color: white;">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h2><i class="fas fa-bullhorn"></i> Announcements</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="announcement-card" onclick="toggleExpand(this)">
                    <div class="announcement-header">
                        <i class="fas fa-bell announcement-icon"></i>
                        <h2 class="announcement-title"><?php echo htmlspecialchars($row['Title']); ?></h2>
                        <span class="announcement-date"><?php echo date("M d, Y", strtotime($row['DateCreated'])); ?></span>
                    </div>
                    <p class="announcement-message"><?php echo nl2br(htmlspecialchars($row['Message'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; color: #666;">No announcements available at this time.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>

    <!-- Bottom Navigation Bar -->
    <footer>
        <nav class="bottom-nav">
            <a href="collector_dash.php" >
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
            <a href="collector_announcement.php" class="active">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
        </nav>
    </footer>

    <!-- JavaScript to handle expanding/collapsing announcement cards -->
    <script>
        function toggleExpand(element) {
            element.classList.toggle('expanded');
        }
    </script>
</body>
</html>
