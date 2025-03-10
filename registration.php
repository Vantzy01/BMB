<?php
include('db_connection.php');
function fetchPackages($conn)
{
    $sql = "SELECT PlanID, Plan FROM tblplan WHERE Status='1'";
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
    <title> Registration Form - BMB Cell </title>
    <link rel="icon" href="Images/logo.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
    <script defer src="registration.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                url('Images/bg1.jpg') center no-repeat;
            background-size: cover;
        }

        .container {
            max-width: 700px;
            width: 100%;
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
            z-index: 2;
            margin: 80px;
            animation: fadeIn 0.3s ease-in;
            overflow: hidden;
        }

        .container .title {
            font-size: 25px;
            font-weight: bolder;
            position: relative;
            color: #00aaff;
        }

        .container .title::before {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 30px;
            border-radius: 5px;
            background-color: #00aaff;
        }

        .content form .user-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 20px 0 12px 0;
        }

        form .user-details .input-box {
            margin-bottom: 5px;
            width: calc(100% / 2 - 20px);
        }

        form .input-box span.details {
            display: block;
            font-weight: 500;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .user-details .input-box input {
            height: 45px;
            width: 100%;
            outline: none;
            font-size: 16px;
            border-radius: 5px;
            padding-left: 15px;
            border: 1px solid #ccc;
            border-bottom-width: 2px;
            transition: all 0.3s ease;
        }

        .user-details .input-box input:focus,
        .user-details .input-box input:valid {
            border-color: #00aaff;
        }

        /* Design for package */
        .user-details .input-box select {
            height: 45px;
            width: 100%;
            outline: none;
            font-size: 16px;
            border-radius: 5px;
            padding-left: 15px;
            border: 1px solid #ccc;
            border-bottom-width: 2px;
            transition: all 0.3s ease;
        }

        .user-details .input-box select:focus,
        .user-details .input-box select:valid {
            border-color: #00aaff;
        }

        .user-details .instruction-text {
            font-size: 10px;
        }

        .input-box .error,
        .input-checkbox .error {
            color: #ff3860;
            font-size: 9px;
            height: 10px;
        }

        #map {
            height: 200px;
            width: 100%;
            margin: auto;
            border-radius: 5px;
        }

        form .button {
            height: 45px;
            margin: 20px 0
        }

        form .button input {
            height: 100%;
            width: 100%;
            border-radius: 5px;
            border: none;
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #00aaff;
        }

        form .button input:hover {
            background: #2563eb;
        }

        .back-link {
            display: inline-block;
            margin-bottom: -25px;
            width: 100%;
            text-align: center;
            text-decoration: none;
            align-self: center;
            transition: all 0.2s;
        }

        .back-link:hover {
            text-decoration: underline;
            transform: scale(1.03);
        }

        /* Responsive media query code for mobile devices */
        @media(max-width: 600px) {
            .container {
                max-width: 100%;
                margin: 5px 5px;
                width: 95%;
                height: 88%;
            }

            form .user-details .input-box {
                margin-bottom: 10px;
                width: 100%;
            }

            form .input-box span.details {
                margin: 0;
            }

            form .user-details .input-box {
                margin: 0;
            }

            .content form .user-details {
                max-height: 450px;
                overflow-y: scroll;
            }

            .user-details::-webkit-scrollbar {
                width: 5px;
            }

            .link p {
                margin-top: 10px;
                font-size: 12px;
            }

            form .button {
                margin: 10px 0;
            }

            form .back-link {
                font-size: 12px;
            }
        }

        /* Responsive media query code for mobile devices */
        @media(max-width: 500px) {
            .container {
                margin: 5px 5px;
                width: 95%;
                height: 88%;
                padding: 25px;
            }

            .container .content {
                flex-direction: column;
            }

            .content {
                max-height: 500px;
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            100% {
                opacity: 100;
                transform: scale(1);
            }
        }

        .leaflet-control-fullscreen {
            width: 36px;
            height: 36px;
            background-color: black;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            background-image: url('https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/icon-fullscreen.png');
            background-size: cover;
        }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div class="container">
        <!-- Title section -->
        <div class="title">BMB Registration</div>
        <div class="content">
            <!-- Registration form -->
            <form id="form" action="register.php" method="post" onsubmit="return validateForm()">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Full Name</span>
                        <input type="text" id="fullName" name="fullName" placeholder="Enter your full name">
                        <div class="error"></div>
                    </div>
                    <div class="input-box">
                        <span class="details">Username</span>
                        <input type="text" id="username" name="username" placeholder="Enter your username">
                        <div class="error"></div>
                    </div>
                    <div class="input-box">
                        <span class="details">Email</span>
                        <input type="text" id="email" name="email" placeholder="Enter your email">
                        <div class="error"></div>
                    </div>
                    <div class="input-box">
                        <span class="details">Phone Number</span>
                        <input type="text" id="mobileNo" name="mobileNo" placeholder="Enter your number">
                        <div class="error"></div>
                    </div>
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" id="password" name="password" placeholder="Enter your password">
                        <div class="error"></div>
                    </div>
                    <div class="input-box">
                        <span class="details">Confirm Password</span>
                        <input type="password" id="password2" name="password2" placeholder="Confirm your password">
                        <div class="error"></div>
                    </div>
                    <div class="input-box">
                        <span class="details">Address</span>
                        <input type="text" id="address" name="address" placeholder="Enter your address">
                        <div class="error"></div>
                    </div>
                    <div class="input-box">
                        <span class="details">Package</span>
                        <select id="package" name="package">
                            <option value="">Select Package</option>
                            <?php echo $options; ?>
                        </select>
                        <div class="error"></div>
                    </div>
                    <div class="instruction-text">
                        <p>Place the blue pin exactly where you live. Pinch or scroll to zoom the map. Click or tap to place it at the roof of your house.</p>
                    </div>
                    <div id="map"></div>
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">
                </div>
                <!-- Submit button -->
                <div class="link">
                    <p>Already a member? <a href="login.php">Login here</a></p>
                </div>
                <div class="button">
                    <input type="submit" value="Register">
                </div>
                <a href="index.php" class="back-link"><i class="fas fa-arrow-left" style=margin-right:5px;></i>Back to Homepage</a>
            </form>
        </div>
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
        <script>
            var map = L.map('map', {
                center: [16.99088, 121.6358],
                zoom: 13,
                zoomControl: false
            });

            var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });

            var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            });

            googleStreets.addTo(map);

            var baseLayers = {
                "Street View": googleStreets,
                "Satellite View": googleSat
            };

            L.control.layers(baseLayers).addTo(map);

            L.control.fullscreen({
                position: 'topleft'
            }).addTo(map);

            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });

            function validateForm() {
                let lat = document.getElementById('latitude').value.trim();
                let lng = document.getElementById('longitude').value.trim();

                if (!lat || !lng) {
                    alert("Please set your location before submitting.");
                    return false;
                }

                return true;
            }
        </script>

    </div>
</body>

</html>