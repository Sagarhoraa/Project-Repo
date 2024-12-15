<?php
include('../db_connect.php');
session_start();

// Check if the user is logged in as admin
// if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
//     echo "You must be logged in as an admin to view this page.";
//     exit;
// }

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Proposal_project\PHPMailer-6.9.3\PHPMailer-6.9.3\src/Exception.php';
require 'C:\xampp\htdocs\Proposal_project\PHPMailer-6.9.3\PHPMailer-6.9.3\src/PHPMailer.php';
require 'C:\xampp\htdocs\Proposal_project\PHPMailer-6.9.3\PHPMailer-6.9.3\src/SMTP.php';

$messages = [];

// Function to send email
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $child_id = $_POST['child_id'];
    $action = $_POST['action'];

    $query = "SELECT * FROM child WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $child_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $child = $result->fetch_assoc();
        $email = $child['p_email'];
        $c_name = $child['c_name'];
        $c_vaccine = $child['c_vaccine'];

        if ($action === 'approve') {
            $status = 'true';
            $schedule_days = 0;

            // Determine the schedule based on the vaccine type
            switch ($c_vaccine) {
                case 'Hepatitis B':
                    $schedule_days = 0; // Immediate scheduling
                    break;
                case 'BCG':
                    $schedule_days = 7;
                    break;
                case 'Polio':
                    $schedule_days = 9;
                    break;
                case 'DTP':
                    $schedule_days = 15;
                    break;
            }

            $scheduled_date = date('Y-m-d', strtotime("+$schedule_days days"));
            $update_query = "UPDATE child SET status = ?, scheduled_date = ? WHERE id = ?";
            $stmt_update = $conn->prepare($update_query);
            $stmt_update->bind_param("ssi", $status, $scheduled_date, $child_id);

            if ($stmt_update->execute()) {
                $subject = "Vaccination Request Approved for $c_name";
                $body = "Your vaccination request for $c_name has been approved and is scheduled on $scheduled_date.";
                sendEmailNotification($email, $subject, $body, $messages);
            } else {
                $messages[] = "Error updating request: " . $stmt_update->error;
            }
        } elseif ($action === 'reject') {
            $status = 'false';

            $update_query = "UPDATE child SET status = ? WHERE id = ?";
            $stmt_update = $conn->prepare($update_query);
            $stmt_update->bind_param("si", $status, $child_id);

            if ($stmt_update->execute()) {
                $subject = "Vaccination Request Rejected for $c_name";
                $body = "We regret to inform you that your vaccination request for $c_name has been rejected.";
                sendEmailNotification($email, $subject, $body, $messages);
            } else {
                $messages[] = "Error updating request: " . $stmt_update->error;
            }
        } elseif ($action === 'delete') {
            // Delete the record
            $delete_query = "DELETE FROM child WHERE id = ?";
            $stmt_delete = $conn->prepare($delete_query);
            $stmt_delete->bind_param("i", $child_id);

            if ($stmt_delete->execute()) {
                $subject = "Vaccination Request Deleted for $c_name";
                $body = "Your vaccination request for $c_name has been deleted.";
                sendEmailNotification($email, $subject, $body, $messages);
            } else {
                $messages[] = "Error deleting request: " . $stmt_delete->error;
            }
        }
    } else {
        $messages[] = "No request found with ID: $child_id";
    }
}

// Fetch pending requests
$query = "SELECT * FROM child WHERE status = 'false'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval - Vaccine Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 20px auto;
            max-width: 900px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: #fff;
        }
        button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
        }
        .approve {
            background-color: #28a745;
        }
        .reject {
            background-color: #dc3545;
        }
        .delete {
            background-color: #ff6347;
        }
        .notification {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pending Vaccine Requests</h2>
        <?php foreach ($messages as $message): ?>
            <div class="notification <?php echo strpos($message, 'Failed') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endforeach; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Child Name</th>
                    <th>Vaccine</th>
                    <th>Parent Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['c_name']; ?></td>
                        <td><?php echo $row['c_vaccine']; ?></td>
                        <td><?php echo $row['p_email']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="child_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="approve">Approve</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="child_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="reject">Reject</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="child_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>