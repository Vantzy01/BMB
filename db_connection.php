<?php
$servername = "153.92.15.31"; 
$username = "u491558769_bmbweb";
$password = "BMBcell@2024";
$dbname = "u491558769_dbinternet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
