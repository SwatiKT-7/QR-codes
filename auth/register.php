<?php
session_start();
require '../config/db.php';
require '../config/smtp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $phone_no = $_POST['phone_no'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $type = $_POST['type'];
    
    // File Upload Handling
    $targetDir = "../assets/images/";
    $photoName = basename($_FILES["photo"]["name"]);
    $targetFile = $targetDir . $photoName;
    move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile);

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM login WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['register_data'] = [
            'username' => $username,
            'phone_no' => $phone_no,
            'email' => $email,
            'password' => $password,
            'type' => $type,
            'photo' => $photoName
        ];

        // Send OTP via Email
        if (sendOTP($email, $otp)) {
            header("Location: verify_otp.php");
            exit();
        } else {
            $error = "Failed to send OTP!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-container {
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
<div class="register-container">
    <h2 class="text-center mb-4">Register</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required>
        </div>
        <div class="mb-3">
            <label for="phone_no" class="form-label">Phone Number</label>
            <input type="text" id="phone_no" name="phone_no" class="form-control" placeholder="Enter phone number" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Profile Photo</label>
            <input type="file" id="photo" name="photo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Account Type</label>
            <select id="type" name="type" class="form-control" required>
                <option value="user">User</option>
                <option value="host">Host</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
