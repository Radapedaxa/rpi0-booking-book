<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$book_id = $_GET['book_id'] ?? null;
if (!$book_id) {
    header("Location: dashboard.php");
    exit();
}

// Fetch book details to display to the student
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if (!$book || $book['available_copies'] <= 0) {
    header("Location: dashboard.php");
    exit();
}

// Calculate the 3-day operational boundaries
$today = date('Y-m-d');
$max_booking_date = date('Y-m-d', strtotime('+3 days'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Confirm Booking Details</title>
</head>
<body>

<h2>Schedule Booking for: <?php echo htmlspecialchars($book['title']); ?></h2>
<p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
<hr>

<form method="POST" action="book_process.php">
    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">

    <!-- Restrict user input bounds completely via standard HTML calendar range properties -->
    <label>Select Booking Date (Max 3 days from today):</label><br>
    <input type="date" name="booking_date" min="<?php echo $today; ?>" max="<?php echo $max_booking_date; ?>" value="<?php echo $today; ?>" required><br><br>

    <label>Select Expected Return Date:</label><br>
    <input type="date" name="due_date" min="<?php echo $today; ?>" required><br><br>

    <button type="submit">Confirm Booking</button>
    <a href="dashboard.php" style="margin-left: 15px;">Cancel</a>
</form>

</body>
</html>
