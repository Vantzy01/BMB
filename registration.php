<?php
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

function fetchPackages($conn) {
    $sql = "SELECT PlanID, Plan FROM tblplan";
    $result = mysqli_query($conn, $sql);

    $options = '';
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='{$row['PlanID']}'>{$row['Plan']}</option>";
        }
    } else {
        $options .= "<option value=''>No Packages Available</option>";
    }
    
    return $options;
}
$options = fetchPackages($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BMB Internet Service</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
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
        }

        /* Overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        /* Registration Form */
        .registration-container {
            position: relative;
            z-index: 2;
            width: 600px;
            margin: 50px auto;
            padding: 40px 30px;
            background-color: rgba(31, 31, 31);
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.6);
            text-align: center;
            color: #fff;
        }

        .registration-container h2 {
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        /* Add a consistent width for inputs and select */
        .form-group input, 
        .form-group select {
            width: calc(100% - 30px); /* Adjust to fit icons or keep consistent */
            padding: 12px 15px;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #2a2a2a;
            color: #fff;
            font-size: 1rem;
            transition: border 0.3s;
        }

        .form-group input:focus, 
        .form-group select:focus {
            border-color: #00aaff;
        }

        /* Input Icon Wrapper */
        .input-icon {
            position: relative;
        }

        .input-icon input {
            padding-left: 40px;
        }

        .input-icon i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: white;
        }

        .cta {
            padding: 15px 30px;
            background-color: #00aaff;
            color: #fff;
            font-size: 1.2em;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            margin-top: 20px;
        }

        .cta:hover {
            background-color: #0088cc;
        }

        .registration-container p {
            margin-top: 20px;
        }

        .registration-container a {
            color: #00aaff;
            text-decoration: none;
        }

        .registration-container a:hover {
            color: #0088cc;
        }

        /* Map Styling */
        #map {
            height: 400px;
            width: 100%;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        /* Checkbox styling */
        .form-group-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-group-checkbox input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }

        /* Instructional Text */
        .instruction-text {
            font-size: 0.9rem;
            color: #bbb;
            margin-bottom: 15px;
        }

        /* Icon for Terms and Conditions */
        .form-group-checkbox label {
            margin: 0;
            font-size: 0.9rem;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Background overlay -->
    <div class="overlay"></div>

    <!-- Registration container -->
    <div class="registration-container">
        <h2>Register for BMB Internet Service</h2>
        <form id="registrationForm" action="register.php" method="post" onsubmit="return validateForm()">
            
            <div class="form-group input-icon">
                <input type="text" id="fullName" name="fullName" placeholder="Full name" required>
                <i class="fas fa-user"></i>
            </div>

            <div class="form-group input-icon">
                <input type="email" id="email" name="email" placeholder="Email" required>
                <i class="fas fa-envelope"></i>
            </div>

            <div class="form-group input-icon">
                <input type="tel" id="mobileNo" name="mobileNo" pattern="[0-9]{11}" placeholder="Mobile Number" required>
                <i class="fas fa-phone"></i>
            </div>

            <div class="form-group input-icon">
                <input type="text" id="username" name="username" placeholder="Username" required>
                <i class="fas fa-user-circle"></i>
            </div>

            <div class="form-group input-icon">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class="fas fa-lock"></i>
            </div>

            <div class="form-group input-icon">
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                <i class="fas fa-lock"></i>
            </div>

            <div class="form-group input-icon">
                <input type="text" id="address" name="address" placeholder="Address" required>
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <br>
            <hr>
            <br>

            <!-- Package Selection -->
            <div class="form-group">
                <label for="package">Package:</label>
                <select id="package" name="package" required>
                    <?php echo $options; ?>
                </select>
            </div>

            <!-- Instructional Text -->
            <div class="instruction-text">
                <p>Place the blue pin exactly where you live. Pinch or scroll to zoom the map. Click or tap to place it at the roof of your house.</p>
            </div>
            
            <!-- Map -->
            <div id="map"></div>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <!-- Terms and conditions -->
            <div class="form-group-checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the terms and conditions applicable</label>
            </div>

            <!-- Register Button -->
            <button type="submit" class="cta">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Log in</a></p>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        function validateForm() {
            const fullName = document.getElementById('fullName').value.trim();
            const email = document.getElementById('email').value.trim();
            const mobileNo = document.getElementById('mobileNo').value.trim();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirmPassword').value.trim();
            const terms = document.getElementById('terms').checked;

            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return false;
            }

            if (!terms) {
                alert('Please agree to the terms and conditions.');
                return false;
            }

            return true;
        }

        // Leaflet map initialization
        var map = L.map('map').setView([16.99088, 121.6358], 13);

        // Street view layer only, satellite view removed
        var streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var marker;

        // On map click, place or move marker
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            // Set latitude and longitude values in hidden inputs
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    </script>
</body>
</html>