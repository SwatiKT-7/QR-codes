<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $host_id = $_POST['host_id'];
    $preferred_time = $_POST['preferred_time'];
    
    // Insert into appointments table
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, host_id, appointment_time, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("iis", $user_id, $host_id, $preferred_time);

    if ($stmt->execute()) {
        echo "Appointment requested successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Failed to request appointment!";
    }
}
?>
