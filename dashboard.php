<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// CONCURRENT SESSION LOCK CHECKUP
// Fetch the live tracking code from the database row record
$session_check = $pdo->prepare("SELECT session_token FROM users WHERE user_id = ?");
$session_check->execute([$_SESSION['user_id']]);
$db_user = $session_check->fetch();

// If tokens do not match, destroy session immediately and boot user out
if (!$db_user || $db_user['session_token'] !== $_SESSION['session_token']) {
    session_destroy();
    header("Location: login.php?error=logged_in_elsewhere");
    exit();
}

// PROCESS ADMIN ACTIONS (ADDING BOOKS AND UPDATING STOCK COUNT VALUES)
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['username'] === 'Admin') {
    if (isset($_POST['add_book'])) {
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $copies = intval($_POST['copies']);
        
        $add_stmt = $pdo->prepare("INSERT INTO books (title, author, available_copies) VALUES (?, ?, ?)");
        $add_stmt->execute([$title, $author, $copies]);
    } 
    elseif (isset($_POST['update_stock'])) {
        $book_id = intval($_POST['book_id']);
        $new_copies = intval($_POST['new_copies']);
        
        $update_stmt = $pdo->prepare("UPDATE books SET available_copies = ? WHERE book_id = ?");
        $update_stmt->execute([$new_copies, $book_id]);
    }
    header("Location: dashboard.php");
    exit();
}

// FETCH LIVE INFORMATION
$today_check = date('Y-m-d');
$warn_stmt = $pdo->prepare("SELECT b.title FROM bookings bk JOIN books b ON bk.book_id = b.book_id WHERE bk.user_id = ? AND bk.due_date < ? AND bk.status = 'active'");
$warn_stmt->execute([$_SESSION['user_id'], $today_check]);
$overdue_items = $warn_stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - Library System</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #f9f9f9; }
        table { border-collapse: collapse; width: 100%; max-width: 800px; background: white; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .nav-links a { text-decoration: none; font-weight: bold; margin-right: 20px; }
        .warning-box { background-color: #ffcccc; color: #cc0000; padding: 15px; border: 2px solid #cc0000; margin-bottom: 20px; font-weight: bold; }
        .admin-panel { background-color: #e2f0d9; padding: 20px; margin-top: 40px; border: 1px solid #70ad47; max-width: 800px; }
    </style>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
<a href="logout.php" style="color: red; font-weight: bold; text-decoration: none;">Logout</a>

<br><br>
<div class="nav-links">
    <a href="my_bookings.php" style="color: green;">📋 View My Borrowed Books</a>
    <a href="about.php" style="color: blue;">👥 About Our Team</a>
</div>

<hr style="margin-top: 20px; margin-bottom: 20px;">

<?php
if (!empty($overdue_items)) {
    echo "<div class='warning-box'>⚠️ SYSTEM WARNING: You have overdue items!<ul>";
    foreach ($overdue_items as $item) { echo "<li>" . htmlspecialchars($item['title']) . "</li>"; }
    echo "</ul></div>";
}
?>

<h3>Available Books Catalogue</h3>
<table>
    <tr><th>Title</th><th>Author</th><th>Available Copies</th><th>Action</th></tr>
    <?php foreach ($books as $row): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['title']); ?></td>
        <td><?php echo htmlspecialchars($row['author']); ?></td>
        <td><?php echo htmlspecialchars($row['available_copies']); ?></td>
        <td>
            <?php if ($row['available_copies'] > 0): ?>
                <a href="book.php?book_id=<?php echo $row['book_id']; ?>" style="color: #007BFF; font-weight: bold; text-decoration: none;">Book Now</a>
            <?php else: ?>
                <span style="color: #777; font-style: italic;">Out of Stock</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- RENDER ADMINISTRATIVE FUNCTION CONTROL FORM LINKS EXCLUSIVELY FOR ADMIN PROFILE -->
<?php if ($_SESSION['username'] === 'Admin'): ?>
<div class="admin-panel">
    <h3>⚙️ Admin Management Console</h3>
    <hr>
    <h4>➕ Add New Book Entry</h4>
    <form method="POST" action="dashboard.php">
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="number" name="copies" placeholder="Stock Count" min="1" required>
        <button type="submit" name="add_book">Add Book</button>
    </form>
    <br>
    <h4>✏️ Adjust Existing Stock Volume</h4>
    <form method="POST" action="dashboard.php">
        <select name="book_id" required>
            <option value="">-- Select Target Book --</option>
            <?php foreach ($books as $row): ?>
                <option value="<?php echo $row['book_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="new_copies" placeholder="New Stock Quantity" min="0" required>
        <button type="submit" name="update_stock">Update Stock</button>
    </form>
</div>
<?php endif; ?>

</body>
</html>
