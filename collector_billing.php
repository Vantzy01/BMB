<?php
session_start();
include('db_connection.php');
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
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; color: #333; margin: 0; padding: 0; }
        
        /* Top Navigation Bar */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        .top-nav {
            background-color: #2C3E50;
            color: white;
            display: flex;
            justify-content: space-between;
            padding: 1em 2em;
            align-items: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .top-nav h1 {
            font-size: 1.5em;
        }

        .profile a {
            color: #ecf0f1;
            text-decoration: none;
            padding: 0.5em 1em;
            background-color: #e74c3c;
            border-radius: 5px;
            font-size: 0.9em;
        }
        .container { max-width: 1800px; margin: auto; padding: 20px; margin-top: 120px; margin-bottom: 120px;}
        .filter-bar { display: flex; gap: 15px; margin-bottom: 20px; }
        .table-container { overflow-x: auto;}
        .filter-bar select, .filter-bar input[type="text"] { padding: 10px; font-size: 16px; width: 100%; max-width: 300px; }
        table {width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        th, td { padding: 8px 10px; text-align: left; }
        th { background-color: #3498db; color: #fff; font-weight: bold; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        tr:hover { background-color: #f1f1f1; }
        table th:nth-child(6) {text-align: center;}
        table td:nth-child(6) {text-align: center;}
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
            background-color: #28a745;
        }

        .action-btn.pay-btn:hover {
            background-color: #218838;
        }

        .action-btn.paid-btn {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .action-btn.waiting-btn {
            background-color: #ffc107;
            cursor: not-allowed;
        }


        /* Modal Styling */
        #billingModal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            overflow: auto;
        }

        .modal-content {
            background: linear-gradient(to bottom right, #ffffff, #f0f3f7);
            margin: 10% auto;
            padding: 20px 30px;
            width: 90%;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
        }

        .close-modal {
            color: #333;
            font-size: 1.5rem;
            font-weight: bold;
            position: absolute;
            top: 15px;
            right: 20px;
            cursor: pointer;
            transition: color 0.2s, transform 0.2s;
        }

        .close-modal:hover {
            color: #e74c3c;
            transform: scale(1.2);
        }

        .modal-content h3 {
            font-size: 1.8em;
            color: #3498db;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }

        .modal-details p {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 1rem;
            font-weight: 500;
            color: #555;
        }

        .modal-details strong {
            font-weight: 600;
            color: #2c3e50;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        /* Payment Modal Background Overlay */
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
            overflow-y: auto;
        }

        /* Modal Content */
        .modal-content {
            background: linear-gradient(to bottom right, #ffffff, #f9f9f9);
            width: 90%;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            font-family: 'Poppins', sans-serif;
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Close Button */
        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.8em;
            color: #aaa;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-modal:hover {
            color: #e74c3c;
        }

        /* Modal Heading */
        .modal-content h3 {
            font-size: 1.8em;
            color: #3498db;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        /* Form Styling */
        #paymentForm label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
            font-size: 1em;
        }

        #paymentForm input[type="text"],
        #paymentForm input[type="number"],
        #paymentForm select{
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease-in-out;
        }

        #paymentForm input[type="text"]:focus,
        #paymentForm input[type="number"]:focus {
            border-color: #3498db;
            outline: none;
        }

        /* Buttons */
        #paymentForm .btn-submit {
            background-color: #3498db;
            color: white;
            padding: 15px;
            font-size: 1em;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease-in-out;
        }

        #paymentForm .btn-submit:hover {
            background-color: #2980b9;
        }

        #paymentForm .btn-cancel {
            background-color: #e74c3c;
            color: white;
            padding: 15px;
            font-size: 1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s ease-in-out;
        }

        #paymentForm .btn-cancel:hover {
            background-color: #c0392b;
        }

        .paid-btn {
            background-color: #bbb;
            color: #666;
            cursor: not-allowed;
        }

        /* Bottom Navigation Bar */
        .bottom-nav {
            display: flex;
            justify-content: space-around;
            background-color: #2C3E50;
            padding: 0.5em 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .bottom-nav a {
            color: white;
            text-decoration: none;
            font-size: 1em;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.3em;
        }

        .bottom-nav a i {
            font-size: 1.2em;
        }

        .bottom-nav .active {
            border-top: 2px solid #3498DB;
            padding-top: 0.5em;
        }

        .bottom-nav a span {
            font-size: 0.75em;
        }
        
        /* Responsive Design */
        @media (max-width: 560px) {
            .top-nav h1 {
                font-size: 1em;
            }

            .filter-bar { display: flex; gap: 5px;}
            .filter-bar select, .filter-bar input[type="text"] { padding: 10px; font-size: 10px; width: 100%; max-width: 300px; }
            th:first-child,
            td:first-child{
                display:none;
            }

            .bottom-nav a span {
                display: none;
            }

            .bottom-nav a i {
                font-size: 1.8em;
            }

            table { width: 100%; background: #fff; border-radius: 2px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
            th{font-size: 7px;}
            td{font-size: 6px;}
            th, td { padding: 6px 4px; text-align: left;}
            tbody td {
                border: none;
                padding: 6px 2px;
            }

            /* Style buttons for mobile */
            .action-btn,
            .action-viewbtn,
            .pay-btn{
                margin-bottom: 1px;
                font-size: 6px;
                padding: 2px 5px;
            }

            .action-btn.pay-btn {
                margin-bottom: 1px;
                font-size: 6px;
                padding: 2px 5px;
            }

            .action-btn.pay-btn:hover {
                margin-bottom: 1px;
                font-size: 6px;
                padding: 2px 5px;
            }

            .action-btn.paid-btn {
                margin-bottom: 1px;
                font-size: 6px;
                padding: 2px 5px;
            }

            .action-btn.waiting-btn {
                margin-bottom: 1px;
                font-size: 6px;
                padding: 2px 5px;
            }

            /* Modal Styling*/
            #billingModal {
                display: none;
                position: fixed;
                z-index: 10;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
            }

            .modal-content {
                background-color: #fff;
                margin: 150px auto;
                padding: 25px;
                width: 80%;
                max-width: 300px;
                border-radius: 8px;
                font-size: 12px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .close-modal {
                color: #aaa;
                float: right;
                font-size: 24px;
                cursor: pointer;
            }

            .close-modal:hover {
                color: #3498db;
            }

            .modal-content h3 {
                font-size: 1.5em;
                color: #3498db;
                margin-bottom: 30px;
            }

            .modal-details p {
                display: flex;
                padding: 0 2px;
                justify-content: space-between;
            }
            .modal-details p span {
                text-align: right;
                margin-left: auto;
            }

            /* Payment Modal */
            .modal-content {
                width: 95%;
                padding: 20px;
            }

            .modal-content h3 {
                font-size: 1.5em;
            }

            #paymentForm label {
                font-size: 0.9em;
            }

            #paymentForm input[type="text"],
            #paymentForm input[type="number"] {
                font-size: 0.9em;
            }

            #paymentForm .btn-submit,
            #paymentForm .btn-cancel {
                font-size: 0.9em;
                padding: 12px;
            }
        }

        /* Responsive Design 830px*/
        @media (min-width: 561px) and (max-width: 880px) {
            .bottom-nav a i {
                font-size: 1.3em;
            }

            .filter-bar { display: flex; gap: 8px;}
            .filter-bar select, .filter-bar input[type="text"] { padding: 10px; font-size: 12px; width: 100%; max-width: 300px; }
            table { font-size: 10px; width: 100%; background: #fff; border-radius: 2px; text-align: left; overflow: auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
            th, td { padding: 7px 5px; text-align: left; }
            th { background-color: #3498db; color: #fff;}
            tbody td {
                text-align: left;
                border: none;
            }

            /* Style buttons for mobile */
            .action-btn,
            .action-viewbtn,
            .pay-btn{
                margin-bottom: 1px;
                font-size: 6px;
                padding: 4px 5px;
            }

            .action-btn.pay-btn {
                margin-bottom: 1px;
                font-size: 6px;
                padding: 4px 5px;
            }

            .action-btn.pay-btn:hover {
                margin-bottom: 1px;
                font-size: 6px;
                padding: 4px 5px;
            }

            .action-btn.paid-btn {
                margin-bottom: 1px;
                font-size: 6px;
                padding: 4px 5px;
            }

            .action-btn.waiting-btn {
                margin-bottom: 1px;
                font-size: 6px;
                padding: 4px 5px;
            }

            /* Modal Styling iedit mo to*/
            #billingModal {
                display: none;
                position: fixed;
                z-index: 10;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
            }

            .modal-content {
                background-color: #fff;
                margin: 25% auto;
                padding: 25px;
                width: 80%;
                max-width: 300px;
                border-radius: 8px;
                font-size: 12px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .close-modal {
                color: #aaa;
                float: right;
                font-size: 24px;
                cursor: pointer;
            }

            .close-modal:hover {
                color: #3498db;
            }

            .modal-content h3 {
                font-size: 1.5em;
                color: #3498db;
                margin-bottom: 30px;
            }

            .modal-details p {
                display: flex;
                padding: 0 2px;
                justify-content: space-between;
            }
            .modal-details p span {
                text-align: right;
                margin-left: auto;
            }

            /* Payment Modal */
            .modal-content {
                width: 85%;
                max-width: 450px;
            }

            #paymentForm label {
                font-size: 1em;
            }

            #paymentForm input[type="text"],
            #paymentForm input[type="number"] {
                font-size: 1em;
            }
        }
        
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <header>
        <nav class="top-nav">
            <h1><?php echo $_SESSION['FullName']; ?></h1>
            <div class="profile">
                <a href="coll_logout.php" style="color: white;">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h2>Billing Records</h2>
        <!-- Filter Bar -->
        <div class="filter-bar">
            <select id="periodSelect" name="period">
                <option value="">All Period</option>
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

                <label>Period:</label>
                <input type="text" id="paymentPeriod" readonly>

                <label>Last Bill (Outstanding Balance):</label>
                <input type="text" id="paymentLastBill" readonly>

                <label>Amount:</label>
                <input type="number" id="paymentAmount" min="1" required>
                
                <label for="paymentMethod">Payment Method:</label>
                <select id="paymentMethod" name="paymentMethod">
                    <option value="Cash">Cash</option>
                    <option value="GCash">E-Payment</option>
                </select>    
                
                <button class="btn-submit"type="button" onclick="savePayment()">Save Payment</button>
                <button class="btn-cancel"type="button" onclick="closePaymentModal()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Bottom Navigation Bar -->
    <footer>
        <nav class="bottom-nav">
            <a href="collector_dash.php" >
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="collector_billing.php" class="active">
                <i class="fas fa-file-invoice"></i>
                <span>Billing</span>
            </a>
            <a href="collector_collection.php">
                <i class="fas fa-wallet"></i>
                <span>Collection</span>
            </a>
            <a href="collector_map.php">
                <i class="fas fa-map-marked-alt"></i>
                <span>Map</span>
            </a>
            <a href="collector_announcement.php">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
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
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);

                    // Dynamically populate modal fields with inline styles
                    document.getElementById("modalInvoiceNo").innerText = data.InvoiceNo;
                    document.getElementById("modalClientID").innerText = data.ClientID;
                    document.getElementById("modalDueDate").innerText = data.DueDate;
                    document.getElementById("modalPeriod").innerText = data.Period;
                    document.getElementById("modalPlan").innerText = data.Plan;
                    document.getElementById("modalDueAmount").innerText = `PHP ${data.DueAmount}`;
                    document.getElementById("modalDiscount").innerText = `PHP ${data.Discount}`;
                    document.getElementById("modalAmountPaid").innerText = `PHP ${data.AmountPaid}`;
                    document.getElementById("modalOutstandingBalance").innerText = `PHP ${data.OutstandingBalance}`;
                    document.getElementById("modalStatus").innerText = `${data.Status}`;

                    // Apply right alignment dynamically
                    const modalDataFields = document.querySelectorAll('#billingModal .data');
                    modalDataFields.forEach(field => {
                        field.style.textAlign = "right"; // Align text to the right
                        field.style.paddingRight = "100px"; // Add some spacing
                    });

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
                PaymentMethod: document.getElementById("paymentMethod").value,
                Period: document.getElementById("paymentPeriod").value,
                Amount: paymentAmount,
                PaymentStatus: "Waiting",
                PaymentDate: new Date().toISOString().slice(0, 19).replace('T', ' '),
                LastBill: lastBill,
                CollectedBy: "Collector"
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

        function openInvoice(invoiceNo) {
            window.open(`invoice.php?invoiceNo=${invoiceNo}`, '_blank');
        }

    </script>
</body>
</html>
