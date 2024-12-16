<?php
session_start();

$clientID = $_SESSION['clientID'];
// Check if the request is coming via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate if clientID is present in the session
    if (!isset($_SESSION['clientID']) || !isset($_POST['message'])) {
        echo "Invalid request.";
        exit();
    }

    // Capture clientID and message from the session and POST data
    $clientID = $_SESSION['clientID'];
    $message = trim($_POST['message']);

    // Check if the message is empty
    if (empty($message)) {
        echo "Message cannot be empty.";
        exit();
    }

    // Database connection credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dbinternet";

    // Create connection to MySQL database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Generate a unique ComplaintID in the format: C######
    function generateComplaintID($conn) {
        $complaintID = 'C' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $sql = "SELECT COUNT(*) as count FROM tblcomplaints WHERE ComplaintID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $complaintID); // Bind as string
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        // If ID exists, generate a new one
        if ($row['count'] > 0) {
            return generateComplaintID($conn);
        }

        return $complaintID;
    }

    // Call the function to generate a unique ComplaintID
    $complaintID = generateComplaintID($conn);

    // Manually set the DateReported (Current DateTime)
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
