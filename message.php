<?php
if (isset($_GET['message'])) {
    $message = $_GET['message'];
} else {
    $message = "Something went wrong. Please try again.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .message-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
        }

        .message-box h2 {
            color: #ff4d4d;
        }

        .message-box p {
            font-size: 16px;
            color: #333;
        }

        .close-btn {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .close-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="message-box">
        <h2>Notification</h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <button class="close-btn" onclick="window.location.href='registration.php';">Okay</button>
    </div>
</body>

</html>