<?php
include('../db_connect.php');
session_start();

function sendEmailNotification($email, $vaccine_name, $v_date, $timing) {
    $subject = "Vaccine Reminder: $vaccine_name";
    $message = "Dear Parent, \n\nThis is a reminder for your child's upcoming vaccine: $vaccine_name scheduled on $v_date at $timing.\n\nBest Regards,\nChild Vaccination System";
    $headers = "From: no-reply@yourdomain.com";

    if (mail($email, $subject, $message, $headers)) {
        echo "Email sent successfully to $email";
    } else {
        echo "Failed to send email to $email";
    }
}

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

    $duplicate_check = "SELECT * FROM child WHERE c_name = ? AND c_birth = ? AND c_weight = ?";
    $stmt = $conn->prepare($duplicate_check);
    $stmt->bind_param("ssi", $c_name, $c_birth, $c_weight);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Cannot insert the record since we found duplicate entry";
    } else {
        $insert_query = "INSERT INTO child (c_name, c_gender, c_city, c_birth, c_age, c_weight, c_height, c_vaccine, p_username)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssiiiss", $c_name, $c_gender, $c_city, $c_birth, $c_age, $c_weight, $c_height, $c_vaccine, $p_username);

        if ($stmt->execute()) {
            echo "New record created successfully";

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

                $insert_vaccine_query = "INSERT INTO vaccine_dates (c_name, p_username, name, v_date, timing, status) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_vaccine = $conn->prepare($insert_vaccine_query);
                $stmt_vaccine->bind_param("ssssss", $c_name, $p_username, $vaccine['name'], $v_date->format('Y-m-d'), '09:00:00', $status); // Default time set to 09:00

                if ($stmt_vaccine->execute()) {
                    // Fetch parent email
                    $parent_query = "SELECT email FROM users WHERE username = ?";
                    $stmt_parent = $conn->prepare($parent_query);
                    $stmt_parent->bind_param("s", $p_username);
                    $stmt_parent->execute();
                    $result_parent = $stmt_parent->get_result();
                    $parent = $result_parent->fetch_assoc();
                    $parent_email = $parent['email'];

                    // Send email notification
                    sendEmailNotification($parent_email, $vaccine['name'], $v_date->format('Y-m-d'), '09:00:00');
                } else {
                    echo "Error: " . $stmt_vaccine->error;
                }
                $stmt_vaccine->close();
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $stmt->close();
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
        input[type="number"] {
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
            translate: background-color 0.35s ease-in-out, transform 0.35s ease-in-out;
        }
        input[type="submit"]:hover {
            background-color: #218838;
            transform: scale(1.04);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Child</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="c_name">Name:</label>
            <input type="text" id="c_name" name="c_name" required><br>
            
            <label for="c_gender">Gender:</label>
            <input type="text" id="c_gender" name="c_gender"><br>
            
            <label for="c_city">City:</label>
            <input type="text" id="c_city" name="c_city"><br>
            
            <label for="c_birth">Birth Date:</label>
            <input type="date" id="c_birth" name="c_birth"><br>
            
            <label for="c_age">Age:</label>
            <input type="number" id="c_age" name="c_age"><br>
            
            <label for="c_weight">Weight:</label>
            <input type="number" id="c_weight" name="c_weight"><br>
            
            <label for="c_height">Height:</label>
            <input type="number" id="c_height" name="c_height"><br>
            
            <label for="c_vaccine">Vaccine:</label>
            <input type="text" id="c_vaccine" name="c_vaccine"><br>
            
            <label for="p_username">Parent Username:</label>
            <input type="text" id="p_username" name="p_username"><br>
            
            <input type="submit" value="Add Child">
        </form>
    </div>
</body>
</html>
