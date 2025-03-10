<?php
include('db_connection.php');
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

    echo json_encode(['instruction' => $instruction]);
}
$conn->close();
