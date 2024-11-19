<?php
session_start();
include 'db_connect.php';
$error_message = '';

if (isset($_POST['Login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION["user-id"] = $user["id"];
            $_SESSION["role"] = $user["role"];
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] == 'parent') {
                header("Location: parent_dashboard.php");
            }
        } else {
            $error_message = "Invalid password";
        }
    } else {
        $error_message = "User not found";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form action="" method="post">
        <h1>Login form</h1>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
        <input type="submit" value="Login" id="Login" name="Login">
        <a href="register.php">Register</a>
    </form>
    <script src="script.js"></script>
</body>
</html>
