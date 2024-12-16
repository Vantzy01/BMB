<?php
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

// Get the topic (Type) from the AJAX request
$type = isset($_POST['type']) ? $_POST['type'] : '';

if (!empty($type)) {
    $stmt = $conn->prepare("SELECT Report FROM tblsolution WHERE Type = ?");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();

    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row['Report'];
    }

    // Return the reports as a JSON response
    echo json_encode($reports);
}

$conn->close();
?>
