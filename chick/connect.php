<?php
$host = "localhost";       // Or your hosting DB server
$user = "root";            // Your DB username
$pass = "";                // Your DB password (on XAMPP it's usually empty)
$dbname = "chic-charm beads"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>