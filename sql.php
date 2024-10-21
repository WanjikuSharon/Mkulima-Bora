<?php
// Connect to the database
$servername = "localhost";
$username = "root";   // Default username for XAMPP
$password = "";       // Default password is empty
$dbname = "agriculture_portal";  // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());  // Display connection error
} else {
    echo "Connected successfully";  // Confirmation of successful connection
}
?>
