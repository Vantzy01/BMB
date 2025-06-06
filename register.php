<?php
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['fullName']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $mobileNo = trim($_POST['mobileNo']);
    $password2 = trim($_POST['password2']);
    $address = trim($_POST['address']);
    $package = trim($_POST['package']);
    $longitude = trim($_POST['longitude']);
    $latitude = trim($_POST['latitude']);
    $status = "Waiting";

    // Check for duplicate Username
    $checkUsername = "SELECT 1 FROM tblclient WHERE Username = ?";
    $stmt = $conn->prepare($checkUsername);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Username already exists!'); window.history.back();</script>";
        exit();
    }

    // Check for duplicate Email
    $checkEmail = "SELECT 1 FROM tblclient WHERE Email = ?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.history.back();</script>";
        exit();
    }

    // Check for duplicate Mobile Number
    $checkMobile = "SELECT 1 FROM tblclient WHERE MobileNumber = ?";
    $stmt = $conn->prepare($checkMobile);
    $stmt->bind_param("s", $mobileNo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Mobile Number already exists!'); window.history.back();</script>";
        exit();
    }

    do {
        $randomNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $clientId = 'CL-ID-' . $randomNumber;

        $query = "SELECT ClientID FROM tblclient WHERE ClientID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
    } while ($result->num_rows > 0);

    // Insert new client record
    $sql = "INSERT INTO tblclient (ClientID, FullName, MobileNumber, Email, Username, Password, Address, PlanID, Longitude, Latitude, Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssdds", $clientId, $fullName, $mobileNo, $email, $username, $password2, $address, $package, $longitude, $latitude, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Redirecting to login.'); window.location.href = 'login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error occurred during registration. Please try again.'); window.history.back();</script>";
        exit();
    }
}
