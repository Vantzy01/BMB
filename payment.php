<?php
// Start the session and ensure the user is authenticated
session_start();

// Redirect to login if the session is not set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the necessary session variables
$clientName = $_SESSION['fullname'];

// Get the invoice number and client ID from the URL
$invoiceNo = isset($_GET['invoiceNo']) ? $_GET['invoiceNo'] : null;
$clientID = isset($_GET['clientID']) ? $_GET['clientID'] : null;

// Validate the input
if (!$invoiceNo || !$clientID) {
    echo "Invalid payment request. Please try again.";
    exit();
}

include('db_connection.php');

// Fetch the latest bill details based on the invoice number and client ID
$billSql = "SELECT * FROM tblbilling WHERE InvoiceNo = ? AND ClientID = ?";
$billStmt = $conn->prepare($billSql);
$billStmt->bind_param("si", $invoiceNo, $clientID);
$billStmt->execute();
$billResult = $billStmt->get_result();

// Check if the billing data exists
if ($billResult->num_rows > 0) {
    $latestBill = $billResult->fetch_assoc();
} else {
    echo "Billing details not found.";
    exit();
}

// Fetch the plan details for the specific PlanID related to the bill
$planSql = "SELECT * FROM tblplan WHERE PlanID = ?";
$planStmt = $conn->prepare($planSql);
$planStmt->bind_param("i", $latestBill['PlanID']);
$planStmt->execute();
$planResult = $planStmt->get_result();
$plan = $planResult->fetch_assoc();

// Close database connections
$planStmt->close();
$billStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment - BMB Internet Service</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom styles for the step-by-step form */
        .step {
            display: none;
            transition: all 0.3s ease-in-out;
        }

        .step.active {
            display: block;
        }

        .step-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .step-header .step-indicator {
            width: 23%;
            padding: 10px;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 5px;
            position: relative;
        }

        .step-header .step-indicator.active {
            background-color: #007bff;
            color: #fff;
        }

        .step-header .step-indicator:after {
            content: '';
            position: absolute;
            top: 50%;
            right: -20px;
            width: 40px;
            height: 2px;
            background-color: #dee2e6;
            transform: translateY(-50%);
        }

        .step-header .step-indicator:last-child:after {
            display: none;
        }

        .btn-primary, .btn-secondary, .btn-success {
            width: 100%;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3 class="text-center">Payment Process</h3>

    <!-- Step Progress Indicator -->
    <div class="step-header">
        <div class="step-indicator active" id="indicator-1">Payment Info</div>
        <div class="step-indicator" id="indicator-2">Billing Details</div>
        <div class="step-indicator" id="indicator-3">Summary</div>
        <div class="step-indicator" id="indicator-4">Payment</div>
    </div>

    <form id="paymentForm" action="process_payment.php" method="post" enctype="multipart/form-data">
        <!-- Step 1: Payment Information -->
        <div class="step active" id="step-1">
            <h4>1. Payment Information</h4>
            <p>Monthly Bill: <strong>₱<?php echo number_format($latestBill['OutstandingBalance'], 2); ?></strong></p>
            <input type="hidden" name="invoiceNo" value="<?php echo $latestBill['InvoiceNo']; ?>">
            <input type="hidden" name="clientID" value="<?php echo $latestBill['ClientID']; ?>">
            <input type="hidden" name="period" value="<?php echo $latestBill['Period']; ?>">
            <input type="hidden" name="lastBill" value="<?php echo $latestBill['OutstandingBalance']; ?>">

            <label class="mt-3">Select Payment Method:</label>
            <div class="form-group">
                <div class="custom-control custom-radio">
                    <input type="radio" id="gcash" name="paymentMethod" value="Gcash" class="custom-control-input" required>
                    <label class="custom-control-label" for="gcash">GCash</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="homeCollector" name="paymentMethod" value="Home Collector" class="custom-control-input" required>
                    <label class="custom-control-label" for="homeCollector">Home Collector</label>
                </div>
            </div>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>

        <!-- Step 2: Billing Details -->
        <div class="step" id="step-2">
            <h4>2. Billing Details</h4>
            <p>Due Date: <strong><?php echo date('d F Y', strtotime($latestBill['DueDate'])); ?></strong></p>
            <p>Status: <strong><?php echo $latestBill['Status']; ?></strong></p>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>

        <!-- Step 3: Summary -->
        <div class="step" id="step-3">
            <h4>3. Summary</h4>
            <p>Review your payment information before proceeding.</p>
            <ul class="list-group">
                <li class="list-group-item">Amount to Pay: <strong>₱<?php echo number_format($latestBill['OutstandingBalance'], 2); ?></strong></li>
                <li class="list-group-item">Payment Method: <strong id="selectedMethod"></strong></li>
            </ul>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>

        <!-- Step 4: Payment -->
        <div class="step" id="step-4">
            <h4>4. Payment</h4>
            <div class="form-group">
                <label>Enter Amount:</label>
                <input type="number" name="amount" class="form-control" required>
            </div>
            <div class="form-group gcash-attachment" style="display: none;">
                <label>Upload GCash Payment Screenshot:</label>
                <input type="file" name="fileToUpload" accept=".jpg, .jpeg, .png" class="form-control">
            </div>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="submit" class="btn btn-success">Submit Payment</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let currentStep = 1;

        // Handle Next Step
        $('.next-step').click(function() {
            if (currentStep < 4) {
                $('#step-' + currentStep).removeClass('active');
                $('#indicator-' + currentStep).removeClass('active');
                currentStep++;
                $('#step-' + currentStep).addClass('active');
                $('#indicator-' + currentStep).addClass('active');
            }
        });

        // Handle Previous Step
        $('.prev-step').click(function() {
            if (currentStep > 1) {
                $('#step-' + currentStep).removeClass('active');
                $('#indicator-' + currentStep).removeClass('active');
                currentStep--;
                $('#step-' + currentStep).addClass('active');
                $('#indicator-' + currentStep).addClass('active');
            }
        });

        // Handle Payment Method Display
        $('input[name="paymentMethod"]').change(function() {
            $('#selectedMethod').text($(this).val());
            if ($(this).val() === 'Gcash') {
                $('.gcash-attachment').show();
            } else {
                $('.gcash-attachment').hide();
            }
        });
    });
</script>

</body>
</html>
