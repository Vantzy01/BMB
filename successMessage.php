<?php
session_start();

if (!isset($_SESSION['success'])) {
    header("Location: complaint.php");
    exit();
}

$successMessage = $_SESSION['success'];
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <title>Success</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #81bdff, #027bff);
            color: #fff;
        }

        .modal {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 90%;
            max-width: 400px;
            color: #333;
            animation: fadeIn 0.5s ease;
        }

        .modal h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .modal button {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .modal button:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .modal button:active {
            transform: translateY(0);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 500px) {
            .modal {
                padding: 20px;
            }

            .modal h2 {
                font-size: 18px;
            }

            .modal button {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="modal">
        <h2><?php echo $successMessage; ?></h2>
        <button onclick="redirectToComplaint()">Okay</button>
    </div>
    <script>
        function redirectToComplaint() {
            window.location.href = "complaint.php";
        }
    </script>
</body>

</html>