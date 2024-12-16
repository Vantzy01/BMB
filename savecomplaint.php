<?php
session_start();

// Validate if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbinternet";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the save complaint form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate unique ComplaintID (e.g., C######)
    function generateComplaintID($conn) {
        do {
            $complaintID = 'C' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $sql = "SELECT ComplaintID FROM tblcomplaints WHERE ComplaintID = '$complaintID'";
            $result = $conn->query($sql);
        } while ($result && $result->num_rows > 0);
        return $complaintID;
    }

    $complaintID = generateComplaintID($conn);
    $clientID = $conn->real_escape_string($_POST['clientId']);
    $type = $conn->real_escape_string($_POST['topic']);
    $report = $conn->real_escape_string($_POST['report']);
    $remark = $conn->real_escape_string($_POST['detail']);
    $status = "Pending"; // Default status
    $dateReported = date("Y-m-d H:i:s"); // Current date and time

    // Insert into the tblcomplaints table
    $sql = "INSERT INTO tblcomplaints (ComplaintID, ClientID, Type, Report, Complaint, Status, DateReported)
            VALUES ('$complaintID', '$clientID', '$type', '$report', '$remark', '$status', '$dateReported')";

    if ($conn->query($sql) === TRUE) {
        // Set the success message in session
        $_SESSION['success'] = "Thank you for submitting your complaint. We have received your complaint and our team will review it as soon as possible. Please check your client portal or email for updates regarding the status of your complaint. We appreciate your patience and will do our best to resolve the issue promptly.";
        header("Location: successMessage.php");
        exit();
    } else {
        // Display an error message
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: filecomplaint.php");
        exit();
    }
}

$conn->close();
?>
