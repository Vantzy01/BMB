<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbinternet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $mobileNo = $_POST['mobileNo'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $package = $_POST['package'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    
    // Check if email already exists
    $emailCheckSql = "SELECT * FROM tblclient WHERE Email = ?";
    $stmt = $conn->prepare($emailCheckSql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "This email is already registered. Please use a different email.";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Generate a unique ClientID
    $clientId = "CL-ID-" . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    // Prepare the SQL statement
    $sql = "INSERT INTO tblclient (ClientID, FullName, MobileNumber, Email, Username, Password, Address, PlanID, Longitude, Latitude, Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $username = strtolower(explode(' ', $fullName)[0]); // Simple username generation from first name
    $status = "Waiting";

    $stmt->bind_param("ssssssssdds", 
        $clientId, $fullName, $mobileNo, $email, $username, $password, $address, $package, 
        $longitude, $latitude, $status
    );

    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
