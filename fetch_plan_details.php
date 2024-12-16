<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $planID = $_POST['planID'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "dbinternet");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT Plan, MonthlyCost, Description FROM tblplan WHERE PlanID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $planID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $planDetails = $result->fetch_assoc();
        echo json_encode($planDetails);
    } else {
        echo json_encode([]);
    }

    $stmt->close();
    $conn->close();
}
