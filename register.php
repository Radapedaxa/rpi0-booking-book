<?php
session_start();
require_once "db.php"; // Connect to your working database configuration

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email    = trim($_POST["email"]);

    if ($username == "" || $password == "" || $email == "") {
        $error = "Please fill in all fields.";
    } else {
        try {
            // Check if username or email already exists to prevent database crash
            $check = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $check->execute([$username, $email]);
            
            if ($check->fetch()) {
                $error = "Username or Email already exists.";
            } else {
                // Insert the new user matching Member 2's structure
                // Saving password as plain text to match Member 3's plain text comparison logic
                $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
                $stmt->execute([$username, $password, $email]);
                
                $success = "Registration successful! You can now log in.";
            }
        } catch (\PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Register New Student Account</h2>

<?php
if ($error != "") { echo "<p style='color:red;'>$error</p>"; }
if ($success != "") { echo "<p style='color:green;'>$success</p>"; }
?>

<form method="POST" action="register.php">
    Username:<br>
    <input type="text" name="username" required><br><br>

    Email Address:<br>
    <input type="email" name="email" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Register Account</button>
</form>

<br>
<a href="login.php">Back to Login</a>

</body>
</html>
