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
<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">

<div class="bg-white p-4 rounded shadow-sm w-100" style="max-width: 420px;">
    <h3 class="text-center mb-3">🔍 Verify Appointment</h3>

    <?php if ($message): ?>
        <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?> text-center">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" id="verifyForm">
        <input type="text" id="verification_code" name="verification_code"
               value="<?= htmlspecialchars($code_from_qr) ?>" placeholder="Enter 6-digit Code"
               class="form-control text-center fs-5 mb-3" maxlength="6" required autofocus>
        <button type="submit" class="btn btn-primary w-100 py-2">Verify</button>
    </form>

    <a href="host_dashboard.php" class="btn btn-secondary mt-3 w-100">⬅ Back to Dashboard</a>

    <!-- Test Simulator (for development) -->
    <div class="text-center text-muted small mt-3">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="simulateScan('ABC123')">
            Simulate Scan
        </button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("verification_code");
    const form = document.getElementById("verifyForm");

    input.focus();

    // Auto-submit on Enter (scanner sends Enter)
    input.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            form.submit();
        }
    });

    // Auto-submit on full 6 characters (some scanners may not send Enter)
    input.addEventListener("input", function () {
        if (input.value.length === 6) {
            form.submit();
        }
    });

    // Reset field after submission
    form.addEventListener("submit", function () {
        setTimeout(() => {
            input.value = "";
            input.focus();
        }, 800);
    });

    // Auto-submit if code was passed via URL
    const codeFromURL = "<?= htmlspecialchars($code_from_qr) ?>";
    if (codeFromURL) {
        form.submit();
    }
});

// Optional: Simulate scan for testing
function simulateScan(code) {
    const input = document.getElementById("verification_code");
    input.value = code;
    input.dispatchEvent(new Event('input')); // Trigger auto-submit
}
</script>

</body>
</html>
