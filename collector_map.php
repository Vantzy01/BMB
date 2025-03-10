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
        tblclient.ClientID, 
        tblclient.FullName, 
        tblclient.MobileNumber, 
        tblclient.Address, 
        tblclient.Email, 
        tblclient.DueDate, 
        tblclient.Status,  -- Specify the table name here
        tblclient.Latitude, 
        tblclient.Longitude, 
        tblclient.Landmark, 
        tblplan.Plan, 
        tblplan.MonthlyCost 
    FROM tblclient 
    LEFT JOIN tblplan ON tblclient.PlanID = tblplan.PlanID 
    WHERE tblclient.Longitude IS NOT NULL AND tblclient.Latitude IS NOT NULL";


// Execute the query first
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

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
    <link rel="icon" href="Images/logo.ico" />
    <title>Client Mapping - BMB Cell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
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

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        /* Top Navigation Bar */
        .top-nav {
            background-color: #2C3E50;
            color: white;
            display: flex;
            justify-content: space-between;
            padding: 1em 2em;
            align-items: center;
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
            position: fixed;
            top: 190px;
            right: 20px;
            width: 300px;
            max-height: 80vh;
            padding: 15px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1000;
            overflow-y: auto;
            display: none;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .panel-header h3 {
            margin: 0;
            font-size: 1.2em;
            font-weight: 600;
            color: #222;
            white-space: wrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.2em;
            color: #666;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s ease;
        }

        .close-btn:hover {
            color: #000;
        }

        .info-section h4 {
            margin: 10px 0 5px;
            font-size: 0.9em;
            font-weight: 600;
            color: #444;
            text-transform: uppercase;
        }

        .info-section p {
            margin: 5px 0;
            font-size: 0.85em;
            color: #555;
            display: flex;
            justify-content: space-between;
        }

        .info-section strong {
            font-weight: 600;
            color: #333;
        }

        .info-section span {
            text-align: right;
            font-weight: 400;
            color: #555;
        }

        .landmark {
            height: 150px;
            width: 100%;
            background: #e9e9e9;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            overflow: hidden;
            font-size: 0.85em;
            color: #666;
            margin-top: 10px;
        }

        /* Adjustments for small screens */
        @media (max-width: 480px) {
            .panel {
                top: 150px;
                right: 10px;
                width: 90%;
                max-height: 85vh;
                padding: 10px;
            }

            .panel-header h3 {
                font-size: 1em;
            }

            .info-section h4 {
                font-size: 0.8em;
            }

            .info-section p {
                font-size: 0.75em;
            }

            .landmark {
                height: 120px;
                font-size: 0.75em;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 560px) {
            .top-nav h1 {
                font-size: 1em;
            }

            .container h2 {
                font-size: 0.9em;
            }

            .bottom-nav a span {
                display: none;
            }

            .bottom-nav a i {
                font-size: 1.8em;
            }
        }

        /* Map and search container styling */
        .search-container {
            position: absolute;
            top: 120px;
            left: 10px;
            right: 10px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* Search wrapper with icon and clear button */
        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Search input field */
        #searchBar {
            width: 100%;
            padding: 15px 40px 12px 45px;
            /* Padding to fit icons */
            font-size: 0.9em;
            border: none;
            border-radius: 8px;
            outline: none;
        }

        /* Search and clear icons */
        .search-icon,
        .clear-icon {
            position: absolute;
            font-size: 1.4em;
            color: #666;
            cursor: pointer;
        }

        .search-icon {
            left: 10px;
        }

        .clear-icon {
            right: 10px;
            display: none;
        }

        #searchBar:focus+.clear-icon,
        #searchBar:not(:placeholder-shown)+.clear-icon {
            display: block;
        }

        /* Dropdown panel for results */
        .dropdown-panel {
            display: none;
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            width: 100%;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1001;
        }

        /* Dropdown items */
        .dropdown-panel div {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            font-size: 0.9em;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }

        .dropdown-panel div:hover {
            background-color: #f9f9f9;
        }

        .dropdown-panel .client-icon {
            font-size: 1.2em;
            color: #ff5a5f;
        }

        /* Responsive Design */
        @media (max-width: 560px) {
            .search-container {
                top: 100px;
                padding: 1px 0.5em 0.5em;
            }

            #searchBar {
                font-size: 0.85em;
                padding: 15px 35px 10px 45px;
            }

            .dropdown-panel div {
                padding: 10px;
                font-size: 0.85em;
            }

            .search-icon,
            .clear-icon {
                font-size: 1.2em;
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
                    <i class="fas fa-sign-out-alt"> Logout</i>
                </a>
            </div>
        </nav>
    </header>
    <div id="map"></div>
    <div class="search-container">
        <div class="search-wrapper">
            <span class="search-icon">&#x1F50D;</span>
            <input
                type="text"
                id="searchBar"
                placeholder="Search clients..."
                oninput="filterClients()"
                onclick="showDropdown()" />
            <span class="clear-icon" onclick="clearSearch()">&#x2715;</span>
        </div>
        <div id="searchResults" class="dropdown-panel" onclick="clearSearch()"></div>
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
        var map = L.map('map', {
            center: [16.99088, 121.6358],
            zoom: 16,
            zoomControl: false,
            fullscreenControl: true
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20
        }).addTo(map);

        var clients = <?php echo json_encode($clientData); ?>;
        clients.forEach(client => {
            var marker = L.marker([client.Latitude, client.Longitude]).addTo(map);

            // Create a custom HTML content for the popup
            var popupContent = `
                <div style="width: 300px; max-height: 80vh; overflow-y: auto;">
                    <div class="panel-header">
                        <h3 style="margin: 0; font-size: 1.2em; font-weight: 600; color: #222; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            ${client.FullName}
                        </h3>
                    </div>
                    <div class="info-section">
                        <p>${client.Address || 'N/A'}</p>
                        <hr>
                        <h4 style="font-size: 0.9em; font-weight: 600; color: #444; text-transform: uppercase;">Contact</h4>
                        <p><strong>Mobile:</strong> <span>${client.MobileNumber || 'N/A'}</span></p>
                        <p><strong>Email:</strong> <span>${client.Email || 'N/A'}</span></p>
                        <hr>
                        <h4 style="font-size: 0.9em; font-weight: 600; color: #444; text-transform: uppercase;">Subscription Plan</h4>
                        <p><strong>Plan:</strong> <span>${client.Plan || 'N/A'}</span></p>
                        <p><strong>Monthly Bill:</strong> <span>₱ ${client.MonthlyCost || 'N/A'}</span></p>
                        <p><strong>Due Date:</strong> <span>${client.DueDate || 'N/A'}</span></p>
                        <hr>
                        <div style="height: 150px; width: 100%; background: #e9e9e9; display: flex; align-items: center; justify-content: center; border-radius: 6px; overflow: hidden; font-size: 0.85em; color: #666; margin-top: 10px;">
                            ${
                                client.Landmark
                                    ? `<img src="data:image/jpeg;base64,${client.Landmark}" width="100%" height="100%">`
                                    : 'No Landmark Found'
                            }
                        </div>
                    </div>
                </div>
            `;
            marker.bindPopup(popupContent);
        });

        function showClientInfo(client) {
            document.getElementById("clientPanel").style.display = "block";
            document.getElementById("clientName").textContent = client.FullName;
            document.getElementById("clientAddress").textContent = client.Address;
            document.getElementById("clientMobile").textContent = `${client.MobileNumber}`;
            document.getElementById("clientEmail").textContent = client.Email;
            document.getElementById("clientPlan").textContent = client.Plan;
            document.getElementById("clientMonthlyCost").textContent = `₱ ${client.MonthlyCost}`;
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
                    var icon = document.createElement('span');
                    icon.className = 'client-icon';
                    icon.innerHTML = '&#128100;';

                    div.appendChild(icon);
                    div.appendChild(document.createTextNode(client.FullName));

                    div.onclick = () => focusClient(client);
                    resultsPanel.appendChild(div);
                });
            resultsPanel.style.display = input ? 'block' : 'none';
            document.querySelector('.clear-icon').style.display = input ? 'block' : 'none';

        }


        function focusClient(client) {
            // Set the map view to the client's location
            map.setView([client.Latitude, client.Longitude], 15);

            // Find the marker associated with the client and bind/open the popup
            var selectedMarker = L.marker([client.Latitude, client.Longitude]).addTo(map);

            // Create a custom popup content for the selected client
            var popupContent = `
                <div style="width: 300px; max-height: 80vh; overflow-y: auto;">
                    <div class="panel-header">
                        <h3 style="margin: 0; font-size: 1.2em; font-weight: 600; color: #222; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            ${client.FullName}
                        </h3>
                    </div>
                    <div class="info-section">
                        <p>${client.Address || 'N/A'}</p>
                        <hr>
                        <h4 style="font-size: 0.9em; font-weight: 600; color: #444; text-transform: uppercase;">Contact</h4>
                        <p><strong>Mobile:</strong> <span>${client.MobileNumber || 'N/A'}</span></p>
                        <p><strong>Email:</strong> <span>${client.Email || 'N/A'}</span></p>
                        <hr>
                        <h4 style="font-size: 0.9em; font-weight: 600; color: #444; text-transform: uppercase;">Subscription Plan</h4>
                        <p><strong>Plan:</strong> <span>${client.Plan || 'N/A'}</span></p>
                        <p><strong>Monthly Bill:</strong> <span>₱ ${client.MonthlyCost || 'N/A'}</span></p>
                        <p><strong>Due Date:</strong> <span>${client.DueDate || 'N/A'}</span></p>
                        <hr>
                        <div style="height: 150px; width: 100%; background: #e9e9e9; display: flex; align-items: center; justify-content: center; border-radius: 6px; overflow: hidden; font-size: 0.85em; color: #666; margin-top: 10px;">
                            ${
                                client.Landmark
                                    ? `<img src="data:image/jpeg;base64,${client.Landmark}" width="100%" height="100%">`
                                    : 'No Landmark Found'
                            }
                        </div>
                    </div>
                </div>
            `;
            selectedMarker.bindPopup(popupContent).openPopup();
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