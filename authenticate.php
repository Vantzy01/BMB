<?php
include('db_connection.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = trim($_POST['username']);
    $inputPassword = trim($_POST['password']);

    // Validate form fields
    if (empty($inputUsername) || empty($inputPassword)) {
        echo "<script>alert('Please fill in all fields.'); window.location.href = 'login.php';</script>";
        exit();
    }

    // Check credentials in the database
    $sql = "SELECT * FROM tblclient WHERE Username = ? AND Password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $inputUsername, $inputPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, fetch client data
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['Username'];
        $_SESSION['fullname'] = $row['FullName'];
        $_SESSION['clientID'] = $row['ClientID'];
        $_SESSION['planID'] = $row['PlanID'];


        // Pass ClientID as a query parameter in the URL
        $clientID = $row['ClientID'];
        $planID = $row['PlanID'];
        $fullName = urlencode($row['FullName']);
        echo "<script>alert('Login successful!'); window.location.href = 'dashboard.php?clientID=$clientID&planID=$planID&fullName=$fullName';</script>";
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid username or password.'); window.location.href = 'login.php';</script>";
    }

    $stmt->close();
}

$conn->close();
