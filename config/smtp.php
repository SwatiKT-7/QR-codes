<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Make sure PHPMailer is installed via Composer

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
      
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@allumnova.site';
        $mail->Password = 'Yuv@12345678';
        $mail->Port = 587;
        $mail->setFrom('info@allumnova.site', 'Alumnova');
        $mail->addAddress($email);
        $mail->Subject = "Your OTP for Registration";
        $mail->Body = "Your OTP for registration is: $otp";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
