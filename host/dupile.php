<?php
session_start();
require '../config/db.php';

$message = "";
$success = false;
$code_from_qr = isset($_GET['code']) ? $_GET['code'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" || !empty($code_from_qr)) {
    $entered_code = !empty($code_from_qr) ? trim($code_from_qr) : trim($_POST['verification_code']);

    // Verify the code from the database
    $stmt = $conn->prepare("SELECT id FROM appointments WHERE verification_code = ?");
    $stmt->bind_param("s", $entered_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $appt = $result->fetch_assoc();
        header("Location: user_details.php?appointment_id=" . $appt['id']); // Redirect to user details
        exit();
    } else {
        $message = "❌ Invalid Code! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
    <h3 class="text-xl font-bold text-center mb-4">🔍 Verify Appointment</h3>

    <?php if ($message): ?>
        <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?> text-center">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" id="verifyForm">
        <input type="text" id="verification_code" name="verification_code" 
               value="<?= htmlspecialchars($code_from_qr) ?>" placeholder="Enter 6-digit Code"
               class="form-control mb-3 text-center text-lg" required>
        <button type="submit" class="btn btn-primary w-100 py-2">Verify</button>
    </form>

    <a href="host_dashboard.php" class="btn btn-secondary mt-3 w-100">⬅ Back to Dashboard</a>
</div>

<script>
    // Auto-submit form if QR code was scanned
    document.addEventListener("DOMContentLoaded", function() {
        var code = "<?= htmlspecialchars($code_from_qr) ?>";
        if (code) {
            document.getElementById("verifyForm").submit();
        }
    });
</script>

</body>
</html>
