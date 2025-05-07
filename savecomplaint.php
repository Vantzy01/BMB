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
function generateComplaintID($conn)
{
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
    $dateReported = date("Y-m-d"); // Current date and time

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
function sendComplaintEmail($complaintID, $clientID, $fullName, $mobileNumber, $clientEmail, $address, $type, $report, $remark)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bmbcellaurora@gmail.com'; // Replace with your Gmail
        $mail->Password   = 'fiyc zxef igpt sdbe';   // Replace with your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email Sender
        $mail->setFrom('bmbcellaurora@gmail.com', 'Complaint System');

        // Email to BMB Cell Aurora (Admin)
        $mail->addAddress('bmbcellaurora@gmail.com', 'Complaint Receiver');
        $mail->isHTML(true);
        $mail->Subject = "New Complaint Filed (ID: $complaintID)";
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; font-size: 14px; color: #333;'>
                <h2 style='color: #0056b3; border-bottom: 2px solid #ccc;'>New Complaint Notification</h2>
                <p style='margin-bottom: 10px;'>A new complaint has been submitted with the following details:</p>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Complaint ID</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$complaintID</td></tr>
                    <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Client ID</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$clientID</td></tr>
                    <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Full Name</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$fullName</td></tr>
                    <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Mobile Number</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$mobileNumber</td></tr>
                    <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Email</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$clientEmail</td></tr>
                    <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Address</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$address</td></tr>
                    <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Topic</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$type</td></tr>
                    <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Report</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$report</td></tr>
                    <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Details</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$remark</td></tr>
                </table>
                <p style='font-size: 12px; color: #999; margin-top: 20px;'>
                    <em>This is an automated message from the Complaint Management System. Please do not reply.</em>
                </p>
            </div>
        ";
        $mail->send();

        // Email to Client (Acknowledgment)
        if (filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
            $mail->clearAddresses();
            $mail->addAddress($clientEmail, "Client $clientID");
            $mail->Subject = "Complaint Acknowledgment (ID: $complaintID)";
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; font-size: 14px; color: #333;'>
                    <h2 style='color: #28a745; border-bottom: 2px solid #ccc;'>Complaint Received</h2>
                    <p>Dear <strong>$fullName</strong>,</p>
                    <p>Thank you for contacting <strong>BMB Cell Aurora</strong>. Your complaint has been successfully received and is now under review. Below are the details of your submission:</p>
                    <table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
                        <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Complaint ID</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$complaintID</td></tr>
                        <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Client ID</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$clientID</td></tr>
                        <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Topic</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$type</td></tr>
                        <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Report</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$report</td></tr>
                        <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Details</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>$remark</td></tr>
                    </table>
                    <p style='margin-top: 15px;'>We appreciate your patience and will notify you as soon as your complaint has been processed. Should you have further questions, feel free to contact us.</p>
                    <p style='font-size: 12px; color: #999; margin-top: 20px;'>
                        <em>This is an automated message from BMB Cell Aurora. Please do not reply to this email.</em>
                    </p>
                </div>
            ";
            $mail->send();
        }

        return true;
    } catch (Exception $e) {
        return false; // Log error if needed
    }
}
