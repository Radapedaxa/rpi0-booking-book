<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $booking_date = $_POST['booking_date'];
    $due_date = $_POST['due_date'];

    $today = date('Y-m-d');
    $max_allowed_start = date('Y-m-d', strtotime('+3 days'));

    // Validation Loop: Enforce 3-day boundaries on the server side
    if ($booking_date < $today || $booking_date > $max_allowed_start) {
        die("Error: The selected booking date must be within 3 days from today.");
    }
    if ($due_date < $booking_date) {
        die("Error: The return date cannot be before the start date.");
    }

    try {
        $pdo->beginTransaction();

        // 1. Log the transaction details inside Member 2's bookings table
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, book_id, booking_date, due_date, status) VALUES (?, ?, ?, ?, 'active')");
        $stmt->execute([$user_id, $book_id, $booking_date, $due_date]);

        // 2. Decrement inventory copies from the books storage table
        $update = $pdo->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE book_id = ?");
        $update->execute([$book_id]);

        $pdo->commit();
        header("Location: dashboard.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Transaction failed: " . $e->getMessage());
    }
}
header("Location: dashboard.php");
exit();
