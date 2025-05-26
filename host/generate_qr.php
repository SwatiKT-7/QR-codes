<?php
session_start();
require '../config/db.php';

$appointment_id = isset($_GET['appointment_id']) ? $_GET['appointment_id'] : '';
$appt = $conn->query("SELECT * FROM appointments WHERE id = '$appointment_id'")->fetch_assoc();

$verification_code = rand(100000, 999999);
$appt_time = $appt['appointment_time'];

$conn->query("UPDATE appointments SET verification_code = '$verification_code' WHERE id = '$appointment_id'");

$verification_link = "http://localhost/qrcodes/host/verify_code.php?code=" . urlencode($verification_code);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate QR Code</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- QRCodeJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-2xl p-8 max-w-md w-full text-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Verification Code</h2>
        <p class="text-3xl text-blue-600 font-mono mb-2"><?= $verification_code ?></p>
        <p class="text-sm text-gray-500 mb-6">Appointment Time: <strong><?= $appt_time ?></strong></p>

        <p class="text-gray-700 mb-4">Scan the QR code below to verify your appointment:</p>
        <div id="qrcode" class="flex justify-center mb-6"></div>

        <p class="text-sm text-gray-600">Or click the link to verify:</p>
        <a href="<?= $verification_link ?>" target="_blank" class="text-blue-500 hover:underline break-all">
            <?= $verification_link ?>
        </a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var qrElement = document.getElementById("qrcode");
            var verificationLink = "<?= $verification_link ?>";

            if (qrElement && verificationLink) {
                new QRCode(qrElement, {
                    text: verificationLink,
                    width: 150,
                    height: 150
                });
            } else {
                console.error("QR Code element or link is missing.");
            }
        });
    </script>
</body>
</html>
