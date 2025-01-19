<?php
session_start();

$clientID = $_SESSION['clientID'];
// Check if the request is coming via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['clientID']) || !isset($_POST['message'])) {
        echo "Invalid request.";
        exit();
    }

    $clientID = $_SESSION['clientID'];
    $message = trim($_POST['message']);

    if (empty($message)) {
        echo "Message cannot be empty.";
        exit();
    }

    include('db_connection.php');

    function generateComplaintID($conn) {
        $complaintID = 'C' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $sql = "SELECT COUNT(*) as count FROM tblcomplaints WHERE ComplaintID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $complaintID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['count'] > 0) {
            return generateComplaintID($conn);
        }

        return $complaintID;
    }

    $complaintID = generateComplaintID($conn);

    $dateReported = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS

    // Prepare SQL query to insert a new complaint into the tblcomplaints table
    $sql = "INSERT INTO tblcomplaints (ComplaintID, ClientID, Message, Status, DateReported) VALUES (?, ?, ?, 'Pending', ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind the parameters to the SQL query, ensuring all are treated as strings
    $stmt->bind_param("ssss", $complaintID, $clientID, $message, $dateReported);

    // Execute the query
    if ($stmt->execute()) {
        echo "Complaint submitted successfully!";
    } else {
        echo "Error submitting complaint: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
