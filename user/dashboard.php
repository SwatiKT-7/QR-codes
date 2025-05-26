<?php
session_start();
require '../config/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// Fetch available hosts
$hosts = $conn->query("SELECT id, username, email, photo FROM login WHERE type='host'");

// Fetch previous appointments
$appointments = $conn->query("SELECT a.id, h.username as host_name, a.appointment_time, a.status 
                              FROM appointments a 
                              JOIN login h ON a.host_id = h.id 
                              WHERE a.user_id = '$user_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
        }
        /* Adjusting the height of the host image */
        .card img {
            height: 150px; /* Decreased height */
            object-fit: cover; /* Ensures the image covers the area without distortion */
        }
        .toggle-button {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }
        .toggle-button:hover {
            background-color: #f0f0f0;
        }
        .host-details {
            display: none;
            transition: all 0.3s ease-in-out;
        }
        .badge {
            font-size: 0.9em;
        }
        .table-responsive {
            overflow-x: auto;
        }
        @media (max-width: 768px) {
            .host-container {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
            }
            .table thead {
                display: none;
            }
            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
                background: white;
            }
            .table td {
                display: block;
                text-align: left;
                padding: 5px;
            }
            .table td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                color: #333;
            }
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Welcome, <?= htmlspecialchars($username) ?>!</h2>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
    </div>

    <h4 class="text-dark">Available Hosts</h4>
    <div class="row host-container">
        <?php while ($host = $hosts->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <button class="toggle-button" onclick="toggleDetails('host_<?= $host['id'] ?>')">
                    Dr. <?= htmlspecialchars($host['username']) ?>
                </button>
                <div id="host_<?= $host['id'] ?>" class="host-details card shadow mt-2 p-3">
                    <img src="../assets/images/<?= htmlspecialchars($host['photo']) ?>" class="card-img-top rounded mb-2" alt="Host Image">
                    <h5 class="text-primary text-center">Dr. <?= htmlspecialchars($host['username']) ?></h5>
                    <p class="text-muted text-center"><?= htmlspecialchars($host['email']) ?></p>
                    <form method="POST" action="request_appointment.php">
                        <input type="hidden" name="host_id" value="<?= $host['id'] ?>">
                        <label for="preferred_time_<?= $host['id'] ?>" class="form-label">Preferred Time:</label>
                        <input type="datetime-local" id="preferred_time_<?= $host['id'] ?>" name="preferred_time" class="form-control mb-2" required>
                        <button type="submit" class="btn btn-primary w-100">Request Appointment</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <h4 class="text-dark mt-5">Your Previous Appointments</h4>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Host</th>
                    <th>Appointment Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($appt = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Host">Dr. <?= htmlspecialchars($appt['host_name']) ?></td>
                        <td data-label="Appointment Time"><?= date("Y-m-d H:i", strtotime($appt['appointment_time'])) ?></td>
                        <td data-label="Status">
                            <?php if ($appt['status'] === 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif ($appt['status'] === 'accepted'): ?>
                                <span class="badge bg-success">Accepted</span>
                            <?php elseif ($appt['status'] === 'rejected'): ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleDetails(id) {
        var element = document.getElementById(id);
        if (element.style.display === "none" || element.style.display === "") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
