<?php
session_start();

// Database connection
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

// Function to decrypt password
function caesar_decrypt($ciphertext, $shift) {
    $decrypted_text = '';
    for ($i = 0; $i < strlen($ciphertext); $i++) {
        $char = $ciphertext[$i];
        if (ctype_alpha($char)) {
            $ascii_offset = ctype_upper($char) ? ord('A') : ord('a');
            $decrypted_text .= chr((ord($char) - $ascii_offset - $shift + 26) % 26 + $ascii_offset);
        } else {
            $decrypted_text .= $char;
        }
    }
    return $decrypted_text;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if user exists
    $sql = "SELECT * FROM tblcollector WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_encrypted_password = $row['Password'];
        $decrypted_password = caesar_decrypt($stored_encrypted_password, 3);

        // Verify decrypted password
        if ($decrypted_password === $password) {
            // Store necessary session variables
            $_SESSION['CollectorID'] = $row['CollectorID'];
            $_SESSION['FullName'] = $row['FullName'];  // Store FullName in session
            header("Location: collector_dash.php");
            exit;
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "Username not found.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background: url('Images/hero-background.jpg') no-repeat center center/cover;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #121212;
            position: relative;
            overflow: hidden;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .login-container {
            background-color: #1f1f1f;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 100%;
            max-width: 400px;
            z-index: 2;
            margin: auto;
        }

        .login-container h2 {
            margin-bottom: 30px;
            font-size: 26px;
            color: white;
        }

        .input-group {
            display: flex;
            align-items: center;
            background-color: #333;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .input-group i {
            color: #aaa;
            margin-right: 10px;
            font-size: 16px;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            border: none;
            outline: none;
            background-color: transparent;
            color: white;
            font-size: 16px;
        }

        .login-container input::placeholder {
            color: #bbb;
        }

        .login-container button {
            background-color: #3498db;
            color: #fff;
            padding: 12px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #2980b9;
        }

        .login-container p {
            color: red;
            font-size: 14px;
            margin-top: 20px;
        }

        .login-container .back-link {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            color: #3498db;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .login-container .back-link:hover {
            color: #2980b9;
        }

        @media (max-width: 600px) {
            .login-container {
                padding: 20px;
            }

            .login-container h2 {
                font-size: 22px;
            }

            .login-container button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Background overlay -->
    <div class="overlay"></div>

    <div class="login-container">
        <h2>Collector Login</h2>
        <form method="POST" action="collector_login.php">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Homepage</a>
    </div>
</body>
</html>
