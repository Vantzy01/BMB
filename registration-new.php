<?php
include('db_connection.php');
function fetchPackages($conn) {
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
	<link rel="icon" href="Images/logo.ico"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
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
            background: url('Images/bg1.jpg') center no-repeat;
            background-size: cover;
        }
        
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
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
            ;
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
        
        .input-box .error, .input-checkbox .error {
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
        
        
        /* Responsive media query code for mobile devices */
        @media(max-width: 584px) {
            .container {
                max-width: 100%;
            }
        
            form .user-details .input-box {
                margin-bottom: 15px;
                width: 100%;
            }
        
            .content form .user-details {
                max-height: 500px;
                overflow-y: scroll;
            }
        
            .user-details::-webkit-scrollbar {
                width: 5px;
            }
        }
        
        /* Responsive media query code for mobile devices */
        @media(max-width: 459px) {
            .container {
                margin: 20px 5px;
                width: 95%;
                height: 80%;
                padding: 25px;
            }
            .container .content {
                flex-direction: column;
            }
            .content {
                max-height: 500px;
            }
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
						<input type="text" id="fullName" name="fullName" placeholder="Enter your username">
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
				<div class="button">
					<input type="submit" value="Register">
				</div>
				<div class="link">
					<p>Already a member? <a href="login.php">Login here</a></p>
				</div>
			</form>
		</div>
		<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
		<script>
			var map = L.map('map', {
				center: [16.99088, 121.6358],
				zoom: 13,
				zoomControl: false
			});

			var streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 19,
			}).addTo(map);

			var marker;

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