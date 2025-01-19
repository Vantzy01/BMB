<?php
session_start();

include('db_connection.php');

// Generate a unique reference number for the payment
$referenceNo = 'REF' . rand(100000, 999999);
$invoiceNo = $_POST['invoiceNo'];
$clientID = $_POST['clientID'];
$paymentMethod = $_POST['paymentMethod'];
$period = $_POST['period'];
$amount = $_POST['amount'];
$lastBill = $_POST['lastBill'];
$paymentStatus = ($paymentMethod == 'Gcash') ? 'Not Confirmed' : 'Waiting';
$paymentDate = date('Y-m-d H:i:s');

// Initialize attachment variable
$attachment = null;

// Handle file upload for GCash payments
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is an actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        $attachment = $target_file; // Store the file path in the $attachment variable
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Prepare the SQL statement
$sql = "INSERT INTO tblpayment (ReferenceNo, InvoiceNo, ClientID, PaymentMethod, Period, Amount, Attachment, PaymentStatus, PaymentDate, LastBill) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind all parameters including the $attachment variable
$stmt->bind_param("sssssdssss", $referenceNo, $invoiceNo, $clientID, $paymentMethod, $period, $amount, $attachment, $paymentStatus, $paymentDate, $lastBill);

if ($stmt->execute()) {
    // Redirect to success page
    header("Location: success.php");
    exit;
} else {
    // Display an error message if execution fails
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();


