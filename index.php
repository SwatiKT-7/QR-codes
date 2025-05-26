<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .left-section {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            background: linear-gradient(to right, #4b6cb7, #182848);
            color: white;
            padding: 2rem;
            height: 100vh;
        }
        .left-section img {
            max-width: 80%;
            border-radius: 10px;
            margin-top: 1rem;
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }
        @media (max-width: 768px) {
            .left-section {
                display: none;
            }
            .form-container {
                max-width: 100%;
                padding: 2rem;
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-0 m-0">

    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <!-- Left Section (Hidden on small screens) -->
            <div class="col-md-6 left-section hidden md:flex">
                <div>
                    <h2 class="text-3xl font-bold">Welcome</h2>
                    <p class="text-lg">Join us and manage your meetings efficiently.</p>
                    <img src="assets/images/meeting.jpg" alt="Meeting Image">
                </div>
            </div>

            <!-- Right Section -->
            <div class="col-md-6 d-flex align-items-center justify-content-center p-0">
                <div class="form-container w-full">
                    <h2 class="text-2xl font-semibold text-center mb-4">Register</h2>
                    <form method="POST" enctype="multipart/form-data" action="auth/register.php">
                        <div class="form-group">
                            <input type="text" name="username" placeholder="Username" class="form-control p-3 rounded" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="phone_no" placeholder="Phone Number" class="form-control p-3 rounded" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" class="form-control p-3 rounded" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password" class="form-control p-3 rounded" required>
                        </div>
                        <div class="form-group">
                            <input type="file" name="photo" class="form-control p-3 rounded" required>
                        </div>
                        <div class="form-group">
                            <select name="type" class="form-control p-3 rounded" required>
                                <option value="user">User</option>
                                <option value="host">Host</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block p-3 rounded text-lg">Register</button>
                    </form>
                    <p class="text-center mt-3">Already have an account? 
                        <a href="auth/login.php" class="text-blue-600 hover:underline">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
