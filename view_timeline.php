<?php
require 'db_connection.php'; // Include database connection

if (!isset($_GET['id'])) {
    echo '<p class="error">Invalid request</p>';
    exit;
}

$complaintID = $_GET['id'];

// Fetch complaint details
$query = "SELECT * FROM tblcomplaints WHERE ComplaintID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $complaintID);
$stmt->execute();
$result = $stmt->get_result();
$complaint = $result->fetch_assoc();
$stmt->close();

// Fetch complaint actions
$query = "SELECT * FROM tblcomplaintact WHERE ComplaintID = ? ORDER BY DateUpdated ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $complaintID);
$stmt->execute();
$result = $stmt->get_result();
$actions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<div class="timeline timed ms-1 me-2">
    <div class="item">
        <span class="time"><?php echo htmlspecialchars($complaint['DateReported']); ?></span>
        <div class="dot bg-primary"></div>
        <div class="content">
            <h4 class="title">Complaint Filed</h4>
            <div class="text">Status: <?php echo htmlspecialchars($complaint['Status']); ?><br>
                Created by: Client ID <?php echo htmlspecialchars($complaint['ClientID']); ?><br>
                Report: <?php echo htmlspecialchars($complaint['Report']); ?>
            </div>
        </div>
    </div>
    
    <?php foreach ($actions as $action): ?>
        <div class="item">
            <span class="time"><?php echo htmlspecialchars($action['DateUpdated']); ?></span>
            <div class="dot bg-success"></div>
            <div class="content">
                <h4 class="title">Action Taken: <?php echo htmlspecialchars($action['ActionTaken']); ?></h4>
                <div class="text">Remark: <?php echo htmlspecialchars($action['Remark']); ?><br>
                    Updated by: <?php echo htmlspecialchars($action['Updatedby']); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
