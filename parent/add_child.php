<?php
include('../db_connect.php');
session_start();

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
    $p_email = $_POST['p_email'];

    $duplicate_check = "SELECT * FROM child WHERE c_name = ? AND c_birth = ? AND c_weight = ?";
    $stmt = $conn->prepare($duplicate_check);
    $stmt->bind_param("ssi", $c_name, $c_birth, $c_weight);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $messages[] = "Cannot insert the record since we found duplicate entry.";
    } else {
        // Insert the child's data with status 'false' (pending approval)
        $insert_query = "INSERT INTO child (c_name, c_gender, c_city, c_birth, c_age, c_weight, c_height, c_vaccine, p_username, p_email)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssiiisss", $c_name, $c_gender, $c_city, $c_birth, $c_age, $c_weight, $c_height, $c_vaccine, $p_username, $p_email);

        if ($stmt->execute()) {
            $messages[] = "Child information submitted for approval.";
        } else {
            $messages[] = "Error submitting child information: " . $stmt->error;
        }
        $stmt->close();
    }
}
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
            margin: 3px;
            padding: 0;
            display: grid;
            justify-content: center;
            align-items: center;
            height: 100vh;
            
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            
        }
        h2 {
            text-align: center;
            margin: 3px 2px 5px 2px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="email"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            
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
        .notification {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: #e8f5e9;
            color: #2e7d32;
            text-align: center;
        }
        .back-button {
            display: block;
            margin-top: 20px;
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        /* Hide increment and decrement icons */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Child</h2>
        <?php
        if (!empty($messages)) {
            foreach ($messages as $message) {
                echo "<div class='notification'>$message</div>";
            }
        }
        ?>
        <form method="post" action="add_child.php">
            <label for="c_name">Name:</label>
            <input type="text" id="c_name" name="c_name" required>

            <label for="c_gender">Gender:</label>
            <select id="c_gender" name="c_gender" required>
                <option value="">Select gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="c_city">City:</label>
            <input type="text" id="c_city" name="c_city" required>

            <!-- <label for="c_birth">Birth Date:</label>
            <input type="date" id="c_birth" name="c_birth" required> -->

            <label for="c_age">Age:</label>
            <input type="number" id="c_age" name="c_age" required>

            <label for="c_weight">Weight in kgs:</label>
            <input type="number" id="c_weight" name="c_weight" required>

            <label for="c_height">Height in cms:</label>
            <input type="number" id="c_height" name="c_height" required>

            <label for="c_vaccine">Vaccine:</label>
            <select id="c_vaccine" name="c_vaccine" required>
                <option value="">Select a vaccine</option>
                <option value="Hepatitis B">Hepatitis B</option>
                <option value="BCG">BCG</option>
                <option value="Polio">Polio</option>
                <option value="DTP">DTP</option>
            </select>

            <label for="p_username">Parent Username:</label>
            <input type="text" id="p_username" name="p_username" required>

            <label for="p_email">Parent Email:</label>
            <input type="email" id="p_email" name="p_email" required>

            <input type="submit" value="Add Child">
        </form>
        <a href="parent_dashboard.php" class="back-button">Back to Parent Dashboard</a>
    </div>
</body>
</html>