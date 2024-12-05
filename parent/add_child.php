<?php
include('../db_connect.php');
session_start();

// Include PHPMailer files and namespaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Proposal_project\PHPMailer-6.9.3\PHPMailer-6.9.3\src/Exception.php'; 
require 'C:\xampp\htdocs\Proposal_project\PHPMailer-6.9.3\PHPMailer-6.9.3\src/PHPMailer.php';
require 'C:\xampp\htdocs\Proposal_project\PHPMailer-6.9.3\PHPMailer-6.9.3\src/SMTP.php';

function sendEmailNotification($email, $vaccine_name, $v_date, $timing, &$messages) {
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

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Vaccine Reminder: $vaccine_name";
        $mail->Body = "Dear Parent, <br><br>This is a reminder for your child's upcoming vaccine: $vaccine_name scheduled on $v_date at $timing.<br><br>Best Regards,<br>Child Vaccination System";

        $mail->send();
        $messages[] = "Email sent successfully to $email";
    } catch (Exception $e) {
        $messages[] = "Failed to send email to $email. Mailer Error: {$mail->ErrorInfo}";
    }
}

$messages = []; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $c_name = $_POST['c_name'];
    $c_gender = $_POST['c_gender'];
    $c_city = $_POST['c_city'];
    $c_birth = $_POST['c_birth'];
    $c_age = $_POST['c_age'];
    $c_weight = $_POST['c_weight'];
    $c_height = $_POST['c_height'];
    $c_vaccine = $_POST['c_vaccine'];
    $p_username = $_POST['p_username'];
    $p_email = $_POST['p_email']; // New email field

    $duplicate_check = "SELECT * FROM child WHERE c_name = ? AND c_birth = ? AND c_weight = ?";
    $stmt = $conn->prepare($duplicate_check);
    $stmt->bind_param("ssi", $c_name, $c_birth, $c_weight);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $messages[] = "Cannot insert the record since we found duplicate entry";
    } else {
        $insert_query = "INSERT INTO child (c_name, c_gender, c_city, c_birth, c_age, c_weight, c_height, c_vaccine, p_username)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssiiiss", $c_name, $c_gender, $c_city, $c_birth, $c_age, $c_weight, $c_height, $c_vaccine, $p_username);

        if ($stmt->execute()) {
            $messages[] = "New record created successfully";

            // Generate vaccine schedule
            $birth_date = new DateTime($c_birth);
            $vaccine_schedule = [
                ['name' => 'Hepatitis B', 'interval' => '0 days'],
                ['name' => 'BCG', 'interval' => '0 days'],
                ['name' => 'Polio', 'interval' => '2 months'],
                ['name' => 'DTP', 'interval' => '4 months'],
                // Add more vaccines as needed
            ];

            foreach ($vaccine_schedule as $vaccine) {
                $v_date = clone $birth_date;
                $v_date->modify($vaccine['interval']);
                $status = 'false';
                $v_name = $vaccine['name'];
                $formatted_v_date = $v_date->format('Y-m-d');
                $timing = '09:00:00';
                
                $insert_vaccine_query = "INSERT INTO vaccine_dates (c_name, p_username, name, v_date, timing, status) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_vaccine = $conn->prepare($insert_vaccine_query);
                $stmt_vaccine->bind_param("ssssss", $c_name, $p_username, $v_name, $formatted_v_date, $timing, $status);

                if ($stmt_vaccine->execute()) {
                    // Send email notification using the provided email
                    sendEmailNotification($p_email, $v_name, $formatted_v_date, $timing, $messages);
                } else {
                    $messages[] = "Error inserting vaccine date: " . $stmt_vaccine->error;
                }
                $stmt_vaccine->close(); // Close each statement after use
            }
        } else {
            $messages[] = "Error inserting child record: " . $stmt->error;
        }
        $stmt->close(); // Ensure this is only called once
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Child</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: grid;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="email"] { /* Added email input style */
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.35s ease-in-out, transform 0.35s ease-in-out;
        }
        input[type="submit"]:hover {
            background-color: #218838;
            transform: scale(1.04);
        }
        .back-button {
            display: block;
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .notification {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: #e8f5e9;
            color: #2e7d32;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Child</h2>
        <form method="post" action="add_child.php">
            <label for="c_name">Name:</label>
            <input type="text" id="c_name" name="c_name" required><br>
            
            <label for="c_gender">Gender:</label>
            <input type="text" id="c_gender" name="c_gender" required><br>
            
            <label for="c_city">City:</label>
            <input type="text" id="c_city" name="c_city" required><br>
            
            <label for="c_birth">Birth Date:</label>
            <input type="date" id="c_birth" name="c_birth" required><br>
            
            <label for="c_age">Age:</label>
            <input type="number" id="c_age" name="c_age" required><br>
            
            <label for="c_weight">Weight in kgs:</label>
            <input type="number" id="c_weight" name="c_weight" required><br>
            
            <label for="c_height">Height in cms:</label>
            <input type="number" id="c_height" name="c_height" required><br>
            
            <label for="c_vaccine">Vaccine:</label>
            <select id="c_vaccine" name="c_vaccine" required>
                <option value="">Select a vaccine</option>
                <option value="Hepatitis B">Hepatitis B</option>
                <option value="BCG">BCG</option>
                <option value="Polio">Polio</option>
                <option value="DTP">DTP</option>
                <!-- Add more options as needed -->
            </select><br>
            
            <label for="p_username">Parent Username:</label>
            <input type="text" id="p_username" name="p_username" required><br>
            
            <label for="p_email">Parent Email:</label> <!-- New email field -->
            <input type="email" id="p_email" name="p_email" required><br>
            
            <input type="submit" value="Add Child">
        </form>
        <a href="parent_dashboard.php" class="back-button">Back to Dashboard</a>
        <?php if (!empty($messages)): ?>
            <div class="notification">
                <?php echo implode('<br>', array_unique($messages)); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>