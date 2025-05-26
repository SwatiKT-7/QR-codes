<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = $_POST['otp'];

    if ($enteredOtp == $_SESSION['otp']) {
        // Insert user into database
        $data = $_SESSION['register_data'];
        $stmt = $conn->prepare("INSERT INTO login (username, phone_no, email, password, type, photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $data['username'], $data['phone_no'], $data['email'], $data['password'], $data['type'], $data['photo']);

        if ($stmt->execute()) {
            unset($_SESSION['otp']);
            unset($_SESSION['register_data']);
            $success = "Registration successful! Redirecting to login...";
            echo "<script>
                    setTimeout(() => { window.location.href = 'login.php'; }, 2000);
                  </script>";
        } else {
            $error = "Registration failed! Please try again.";
        }
    } else {
        $error = "Invalid OTP! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 15px;
        }
        .otp-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-control {
            height: 45px;
            border-radius: 8px;
            padding: 10px;
        }
        .btn-primary {
            background-color: #2563eb;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .text-center a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="otp-container">
    <h2 class="text-center text-2xl font-bold mb-4">Verify OTP</h2>
    
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
            <input type="text" id="otp" name="otp" class="form-control text-center text-lg font-semibold tracking-widest" placeholder="XXXXXX" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Verify</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
