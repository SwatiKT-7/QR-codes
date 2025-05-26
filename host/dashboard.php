<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'host') {
    header("Location: ../auth/login.php");
    exit();
}

$host_id = $_SESSION['user_id'];
$host = $conn->query("SELECT * FROM login WHERE id = '$host_id'")->fetch_assoc();
$username = $host['username'];
$host_photo = !empty($host['photo']) ? $host['photo'] : 'default.jpg';

$appointments = $conn->query("
    SELECT a.*, u.username as user_name, u.email, u.phone_no, u.photo as user_photo
    FROM appointments a
    JOIN login u ON a.user_id = u.id
    WHERE a.host_id = '$host_id'
    ORDER BY a.appointment_time DESC
");

$groupedAppointments = [];
while ($appt = $appointments->fetch_assoc()) {
    $groupedAppointments[$appt['user_name']]['user'] = $appt;
    $groupedAppointments[$appt['user_name']]['appointments'][] = $appt;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Host Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">

<div class="max-w-6xl mx-auto p-4">
  <!-- Host Header -->
  <div class="bg-white p-5 rounded-xl shadow flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="flex items-center gap-4">
      <img src="../assets/images/<?= htmlspecialchars($host_photo) ?>" class="w-16 h-16 rounded-full ring-2 ring-blue-500 object-cover" alt="Host Photo">
      <div>
        <h2 class="text-xl font-semibold text-blue-700"><?= htmlspecialchars($username) ?> <span class="text-sm text-gray-500">(Host)</span></h2>
        <p class="text-sm text-gray-500">Manage your appointments below.</p>
      </div>
    </div>
    <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
  </div>

  <!-- Pending Appointments -->
  <h3 class="text-lg font-semibold mt-8 mb-4 text-gray-700">Pending Appointments</h3>
  <div class="grid md:grid-cols-2 gap-4">
    <?php foreach ($groupedAppointments as $group): ?>
      <?php foreach ($group['appointments'] as $appt): ?>
        <?php if ($appt['status'] === 'pending'): ?>
          <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center gap-3">
                <img src="../assets/images/<?= htmlspecialchars($appt['user_photo']) ?>" class="w-12 h-12 rounded-full object-cover" alt="User">
                <div>
                  <h4 class="font-medium"><?= htmlspecialchars($appt['user_name']) ?></h4>
                  <p class="text-xs text-gray-500"><?= date('M d, Y h:i A', strtotime($appt['appointment_time'])) ?></p>
                </div>
              </div>
              <span class="badge bg-warning text-dark">Pending</span>
            </div>
            <form method="POST" action="manage_appointment.php">
              <input type="hidden" name="appointment_id" value="<?= $appt['id'] ?>">
              <input type="hidden" name="user_email" value="<?= $appt['email'] ?>">
              <label class="text-sm mb-1 block">Confirm Time</label>
              <input type="datetime-local" name="confirmed_time" class="form-control mb-2" required>
              <div class="flex gap-2">
                <button type="submit" name="accept" class="btn btn-success w-100">Accept</button>
                <button type="submit" name="reject" class="btn btn-danger w-100">Reject</button>
              </div>
            </form>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </div>

  <!-- Toggle Completed -->
  <div class="text-center my-6">
    <button class="btn btn-primary px-5" onclick="toggleCompleted()">Show Completed Appointments</button>
  </div>

  <!-- Completed Appointments -->
  <div id="completedAppointments" class="hidden">
    <h3 class="text-lg font-semibold mb-4 text-gray-700">Appointments by User</h3>
    <?php foreach ($groupedAppointments as $username => $data): ?>
      <?php
        $completed = array_filter($data['appointments'], fn($a) => $a['status'] !== 'pending');
        if (empty($completed)) continue;
      ?>
      <details class="bg-white rounded-xl shadow p-4 mb-4">
        <summary class="flex items-center gap-3 cursor-pointer">
          <img src="../assets/images/<?= htmlspecialchars($data['user']['user_photo']) ?>" class="w-12 h-12 rounded-full object-cover" alt="User">
          <strong><?= htmlspecialchars($username) ?></strong>
        </summary>
        <div class="mt-3 space-y-3">
          <?php foreach ($completed as $appt): ?>
            <div class="border rounded-lg p-3">
              <div class="flex justify-between items-center mb-1">
                <small class="text-gray-500"><?= date('M d, Y h:i A', strtotime($appt['appointment_time'])) ?></small>
                <span class="badge <?= $appt['status'] === 'accepted' ? 'bg-success' : 'bg-danger' ?>">
                  <?= ucfirst($appt['status']) ?>
                </span>
              </div>
              <?php if ($appt['status'] === 'accepted'): ?>
                <a href="generate_qr.php?appointment_id=<?= $appt['id'] ?>" class="btn btn-outline-info btn-sm w-100 mt-2">Generate QR</a>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </details>
    <?php endforeach; ?>
  </div>

  <!-- Tools -->
  <h3 class="text-lg font-semibold mt-10 mb-3 text-gray-700">Appointment Tools</h3>
  <div class="grid md:grid-cols-3 gap-3">
    <a href="data.php" class="btn btn-outline-primary">Update Check In/Out</a>
    <a href="verify_code.php" class="btn btn-outline-secondary">Verify Code</a>
    <a href="verify_qr.php" class="btn btn-outline-success">Check In/Out via QR</a>
  </div>
</div>

<script>
function toggleCompleted() {
  const section = document.getElementById("completedAppointments");
  const btn = event.target;
  if (section.classList.contains("hidden")) {
    section.classList.remove("hidden");
    btn.textContent = "Hide Completed Appointments";
  } else {
    section.classList.add("hidden");
    btn.textContent = "Show Completed Appointments";
  }
}
</script>

</body>
</html>
