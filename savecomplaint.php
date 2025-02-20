<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

// Validate if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('db_connection.php');

// Function to generate a unique ComplaintID (e.g., C######)
function generateComplaintID($conn) {
    do {
        $complaintID = 'C' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $stmt = $conn->prepare("SELECT ComplaintID FROM tblcomplaints WHERE ComplaintID = ?");
        $stmt->bind_param("s", $complaintID);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);
    $stmt->close();
    return $complaintID;
}

// Handle the save complaint form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaintID = generateComplaintID($conn);
    $clientID = $_POST['clientId'] ?? 'N/A';
    $type = $_POST['topic'] ?? 'N/A';
    $report = $_POST['report'] ?? 'N/A';
    $remark = $_POST['detail'] ?? 'No details provided';
    $status = "Pending"; // Default status
    $dateReported = date("Y-m-d H:i:s"); // Current date and time

    // Get Client Details
    $stmt = $conn->prepare("SELECT FullName, MobileNumber, Email, Address FROM tblclient WHERE ClientID = ?");
    $stmt->bind_param("s", $clientID);
    $stmt->execute();
    $stmt->bind_result($fullName, $mobileNumber, $clientEmail, $address);
    $stmt->fetch();
    $stmt->close();

    // Default email if not found
    if (empty($clientEmail)) {
        $clientEmail = "no-email@provided.com";
    }

    // Prepared statement for inserting complaint
    $stmt = $conn->prepare("INSERT INTO tblcomplaints (ComplaintID, ClientID, Type, Report, Complaint, Status, DateReported) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $complaintID, $clientID, $type, $report, $remark, $status, $dateReported);

    if ($stmt->execute()) {
        // Send an email notification to both BMB Cell Aurora and the client
        if (sendComplaintEmail($complaintID, $clientID, $fullName, $mobileNumber, $clientEmail, $address, $type, $report, $remark)) {
            $_SESSION['success'] = "Complaint submitted successfully and email notification sent.";
        } else {
            $_SESSION['success'] = "Complaint submitted, but email notification failed.";
        }
        header("Location: successMessage.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: filecomplaint.php");
        exit();
    }
    $stmt->close();
}

$conn->close();

// Function to send email notification using PHPMailer
function sendComplaintEmail($complaintID, $clientID, $fullName, $mobileNumber, $clientEmail, $address, $type, $report, $remark) {
    $mail = new PHPMailer(true);
    
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bmbcellaurora@gmail.com'; // Replace with your Gmail
        $mail->Password   = 'hypl rkmr llcs mhmn';   // Replace with your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email Sender
        $mail->setFrom('bmbcellaurora@gmail.com', 'Complaint System');

        // Email to BMB Cell Aurora (Admin)
        $mail->addAddress('bmbcellaurora@gmail.com', 'Complaint Receiver'); 
        $mail->isHTML(true);
        $mail->Subject = "New Complaint Filed (ID: $complaintID)";
        $mail->Body    = "
            <h2 style='color: #007bff;'>New Complaint Received</h2>
            <p>A new complaint has been submitted with the following details:</p>
            <table style='width: 100%; border-collapse: collapse;'>
                <tr><td><strong>Complaint ID:</strong></td><td>$complaintID</td></tr>
                <tr><td><strong>Client ID:</strong></td><td>$clientID</td></tr>
                <tr><td><strong>Full Name:</strong></td><td>$fullName</td></tr>
                <tr><td><strong>Mobile Number:</strong></td><td>$mobileNumber</td></tr>
                <tr><td><strong>Email:</strong></td><td>$clientEmail</td></tr>
                <tr><td><strong>Address:</strong></td><td>$address</td></tr>
                <tr><td><strong>Topic:</strong></td><td>$type</td></tr>
                <tr><td><strong>Report:</strong></td><td>$report</td></tr>
                <tr><td><strong>Details:</strong></td><td>$remark</td></tr>
            </table>
            <hr>
            <p style='color: gray; font-size: 12px;'><em>This email is automatically generated. Please do not reply.</em></p>
        ";
        $mail->send();

        // Email to Client (Acknowledgment)
        if (filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) { 
            $mail->clearAddresses();
            $mail->addAddress($clientEmail, "Client $clientID"); 
            $mail->Subject = "Acknowledgment of Your Complaint (ID: $complaintID)";
            $mail->Body = "
                <h2 style='color: #28a745;'>Complaint Acknowledgment</h2>
                <p>Dear <strong>$fullName</strong>,</p>
                <p>We have received your complaint and will review it as soon as possible. Below are the details of your complaint:</p>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr><td><strong>Complaint ID:</strong></td><td>$complaintID</td></tr>
                    <tr><td><strong>Client ID:</strong></td><td>$clientID</td></tr>
                    <tr><td><strong>Topic:</strong></td><td>$type</td></tr>
                    <tr><td><strong>Report:</strong></td><td>$report</td></tr>
                    <tr><td><strong>Details:</strong></td><td>$remark</td></tr>
                </table>
                <p>We appreciate your patience. You will be notified once your complaint is processed.</p>
                <hr>
                <p style='color: gray; font-size: 12px;'><em>This email is automatically generated. Please do not reply.</em></p>
            ";
            $mail->send();
        }

        return true;
    } catch (Exception $e) {
        return false; // Log error if needed
    }
}
?>
