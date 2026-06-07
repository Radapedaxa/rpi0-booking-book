<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $booking_id = $_POST["booking_id"];

    $stmt = $pdo->prepare("
        SELECT book_id FROM bookings WHERE booking_id = ?
    ");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking) {

        $book_id = $booking["book_id"];

        $stmt2 = $pdo->prepare("
            UPDATE bookings
            SET return_date = CURDATE(),
                status = 'returned'
            WHERE booking_id = ?
        ");
        $stmt2->execute([$booking_id]);

        $stmt3 = $pdo->prepare("
            UPDATE books
            SET available_copies = available_copies + 1
            WHERE book_id = ?
        ");
        $stmt3->execute([$book_id]);
    }

    header("Location: dashboard.php");
    exit();
}

?>
