<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['otp'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = $_POST['otp'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    if ($enteredOtp == $_SESSION['otp']) {
        // Update password in the database
        $email = $_SESSION['reset_email'];
        $stmt = $conn->prepare("UPDATE login SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $newPassword, $email);

        if ($stmt->execute()) {
            unset($_SESSION['otp']);
            unset($_SESSION['reset_email']);
            $success = "Password reset successful! <a href='login.php'>Login here</a>";
        } else {
            $error = "Failed to reset password!";
        }
    } else {
        $error = "Invalid OTP!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-container {
            max-width: 450px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            height: 45px;
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .text-center a {
            color: #007bff;
            text-decoration: none;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="reset-container">
    <h2 class="text-center mb-4">Reset Password</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="otp" class="form-label">Enter OTP</label>
            <input type="text" id="otp" name="otp" class="form-control" placeholder="Enter OTP" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
