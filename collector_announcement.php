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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        /* General styling */
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
            <a href="collector_dash.php">Dashboard</a>
            <a href="collector_billing.php">Billing</a>
            <a href="collector_collection.php">Collection</a>
            <a href="collector_map.php">Map</a>
            <a href="collector_announcement.php" class="active">Announcements</a>
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
