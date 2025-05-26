<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Autoload PHPMailer

// Function to send Acceptance Email with QR Code
function sendAcceptanceEmail($user_email, $appointment_time, $verification_code, $qr_file) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@allumnova.site';
        $mail->Password = 'Yuv@12345678';
        $mail->Port = 587;
        $mail->setFrom('info@allumnova.site', 'Alumnova');
        $mail->addAddress($user_email);
        $mail->Subject = "Appointment Confirmation";
        $mail->isHTML(true);

        // Email body with appointment details
        $mail->Body = "
            <h3>Your appointment has been confirmed!</h3>
            <p><strong>Appointment Time:</strong> $appointment_time</p>
            <p><strong>Verification Code:</strong> <span style='font-size:16px; font-weight:bold;'>$verification_code</span></p>
            <p>Use the verification code above or scan the attached QR code at check-in.</p>
            <p>Thank you for using our service!</p>
        ";

        // Attach QR Code
        if (file_exists($qr_file)) {
            $mail->addAttachment($qr_file, "QR_Code.png");
        }

        // Send email
        $mail->send();

    } catch (Exception $e) {
        die("Error sending email: " . $mail->ErrorInfo);
    }
}

// Function to send Rejection Email
function sendRejectionEmail($user_email) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@allumnova.site';
        $mail->Password = 'Yuv@12345678';
        $mail->Port = 587;
        $mail->setFrom('info@allumnova.site', 'Alumnova');
        $mail->addAddress($user_email);
        $mail->Subject = "Appointment Rejection Notification";
        $mail->isHTML(true);

        // Email body for rejection
        $mail->Body = "
            <h3>Your appointment request has been rejected.</h3>
            <p>Unfortunately, the host was unable to accept your appointment request.</p>
            <p>Please try requesting another time or contact the host for more details.</p>
            <p>Thank you for understanding!</p>
        ";

        // Send rejection email
        $mail->send();

    } catch (Exception $e) {
        die("Error sending rejection email: " . $mail->ErrorInfo);
    }
}
?>
