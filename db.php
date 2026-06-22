<?php
// db.php
$servername = "localhost";
$username = "root";
$password = ""; // XAMPP mein default password khali hota hai
$dbname = "event_db";

// Connection banana
$conn = new mysqli($servername, $username, $password, $dbname);

// Check karna ke connection hua ya nahi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>