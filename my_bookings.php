<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
    SELECT b.booking_id,
           bk.title,
           bk.author,
           b.booking_date,
           b.due_date
    FROM bookings b
    JOIN books bk
    ON b.book_id = bk.book_id
    WHERE b.user_id = ?
    AND b.status = 'active'
");

$stmt->execute([$user_id]);

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
</head>
<body>

<h2>My Active Bookings</h2>

<a href="dashboard.php">Back to Dashboard</a>
|
<a href="logout.php">Logout</a>

<br><br>

<table border="1">

<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Booking Date</th>
    <th>Due Date</th>
    <th>Action</th>
</tr>

<?php foreach ($bookings as $booking): ?>

<tr>

    <td><?php echo $booking["title"]; ?></td>

    <td><?php echo $booking["author"]; ?></td>

    <td><?php echo $booking["booking_date"]; ?></td>

    <td><?php echo $booking["due_date"]; ?></td>

    <td>

        <form method="POST" action="return.php">

            <input type="hidden"
                   name="booking_id"
                   value="<?php echo $booking["booking_id"]; ?>">

            <button type="submit">Return</button>

        </form>

    </td>

</tr>

<?php endforeach; ?>

</table>

</body>
</html>
