<?php
// Start the session to manage user data
session_start();

// Redirect to login if the session is not set (optional, ensure user access control)
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Optional: You can add any session clean-up or additional processing needed here
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5;url=dashboard.php">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .center-screen {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>

<body>

    <div class="container center-screen">
        <div class="alert alert-success text-center" role="alert">
            <h4 class="alert-heading">Payment Successful!</h4>
            <p>Your payment has been processed successfully. You will be redirected to the dashboard shortly.</p>
            <hr>
            <p class="mb-0">If you are not redirected automatically, <a href="dashboard.php" class="alert-link">click here</a>.</p>
        </div>
    </div>

    <script>
        // Optional: Additional JavaScript can be added if further processing is needed
        setTimeout(function() {
            window.location.href = 'dashboard.php'; // Redirect to dashboard.php after the alert is shown
        }, 5000); // Adjust time if needed (5 seconds)
    </script>

</body>

</html>