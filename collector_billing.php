<?php
session_start();
include('db_connection.php');


// Fetch unique periods for the dropdown
$periodsQuery = "SELECT DISTINCT Period FROM tblbilling ORDER BY Period DESC";
$periodsResult = $conn->query($periodsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <title>Billing Records</title>
    <style>
        /* Modern styling for the page */
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; color: #333; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .filter-bar { display: flex; gap: 15px; margin-bottom: 20px; }
        .table-container { overflow-x: auto;}
        .filter-bar select, .filter-bar input[type="text"] { padding: 10px; font-size: 16px; width: 100%; max-width: 300px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        th, td { padding: 8px 10px; text-align: left; }
        th { background-color: #3498db; color: #fff; font-weight: bold; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        tr:hover { background-color: #f1f1f1; }
        .action-viewbtn { padding: 6px 12px; background: #3498db; color: #fff; border: none; cursor: pointer; border-radius: 4px; }
        .action-viewbtn:hover { background: #2980b9; }
        /* Styling for action buttons */
        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            color: #fff;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }

        /* Colors for different button statuses */
        .action-btn.pay-btn {
            background-color: #28a745; /* Green */
        }

        .action-btn.pay-btn:hover {
            background-color: #218838;
        }

        .action-btn.paid-btn {
            background-color: #6c757d; /* Gray */
            cursor: not-allowed;
        }

        .action-btn.waiting-btn {
            background-color: #ffc107; /* Yellow */
            cursor: not-allowed;
        }

        /* Icons styling */
        .action-btn i {
            font-size: 16px;
        }

        /* Modal Styling */
        #billingModal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Dark overlay */
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', sans-serif;
        }

        .close-modal {
            color: #aaa;
            float: right;
            font-size: 24px;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #333;
        }

        .modal-content h3 {
            font-size: 1.5em;
            color: #3498db;
        }

        .modal-details p {
            font-size: 1em;
            margin: 8px 0;
        }

        /* Modal Background Overlay */
        #paymentModal {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            overflow-y: auto; /* Ensure the modal container itself can scroll if needed */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fff;
            width: 100%;
            max-width: 500px;
            max-height: 100vh; /* Restrict height to 80% of viewport */
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            font-family: 'Poppins', sans-serif;
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Close Button */
        .close-modal {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5em;
            color: #aaa;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-modal:hover {
            color: #333;
        }

        /* Modal Heading */
        .modal-content h3 {
            color: #3498db;
            font-size: 1.8em;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Form Styling */
        #paymentForm label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
            font-size: 1em;
        }

        #paymentForm input[type="text"],
        #paymentForm input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        #paymentForm input[type="text"]:focus,
        #paymentForm input[type="number"]:focus {
            border-color: #3498db;
            outline: none;
        }

        /* Buttons */
        #paymentForm .btn-submit {
            background-color: #3498db;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        #paymentForm .btn-submit:hover {
            background-color: #2980b9;
        }

        #paymentForm .btn-cancel {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        #paymentForm .btn-cancel:hover {
            background-color: #c0392b;
        }

        .paid-btn {
            background-color: #ccc; /* Grey for "Already Paid" button */
            color: #666;
            cursor: not-allowed;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .top-nav {
            background-color: #2C3E50;
            color: white;
            display: flex;
            justify-content: space-between;
            padding: 1em;
            align-items: center;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 1em;
        }

        .bottom-nav {
            display: flex;
            justify-content: space-around;
            background-color: #2C3E50;
            padding: 1em;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .bottom-nav a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
        }

        .bottom-nav .active {
            border-top: 2px solid #3498DB;
            padding-top: 0.5em;
        }

    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <header>
        <nav class="top-nav">
            <h1>Collector Dashboard</h1>
            <div class="profile">
                <span><?php echo $_SESSION['FullName']; ?></span>
                <a href="coll_logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h2>Billing Records</h2>
        <!-- Filter Bar -->
        <div class="filter-bar">
            <select id="periodSelect" name="period">
                <option value="">Select Period</option>
                <?php while ($periodRow = $periodsResult->fetch_assoc()): ?>
                    <option value="<?php echo $periodRow['Period']; ?>"><?php echo $periodRow['Period']; ?></option>
                <?php endwhile; ?>
            </select>
            <select id="statusSelect" name="status">
                <option value="">All Statuses</option>
                <option value="Paid">Paid</option>
                <option value="Unpaid">Unpaid</option>
            </select>
            <input type="text" id="searchInput" placeholder="Search by Client Name" />
        </div>

        <!-- Billing Table -->
        <table>
            <thead>
                <tr>
                    <th>Invoice No</th>
                    <th>Client Name</th>
                    <th>Due Date</th>
                    <th>Due Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="billingTableBody">
                <!-- Table rows will be loaded here by AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Modal Structure -->
    <div id="billingModal" style="display: none;">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h3>Billing Details</h3>
            <div class="modal-details">
                <p><strong>Invoice No:</strong> <span id="modalInvoiceNo"></span></p>
                <p><strong>Client ID:</strong> <span id="modalClientID"></span></p>
                <p><strong>Due Date:</strong> <span id="modalDueDate"></span></p>
                <p><strong>Period:</strong> <span id="modalPeriod"></span></p>
                <p><strong>Plan:</strong> <span id="modalPlan"></span></p>
                <p><strong>Due Amount:</strong> <span id="modalDueAmount"></span></p>
                <p><strong>Discount:</strong> <span id="modalDiscount"></span></p>
                <p><strong>Amount Paid:</strong> <span id="modalAmountPaid"></span></p>
                <p><strong>Outstanding Balance:</strong> <span id="modalOutstandingBalance"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
            </div>
        </div>
    </div>

    <!-- Payment Modal Structure -->
    <div id="paymentModal" style="display: none;">
        <div class="modal-content">
            <span class="close-modal" onclick="closePaymentModal()">&times;</span>
            <h3>Make Payment</h3>
            <form id="paymentForm">
                <label>Reference No:</label>
                <input type="text" id="paymentReferenceNo" readonly>

                <label>Invoice No:</label>
                <input type="text" id="paymentInvoiceNo" readonly>

                <label>Client ID:</label>
                <input type="text" id="paymentClientID" readonly>

                <label>Payment Method:</label>
                <input type="text" value="Home Collector" readonly>

                <label>Period:</label>
                <input type="text" id="paymentPeriod" readonly>

                <label>Amount:</label>
                <input type="number" id="paymentAmount" min="1" required>

                <label>Last Bill (Outstanding Balance):</label>
                <input type="text" id="paymentLastBill" readonly>

                <button class="btn-submit"type="button" onclick="savePayment()">Save Payment</button>
                <button class="btn-cancel"type="button" onclick="closePaymentModal()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Bottom Navigation Bar -->
    <footer>
        <nav class="bottom-nav">
            <a href="collector_dash.php">Dashboard</a>
            <a href="collector_billing.php" class="active">Billing</a>
            <a href="collector_collection.php">Collection</a>
            <a href="collector_map.php">Map</a>
            <a href="collector_announcement.php">Announcements</a>
        </nav>
    </footer>
    
    <script>
        function fetchBillingData() {
            const period = document.getElementById("periodSelect").value;
            const searchQuery = document.getElementById("searchInput").value;
            const statusFilter = document.getElementById("statusSelect").value;

            const xhr = new XMLHttpRequest();
            xhr.open("GET", `fetch_billing.php?period=${period}&search=${searchQuery}&status=${statusFilter}`, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById("billingTableBody").innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        document.addEventListener("DOMContentLoaded", function() {
            fetchBillingData(); // Load data by default on page load
            document.getElementById("searchInput").addEventListener("input", fetchBillingData);
            document.getElementById("periodSelect").addEventListener("change", fetchBillingData);
            document.getElementById("statusSelect").addEventListener("change", fetchBillingData);
        });

        function viewDetails(invoiceNo) {
            // AJAX to fetch billing details
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `fetch_billing_details.php?invoiceNo=${invoiceNo}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    // Populate modal fields
                    document.getElementById("modalInvoiceNo").innerText = data.InvoiceNo;
                    document.getElementById("modalClientID").innerText = data.ClientID;
                    document.getElementById("modalDueDate").innerText = data.DueDate;
                    document.getElementById("modalPeriod").innerText = data.Period;
                    document.getElementById("modalPlan").innerText = data.Plan;
                    document.getElementById("modalDueAmount").innerText = `$${data.DueAmount}`;
                    document.getElementById("modalDiscount").innerText = `$${data.Discount}`;
                    document.getElementById("modalAmountPaid").innerText = `$${data.AmountPaid}`;
                    document.getElementById("modalOutstandingBalance").innerText = `$${data.OutstandingBalance}`;
                    document.getElementById("modalStatus").innerText = data.Status;

                    // Show modal
                    document.getElementById("billingModal").style.display = "block";
                }
            };
            xhr.send();
        }

        function closeModal() {
            document.getElementById("billingModal").style.display = "none";
        }


        function openPaymentModal(invoiceNo) {
            // Generate unique ReferenceNo
            const referenceNo = "REF" + Math.floor(100000 + Math.random() * 900000);

            // AJAX to get additional billing data
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `fetch_payment_data.php?invoiceNo=${invoiceNo}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);

                    // Populate form fields
                    document.getElementById("paymentReferenceNo").value = referenceNo;
                    document.getElementById("paymentInvoiceNo").value = data.InvoiceNo;
                    document.getElementById("paymentClientID").value = data.ClientID;
                    document.getElementById("paymentPeriod").value = data.Period;
                    document.getElementById("paymentLastBill").value = data.OutstandingBalance;

                    document.getElementById("paymentModal").style.display = "block";
                }
            };
            xhr.send();
        }

        function savePayment() {
            const paymentAmount = parseFloat(document.getElementById('paymentAmount').value);
            const lastBill = parseFloat(document.getElementById('paymentLastBill').value);

            // Validate the payment amount
            if (!paymentAmount || paymentAmount <= 0) {
                alert("Please enter a valid amount greater than 0.");
                return;
            }

            if (paymentAmount > lastBill) {
                alert("The entered amount cannot exceed the outstanding balance.");
                return;
            }

            // Prepare payment data if validation passes
            const paymentData = {
                ReferenceNo: document.getElementById("paymentReferenceNo").value,
                InvoiceNo: document.getElementById("paymentInvoiceNo").value,
                ClientID: document.getElementById("paymentClientID").value,
                PaymentMethod: "Home Collector",
                Period: document.getElementById("paymentPeriod").value,
                Amount: paymentAmount,
                PaymentStatus: "Waiting",
                PaymentDate: new Date().toISOString().slice(0, 19).replace('T', ' '),
                LastBill: lastBill
            };

            // AJAX to save payment
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "save_payment.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("Payment saved successfully!");
                    closePaymentModal();
                    fetchBillingData(); // Refresh billing data
                } else {
                    alert("Error saving payment. Please try again.");
                }
            };

            xhr.send(JSON.stringify(paymentData));
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

    </script>
</body>
</html>
