<?php
session_start();
include('db_connection.php');

// Check if the collector is logged in
if (!isset($_SESSION['CollectorID'])) {
    header("Location: collector_login.php");
    exit;
}

// Fetch client data
$clientData = [];
$query = "
    SELECT 
        ClientID, FullName, MobileNumber, Address, Email, DueDate, Status, 
        Latitude, Longitude, Landmark, tblplan.Plan, tblplan.MonthlyCost 
    FROM tblclient 
    LEFT JOIN tblplan ON tblclient.PlanID = tblplan.PlanID 
    WHERE Longitude IS NOT NULL AND Latitude IS NOT NULL";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $clientData[] = [
        'ClientID' => $row['ClientID'],
        'FullName' => $row['FullName'],
        'MobileNumber' => $row['MobileNumber'],
        'Address' => $row['Address'],
        'Email' => $row['Email'],
        'DueDate' => $row['DueDate'],
        'Status' => $row['Status'],
        'Latitude' => $row['Latitude'],
        'Longitude' => $row['Longitude'],
        'Plan' => $row['Plan'],
        'MonthlyCost' => $row['MonthlyCost'],
        'Landmark' => base64_encode($row['Landmark'])
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Client Mapping</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            overflow: hidden;
        }

        /* Top Navigation Bar */
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

        #map {
            height: 100vh;
            width: 100%;
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
            z-index: 1000;
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

        .panel {
            position: absolute;
            top: 130px;
            right: 30px;
            width: 400px;
            max-height: 650px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            z-index: 1000;
            overflow-y: auto;
            display: none;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-header h3 {
            margin: 0;
            font-size: 1.6em;
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5em;
            color: #888;
            cursor: pointer;
            padding: 0;
            margin-left: 10px;
            transition: color 0.3s;
        }

        .panel h3 {
            margin-bottom: 10px;
            font-size: 1.6em;
            color: #333;
        }

        .panel h4 {
            font-size: 1em;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }

        .panel p {
            font-size: 1em;
            color: #555;
            margin: 5px 0;
        }

        .info-section {
            margin: 20px;
        }

        .info-section h4 {
            flex:1;
            text-align: middle;
            font-size: 10px;
            color: #333;
        }

        .info-section p {
            margin: 5px 0;
            display: flex;
            justify-content: space-between;
        }

        .info-section strong {
            flex: 1;
            text-align: left;
        }

        .info-section span {
            flex: 1;
            text-align: right;
        }

        .landmark {
            height: 200px;
            width: 100%;
            background: lightgray;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 15px;
        }

        /* Map and search container styling */
        .search-container {
            position: absolute;
            top: 130px;
            left: 80px;
            width: 400px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .search-container .search-icon {
            font-size: 1.2em;
            color: #888;
        }

        #searchBar {
            width: 100%;
            padding: 15px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .search-container .clear-icon {
            font-size: 1.2em;
            color: #888;
            cursor: pointer;
            display: none;
        }

        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        #searchBar {
            width: 100%;
            padding: 15px 40px 15px 35px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .search-icon, .clear-icon {
            position: absolute;
            font-size: 1.2em;
            color: #555;
            cursor: pointer;
        }

        .search-icon {
            left: 7px;
        }

        .clear-icon {
            right: 10px;
            display: none;
        }

        #searchBar:focus + .clear-icon,
        #searchBar:not(:placeholder-shown) + .clear-icon {
            display: block;
        }

        .dropdown-panel {
            display: none;
            position: absolute;
            top: 100%;
            width: 100%;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            max-height: 250px;
            overflow-y: auto;
            z-index: 1001;
        }

        .dropdown-panel div {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .dropdown-panel div:hover {
            background-color: #f5f5f5;
        }

        .dropdown-panel .client-icon {
            font-size: 1.2em;
            color: #ff5a5f;
        }
        /* Responsive Design */
        @media (max-width: 600px) {
            .top-nav h1 {
                font-size: 1em;
            }
            .card p {
                font-size: 1.5em;
            }

            .card h2 {
                font-size: 1em;
            }

            .bottom-nav a span {
                display: none;
            }

            .bottom-nav a i {
                font-size: 1.8em;
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

    <div id="map"></div>
    <div class="search-container">
        <div class="search-wrapper">
            <span class="search-icon">&#x1F50D;</span>
            <input type="text" id="searchBar" placeholder="Search clients..." oninput="filterClients()" onclick="showDropdown()">
            <span class="clear-icon" onclick="clearSearch()">&#x2715;</span>
        </div>
        <div id="searchResults" class="dropdown-panel"></div>
    </div>
    
    <div class="panel" id="clientPanel"> 
        <div class="panel-header">
            <h3 id="clientName">Client Name</h3>
            <button onclick="closePanel()" class="close-btn">x</button>
        </div>

        <div class="info-section">
            <h4>Location</h4>
            <p id="clientAddress">Address: N/A</p>
            <p id="clientCoordinates">Coordinates: N/A</p>
            <hr>
            <h4>Contact Information</h4>
            <p><strong>Mobile:</strong> <span id="clientMobile">N/A</span></p>
            <p><strong>Email:</strong> <span id="clientEmail">N/A</span></p>
            <hr>
            <h4>Subscription Plan</h4>
            <p><strong>Plan: </strong> <span id="clientPlan">N/A</span></p>
            <p><strong>Monthly Bill: </strong> ₱<span id="clientMonthlyCost">N/A</span></p>
            <p><strong>Due Date: </strong> <span id="clientDueDate">N/A</span></p>
            <hr>
            <div id="landmarkImage" class="landmark">
                No Landmark Found
            </div>
        </div>

        
    </div>
    
    <!-- Bottom Navigation Bar -->
    <footer>
        <nav class="bottom-nav">
            <a href="collector_dash.php">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="collector_billing.php">
                <i class="fas fa-file-invoice"></i>
                <span>Billing</span>
            </a>
            <a href="collector_collection.php">
                <i class="fas fa-wallet"></i>
                <span>Collection</span>
            </a>
            <a href="collector_map.php" class="active">
                <i class="fas fa-map-marked-alt"></i>
                <span>Map</span>
            </a>
            <a href="collector_announcement.php">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
        </nav>
    </footer>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize the map
        var map = L.map('map').setView([16.99088, 121.6358], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 20}).addTo(map);

        // Add client markers
        var clients = <?php echo json_encode($clientData); ?>;
        clients.forEach(client => {
            var marker = L.marker([client.Latitude, client.Longitude]).addTo(map);
            marker.bindPopup(`<b>${client.FullName}</b><br>${client.Address}`);
            marker.on('click', () => showClientInfo(client));
        });

        function showClientInfo(client) {
            document.getElementById("clientPanel").style.display = "block";
            document.getElementById("clientName").textContent = client.FullName;
            document.getElementById("clientAddress").textContent = `Address: ${client.Address}`;
            document.getElementById("clientCoordinates").textContent = `Coordinates: ${client.Latitude}, ${client.Longitude}`;
            document.getElementById("clientMobile").textContent = `${client.MobileNumber}`;
            document.getElementById("clientEmail").textContent = client.Email;
            document.getElementById("clientPlan").textContent = client.Plan;
            document.getElementById("clientMonthlyCost").textContent = client.MonthlyCost;
            document.getElementById("clientDueDate").textContent = client.DueDate;

            if (client.Landmark) {
                document.getElementById("landmarkImage").innerHTML = `<img src="data:image/jpeg;base64,${client.Landmark}" width="100%" height="100%">`;
            } else {
                document.getElementById("landmarkImage").textContent = "No Landmark Found";
            }
        }


        function closePanel() {
            document.getElementById("clientPanel").style.display = "none";
        }

        function filterClients() {
            var input = document.getElementById('searchBar').value.toLowerCase();
            var resultsPanel = document.getElementById('searchResults');
            resultsPanel.innerHTML = '';
            clients.filter(client => client.FullName.toLowerCase().includes(input))
                .forEach(client => {
                    var div = document.createElement('div');
                    
                    // Create the client icon
                    var icon = document.createElement('span');
                    icon.className = 'client-icon';
                    icon.innerHTML = '&#128100;'; // This adds a person emoji as the icon
                    
                    // Add the icon and client name to the result item
                    div.appendChild(icon);
                    div.appendChild(document.createTextNode(client.FullName));
                    
                    // Set click event to focus on the client
                    div.onclick = () => focusClient(client);
                    resultsPanel.appendChild(div);
                });
            resultsPanel.style.display = input ? 'block' : 'none';
            document.querySelector('.clear-icon').style.display = input ? 'block' : 'none';
        }


        function focusClient(client) {
            map.setView([client.Latitude, client.Longitude], 16);
            showClientInfo(client);
            document.getElementById('searchResults').style.display = 'none';
        }

        function clearSearch() {
            document.getElementById('searchBar').value = '';
            document.getElementById('searchResults').style.display = 'none';
            document.querySelector('.clear-icon').style.display = 'none';
        }
    </script>
</body>
</html>
