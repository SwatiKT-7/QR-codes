<?php
session_start();
require '../config/db.php';

if (!isset($_GET['appointment_id'])) {
    echo "<div class='alert alert-danger'>Invalid Request!</div>";
    exit();
}

$appointment_id = $_GET['appointment_id'];

$stmt = $conn->prepare("SELECT a.id, a.user_id, a.appointment_time, a.status, a.check_in, a.check_out,
                               u.username, u.email, u.phone_no, u.photo 
                               FROM appointments a 
                               JOIN login u ON a.user_id = u.id 
                               WHERE a.id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $appt = $result->fetch_assoc();
} else {
    echo "<div class='alert alert-danger'>Invalid Appointment ID!</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="container max-w-lg bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center">📋 User Details</h2>

    <div class="flex items-center space-x-4 mt-4">
        
        <img src="../assets/images/<?= htmlspecialchars($appt['photo']); ?>" class="rounded-full border-2 border-gray-300" width="100" alt="User Photo">
        <div>
            <h5 class="text-lg font-semibold"><?= htmlspecialchars($appt['username']); ?></h5>
            <p>Email: <b><?= htmlspecialchars($appt['email']); ?></b></p>
            <p>Phone: <b><?= htmlspecialchars($appt['phone_no']); ?></b></p>
            <p>Appointment Time: <b><?= htmlspecialchars($appt['appointment_time']); ?></b></p>
        </div>
    </div>

    <form id="checkInOutForm">
        <input type="hidden" name="appt_id" value="<?= $appt['id']; ?>">

        <div class="flex justify-between mt-4">
            <button type="button" id="checkIn" class="btn btn-success w-1/2 mr-2">✔ Check-In</button>
            <button type="button" id="checkOut" class="btn btn-danger w-1/2">❌ Check-Out</button>
        </div>
    </form>

    <p id="message" class="text-center text-lg font-semibold mt-4"></p>
    
    <a href="dashboard.php" class="btn btn-secondary w-100 mt-4">⬅ Back to Dashboard</a>
</div>

<script>
document.getElementById('checkIn').addEventListener('click', function() {
    updateCheckInOut('check_in');
});
document.getElementById('checkOut').addEventListener('click', function() {
    updateCheckInOut('check_out');
});

function updateCheckInOut(action) {
    const formData = new FormData(document.getElementById('checkInOutForm'));
    formData.append(action, '1');

    fetch('check_in_out.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('message').innerHTML = data;
    })
    .catch(error => console.error('Error:', error));
}
</script>

</body>
</html>
