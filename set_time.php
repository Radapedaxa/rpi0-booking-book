<?php
session_start();
// Security Check: Only let your logged-in 'Admin' user access this tool
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'Admin') {
    die("Access Denied: Only the Admin account can manipulate system time.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_date'])) {
    $target_date = $_POST['new_date']; // Format: YYYY-MM-DD
    $target_time = $_POST['new_time'] ?? "12:00:00"; // Default to noon if blank
    
    // Construct the standard Linux date execution command string
    $command = "sudo date -s '" . $target_date . " " . $target_time . "'";
    
    // Execute the command natively on the Pi hardware core
    exec($command, $output, $return_var);
    
    if ($return_var === 0) {
        $message = "✅ Pi System Clock successfully shifted to: " . date('Y-m-d H:i:s');
    } else {
        $message = "❌ Error: Failed to manipulate hardware clock. Check sudo permissions.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pi Time Administration Control</title>
</head>
<body style="font-family: sans-serif; margin: 40px; background-color: #fff3cd;">

<h2>⏰ Raspberry Pi Internal Time Controller</h2>
<p>Current Server Reported Time: <strong><?php echo date('Y-m-d H:i:s (l)'); ?></strong></p>
<hr>

<?php if ($message !== "") { echo "<p style='font-weight:bold;'>$message</p>"; } ?>

<form method="POST" action="set_time.php">
    <label>Set New Target Date:</label><br>
    <input type="date" name="new_date" required><br><br>

    <label>Set Target Time (Optional):</label><br>
    <input type="time" name="new_time" value="12:00"><br><br>

    <button type="submit" style="background-color: #ffc107; padding: 10px; font-weight: bold; border-radius: 4px; cursor: pointer;">
        🚀 Warp Pi System Time
    </button>
</form>

<br><br>
<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
