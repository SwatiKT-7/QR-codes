<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appt_id = intval($_POST['appt_id']);

    $stmt = $conn->prepare("SELECT check_in, check_out FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $appt_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appt = $result->fetch_assoc();

    if (isset($_POST['check_in']) && empty($appt['check_in'])) {
        $stmt = $conn->prepare("UPDATE appointments SET check_in = NOW() WHERE id = ?");
        $stmt->bind_param("i", $appt_id);
        $stmt->execute();
        echo "<div class='alert alert-success'>User Checked In Successfully!</div>";
    } elseif (isset($_POST['check_out']) && empty($appt['check_out'])) {
        $stmt = $conn->prepare("UPDATE appointments SET check_out = NOW() WHERE id = ?");
        $stmt->bind_param("i", $appt_id);
        $stmt->execute();
        echo "<div class='alert alert-danger'>User Checked Out Successfully!</div>";
    } else {
        echo "<div class='alert alert-warning'>Action already performed!</div>";
    }
}
?>
