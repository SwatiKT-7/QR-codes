<?php
session_start();
require '../config/db.php';

$host_id = $_SESSION['user_id'] ?? 0;

// Default to today if no date selected
$selected_date = $_GET['date'] ?? date('Y-m-d');
$start = $selected_date . " 00:00:00";
$end = $selected_date . " 23:59:59";

// Fetch appointments joined with username (not name)
$sql = "SELECT a.*, u.username AS user_name 
        FROM appointments a 
        JOIN login u ON a.user_id = u.id
        WHERE a.host_id = ? 
        AND a.appointment_time BETWEEN ? AND ?
        ORDER BY a.appointment_time ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $host_id, $start, $end);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Host Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Dashboard Card -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <!-- Header with date selector -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <h1 class="text-2xl font-bold text-blue-700">Appointments for <?= date("F j, Y", strtotime($selected_date)) ?></h1>
                <form method="GET" class="flex items-center mt-4 md:mt-0">
                    <label for="date" class="mr-2 font-medium text-gray-700">Select Date:</label>
                    <input type="date" name="date" id="date" value="<?= htmlspecialchars($selected_date) ?>" class="border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <button type="submit" class="ml-3 px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">View</button>
                </form>
            </div>

            <!-- Appointments Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-300 rounded-md">
                    <thead class="bg-blue-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Visitor</th>
                            <th class="px-4 py-3 font-semibold">Time</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Check-In</th>
                            <th class="px-4 py-3 font-semibold">Check-Out</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($row['user_name']) ?></td>
                                    <td class="px-4 py-3"><?= date("h:i A", strtotime($row['appointment_time'])) ?></td>
                                    <td class="px-4 py-3">
                                        <?php if ($row['check_out']): ?>
                                            <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Checked Out</span>
                                        <?php elseif ($row['check_in']): ?>
                                            <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Checked In</span>
                                        <?php else: ?>
                                            <span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">Not Arrived</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3"><?= $row['check_in'] ? date("h:i A", strtotime($row['check_in'])) : '—' ?></td>
                                    <td class="px-4 py-3"><?= $row['check_out'] ? date("h:i A", strtotime($row['check_out'])) : '—' ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center px-4 py-6 text-gray-500">No appointments found for this date.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
