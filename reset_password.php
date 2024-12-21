<?php
include 'db_connect.php';

$messages = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $query = "SELECT * FROM users WHERE reset_token=? AND reset_token_expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $update_query = "UPDATE users SET password=?, reset_token=NULL, reset_token_expiry=NULL WHERE reset_token=?";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bind_param("ss", $new_password, $token);
        if ($stmt_update->execute()) {
            $messages[] = "Password reset successfully!";
        } else {
            $messages[] = "Error resetting password.";
        }
    } else {
        $messages[] = "Invalid or expired token.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        
    </style>
</head>
<body>
    <form action="" method="post">
        <h1>Reset Password</h1>
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" required>
        <input type="submit" value="Reset Password">
        <a href="test.php" class="back-link">Back to login</a>
    </form>


    
    <?php foreach ($messages as $message): ?>
        <div class="notification <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>