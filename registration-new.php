<?php
include('db_connection.php');
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
	<title> Registration Form - BMB Aurora </title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
	<script defer src="registration.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="registration.css" />
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
					<div class="input-checkbox">
						<input type="checkbox" id="terms" name="terms">
						<label for="terms">I agree to the terms and conditions applicable</label>
                        <div class="error"></div>
					</div>
				</div>
				<!-- Submit button -->
				<div class="button">
					<input type="submit" value="Register">
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