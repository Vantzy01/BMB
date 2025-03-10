<?php
include('db_connection.php');
session_start();
$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = trim($_POST['username']);
    $inputPassword = trim($_POST['password']);

    // Validate form fields
    if (empty($inputUsername) || empty($inputPassword)) {
        $error_message = "Please fill in all fields.";
    } else {
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

            // Redirect to the dashboard with query parameters
            $clientID = $row['ClientID'];
            $planID = $row['PlanID'];
            $fullName = urlencode($row['FullName']);
            header("Location: dashboard.php?clientID=$clientID&planID=$planID&fullName=$fullName");
            exit();
        } else {
            // Invalid credentials
            $error_message = "Invalid username or password.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Vanguard">
    <meta name="description" content="BMB Cell System">
    <meta name="keywords" content="BMB Cell Aurora, BMB Cell, Aurora">
    <link rel="icon" href="Images/logo.ico" />
    <title>Login - BMB Cell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            background: url('Images/bg1.jpg') center no-repeat;
            background-size: cover;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        .container {
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
            z-index: 2;
            margin: 80px;
            animation: fadeIn 0.3s ease-in;
        }

        .container .title {
            font-size: 25px;
            font-weight: bolder;
            position: relative;
            color: #00aaff;
        }

        .container .title::before {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 30px;
            border-radius: 5px;
            background-color: #00aaff;
        }

        .content form .user-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px;
            margin: 20px 0 10px 0;
        }

        form .input-box span.details {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .user-details .input-box input {
            height: 45px;
            width: 100%;
            outline: none;
            font-size: 16px;
            border-radius: 5px;
            padding-left: 15px;
            border: 1px solid #ccc;
            border-bottom-width: 2px;
            transition: all 0.3s ease;
        }

        .user-details .input-box input:focus {
            border-color: #00aaff;
        }

        /* Design for package */
        .user-details .input-box select {
            height: 45px;
            width: 100%;
            outline: none;
            font-size: 16px;
            border-radius: 5px;
            padding-left: 15px;
            border: 1px solid #ccc;
            border-bottom-width: 2px;
            transition: all 0.3s ease;
        }

        .user-details .input-box select:focus,
        .user-details .input-box select:valid {
            border-color: #00aaff;
        }

        form .button {
            height: 45px;
            margin: 20px 0
        }

        form .button input {
            height: 100%;
            width: 100%;
            border-radius: 5px;
            border: none;
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #00aaff;
        }

        form .button input:hover {
            background: #2563eb;
        }

        .error-message {
            color: #ff4d4d;
            font-size: 0.9rem;
            margin-top: 5px;
            margin-bottom: 5px;
            text-align: left;
        }

        .back-link {
            display: inline-block;
            width: 100%;
            text-align: center;
            text-decoration: none;
            align-self: center;
            transition: all 0.2s;
        }

        .back-link:hover {
            text-decoration: underline;
            transform: scale(1.03);
        }

        @media(max-width: 584px) {
            .container {
                max-width: 100%;
            }

            form .user-details .input-box {
                margin-bottom: 15px;
                width: 100%;
            }

            .content form .user-details {
                max-height: 300px;
            }
        }

        @media(max-width: 459px) {
            .container {
                margin: auto;
                width: 95%;
                height: auto;
                padding: 25px;
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            100% {
                opacity: 100;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="title">Client Login</div>
        <div class="content">
            <form id="loginForm" action="" method="post">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Username</span>
                        <input type="text" id="username" name="username" placeholder="Enter your username">
                    </div>
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" id="password" name="password" placeholder="Enter your password">
                    </div>
                    <?php if (!empty($error_message)): ?>
                        <div class="error-message"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                </div>
                <div class="login-link">
                    <p>Not a member? <a href="registration.php">Register here</a></p>
                </div>
                <div class="button">
                    <input type="submit" value="Login">
                </div>
                <a href="index.php" class="back-link"><i class="fas fa-arrow-left" style=margin-right:5px;></i>Back to Homepage</a>
            </form>
        </div>
    </div>
</body>

</html>