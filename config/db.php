
<?php
$host = "localhost"; // Change if needed
$username = "root"; // Change as per your database
$password = ""; // Change as per your database
$database = "v";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
