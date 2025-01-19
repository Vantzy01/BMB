<?php
include('db_connection.php');

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
