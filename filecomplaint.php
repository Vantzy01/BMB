<?php
session_start();
// Validate if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// Use the session variable for clientID, if available
if (isset($_GET['clientID'])) {
    $_SESSION['clientID'] = $_GET['clientID'];
}

$clientID = isset($_SESSION['clientID']) ? $_SESSION['clientID'] : null;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbinternet";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch topics for dropdown
$sql = "SELECT Type FROM tbltype";
$result = $conn->query($sql);

$types = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $types[] = $row['Type'];
    }
}

// Handle AJAX request for client details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'fetchClientDetails') {
    $clientID = $conn->real_escape_string($_POST['clientID']);
    $sql = "SELECT FullName as fullName, MobileNumber AS phone, Address as address FROM tblclient WHERE ClientID = '$clientID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['fullName' => 'N/A', 'phone' => 'N/A', 'address' => 'N/A']);
    }
    $conn->close();
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>File Complaint</title>
    <style>
        /* Reset and Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 90%;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
        }

        h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
            color: #444444;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #007bff;
            background-color: #ffffff;
            outline: none;
        }

        textarea {
            resize: none;
        }

        .hidden {
            display: none;
        }

        .note {
            background-color: #e9f5ff;
            border-left: 4px solid #007bff;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 14px;
            color: #333333;
        }

        button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: #ffffff;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }
        .hidden {
            display: none;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>File a Complaint</h2>
        <form  id="saveComplaintForm" action="savecomplaint.php" method="POST">
            <!-- Client ID -->
            <div class="form-group">
                <label for="clientId">Client ID</label>
                <input type="text" id="clientId" name="clientId" value="<?php echo htmlspecialchars($clientID ?? 'N/A'); ?>" readonly>
            </div>

            <!-- Topic Selection -->
            <div class="form-group">
                <label for="topic">Topic</label>
                <select id="topic" name="topic" required>
                    <option value="" disabled selected>Select</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Report Selection (Hidden Initially) -->
            <div id="reportSection" class="form-group hidden">
                <label for="report">Select Report</label>
                <select id="report" name="report" required>
                    <option value="" disabled selected>Select</option>
                </select>
            </div>

            <!-- Instruction Section (Hidden Initially) -->
            <div id="instructionSection" class="form-group hidden">
                <hr>
                <div class="note">
                    "We sincerely apologize for any inconvenience you may be experiencing. Before proceeding with your complaint, we kindly recommend trying the following steps to address the issue. Your satisfaction is our top priority, and we are here to assist you every step of the way."
                </div>
                <label for="instruction">Instruction</label>
                <textarea id="instruction" name="instruction" readonly rows="20"></textarea>
                <div class="note">
                    "If the above method still fails, follow the next steps for further handling. Click the "Next" button below."
                </div>
                <br>
                <button type="next">Next</button>
            </div>
            <!-- Detail Section (Hidden Initially) -->
            <div id="detailSection" class="form-group hidden">
                <hr>
                <label for="fullName">FullName</label>
                <input type="text" id="fullName" name="fullName" readonly>
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" readonly>
                <label for="address">Address</label>
                <input type="text" id="address" name="address" readonly>

                <label for="detail">Remark</label>
                <textarea type="text" id="detail" name="detail" placeholder="Detail your Problem" rows="5"spellcheck="false" required></textarea>         
                <button type="save">Save</button>
            </div>
        </form>
    </div>

    <script>
    $(document).ready(function () {
        // Show Report Section When Topic is Selected
        $('#topic').change(function () {
            const selectedTopic = $(this).val();

            if (selectedTopic) {
                $('#reportSection').removeClass('hidden');

                // Clear existing options and add loading placeholder
                $('#report').empty().append('<option value="" disabled selected>Loading...</option>');

                // Fetch reports for the selected topic
                $.ajax({
                    url: 'fetch_reports.php',
                    type: 'POST',
                    data: { type: selectedTopic },
                    dataType: 'json',
                    success: function (data) {
                        $('#report').empty().append('<option value="" disabled selected>Select</option>');
                        data.forEach(function (report) {
                            $('#report').append(`<option value="${report}">${report}</option>`);
                        });
                    },
                    error: function () {
                        alert('Failed to fetch reports. Please try again.');
                    }
                });
            } else {
                $('#reportSection, #instructionSection').addClass('hidden');
            }
        });

        // Show Instruction Section When Report is Selected
        $('#report').change(function () {
            const selectedReport = $(this).val();

            if (selectedReport) {
                $('#instructionSection').removeClass('hidden');

                // Fetch instruction for the selected report
                $.ajax({
                    url: 'fetch_instruction.php',
                    type: 'POST',
                    data: { report: selectedReport },
                    dataType: 'json',
                    success: function (data) {
                        $('#instruction').val(data.instruction || 'No instruction available.');
                    },
                    error: function () {
                        alert('Failed to fetch the instruction. Please try again.');
                    }
                });
            } else {
                $('#instructionSection').addClass('hidden');
            }
        });

        // Handle Next Button Click
        $('#instructionSection button[type="next"]').click(function (e) {
            e.preventDefault(); // Prevent form submission

            // Hide instruction section and show detail section
            $('#instructionSection').addClass('hidden');
            $('#detailSection').removeClass('hidden');

            // Fetch dynamic client data
            const clientID = $('#clientId').val();
            $.ajax({
                url: 'filecomplaint.php', // Same file
                type: 'POST',
                data: { action: 'fetchClientDetails', clientID: clientID },
                dataType: 'json',
                success: function (data) {
                    $('#fullName').val(data.fullName || 'N/A');
                    $('#phone').val(data.phone || 'N/A');
                    $('#address').val(data.address || 'N/A');
                },
                error: function () {
                    alert('Failed to fetch client details. Please try again.');
                }
            });
        });
    });
</script>

</body>
</html>

