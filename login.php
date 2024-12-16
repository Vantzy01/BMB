<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <title>Login - BMB Internet Service</title>
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
            position: relative;
            z-index: 2;
            max-width: 400px;
            padding: 20px;
            background-color: #1f1f1f;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #2a2a2a;
            color: #fff;
        }

        .cta {
            padding: 15px 30px;
            background-color: #00aaff;
            color: #fff;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .cta:hover {
            background-color: #0088cc;
        }

        .login-container p {
            margin-top: 20px;
        }

        .login-container a {
            color: #00aaff;
            text-decoration: none;
        }

        .login-container a:hover {
            color: #0088cc;
        }
        
    </style>
</head>
<body>
    <!-- Background overlay -->
    <div class="overlay"></div>

    <!-- Login container -->
    <div class="login-container">
        <h2>Login to BMB Internet Service</h2>
        <form action="authenticate.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="cta">Login</button>
        </form>
        <p>Don't have an account? <a href="registration.php">Register here</a></p>
    </div>
</body>
</html>
