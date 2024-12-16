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

// Get the report from the AJAX request
$report = isset($_POST['report']) ? $_POST['report'] : '';

if (!empty($report)) {
    $stmt = $conn->prepare("SELECT Instruction FROM tblsolution WHERE Report = ?");
    $stmt->bind_param("s", $report);
    $stmt->execute();
    $result = $stmt->get_result();

    $instruction = '';
    if ($row = $result->fetch_assoc()) {
        $instruction = $row['Instruction'];
    }

    // Return the instruction as a JSON response
    echo json_encode(['instruction' => $instruction]);
}

$conn->close();
?>
