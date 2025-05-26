<?php
session_start();
require '../config/db.php';
require_once 'smtp.php'; // Ensure SMTP functions are loaded only once

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the appointment ID and user email
$appointment_id = $_POST['appointment_id'] ?? null;
$user_email = $_POST['user_email'] ?? null;

if (!$appointment_id || !$user_email) {
    die("Missing appointment ID or user email.");
}

// Handle appointment acceptance
if (isset($_POST['accept'])) {
    $confirmed_time = $_POST['confirmed_time'];

    if (empty($confirmed_time)) {
        die("Appointment time is required!");
    }

    // Generate a verification code
    $verification_code = rand(100000, 999999);

    // Update appointment status and time
    $stmt = $conn->prepare("UPDATE appointments SET appointment_time = ?, status = 'accepted', verification_code = ? WHERE id = ?");
    $stmt->bind_param("ssi", $confirmed_time, $verification_code, $appointment_id);
    $stmt->execute();

    // Generate QR code containing only the verification code
    $qr_file = "../uploads/qr_$appointment_id.png"; // Save QR code to uploads folder
    $qr_data = $verification_code; // Only the code
    $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qr_data);
    file_put_contents($qr_file, file_get_contents($qr_url));

    // Send acceptance email with QR code and verification code
    sendAcceptanceEmail($user_email, $confirmed_time, $verification_code, $qr_file, null);

} elseif (isset($_POST['reject'])) {
    // Reject the appointment
    $stmt = $conn->prepare("UPDATE appointments SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    // Send rejection email
    sendRejectionEmail($user_email);
}

// Redirect to the dashboard after handling
header("Location: dashboard.php");
exit();
?>
