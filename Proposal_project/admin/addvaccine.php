<?php
include('../db_connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vaccine_type = $_POST['vaccine_type'];
    $time = $_POST['time'];

    $insert_query = "INSERT INTO vaccine (vaccine_type, time) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ss", $vaccine_type, $time);

    if ($stmt->execute()) {
        echo "New vaccine record created successfully";
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Add Vaccine</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #ffffff;
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
        input[type="text"] {
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Vaccine</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="vaccine_type">Vaccine Type:</label>
            <input type="text" id="vaccine_type" name="vaccine_type" required><br>

            <label for="time">Time:</label>
            <input type="text" id="time" name="time" required><br>

            <input type="submit" value="Add Vaccine">
        </form>
    </div>
</body>
</html>
