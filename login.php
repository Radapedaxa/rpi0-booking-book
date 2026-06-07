<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username == "" || $password == "") {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user["password"]) {
            // Generate a unique session verification tracker token code
            $unique_token = bin2hex(random_bytes(16));

            // Write the generated tracking code securely to the user record array
            $update = $pdo->prepare("UPDATE users SET session_token = ? WHERE user_id = ?");
            $update->execute([$unique_token, $user["user_id"]]);

            // Synchronize the validation strings directly inside browser memory parameters
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["session_token"] = $unique_token;

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
<?php if ($error != "") { echo "<p style='color:red;'>$error</p>"; } ?>
<form method="POST" action="login.php">
    Username:<br>
    <input type="text" name="username"><br><br>
    Password:<br>
    <input type="password" name="password"><br><br>
    <button type="submit">Login</button>
    <br><br>
    <a href="register.php" style="font-weight: bold; color: blue;">Don't have an account? Register here</a>
</form>
</body>
</html>
