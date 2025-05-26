<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, phone_no, email, password, type, photo FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['photo'] = $user['photo'];

            // Redirect to dashboard based on user type
            if ($user['type'] == 'user') {
                header("Location: ../user/dashboard.php");
            } else {
                header("Location: ../host/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
        }
        .login-container {
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

<div class="login-container">
    <h2 class="text-center text-2xl font-bold mb-4">Login</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-center mt-3">
        <a href="forgot_password.php">Forgot Password?</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
