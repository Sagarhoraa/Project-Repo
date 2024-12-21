<?php
include 'db_connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/Proposal_project/PHPMailer-6.9.3/PHPMailer-6.9.3/src/Exception.php';
require 'C:/xampp/htdocs/Proposal_project/PHPMailer-6.9.3/PHPMailer-6.9.3/src/PHPMailer.php';
require 'C:/xampp/htdocs/Proposal_project/PHPMailer-6.9.3/PHPMailer-6.9.3/src/SMTP.php';

$messages = [];

function sendEmailNotification($email, $subject, $body, &$messages) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shreejanpkota009@gmail.com';
        $mail->Password = 'haohfpwexwnifqfc';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('shreejanpkota009@gmail.com', 'Child Vaccination System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        $messages[] = "Email sent successfully to $email.";
    } catch (Exception $e) {
        $messages[] = "Failed to send email to $email. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $query = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $reset_token = bin2hex(random_bytes(16));
        $reset_query = "UPDATE users SET reset_token=?, reset_token_expiry=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email=?";
        $stmt_reset = $conn->prepare($reset_query);
        $stmt_reset->bind_param("ss", $reset_token, $email);
        $stmt_reset->execute();

        $reset_link = "http://localhost/Proposal_project/reset_password.php?token=$reset_token";
        $subject = "Password Reset Request";
        $body = "Click the following link to reset your password: <a href='$reset_link'>$reset_link</a>";
        sendEmailNotification($email, $subject, $body, $messages);
    } else {
        $messages[] = "No user found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        input[type="email"] {
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
    </style>
</head>
<body>
    <form action="" method="post">
        <h1>Forgot Password</h1>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Send Reset Link">
    </form>
    <?php foreach ($messages as $message): ?>
        <div class="notification <?php echo strpos($message, 'Failed') !== false ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>