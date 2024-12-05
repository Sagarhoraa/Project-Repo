<?php
include('../db_connect.php');
session_start();

// Fetch records from the database
$sql = "SELECT * FROM child";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Child Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 80%;
            max-width: 800px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Child Records</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>City</th>
                <th>Birth Date</th>
                <th>Age</th>
                <th>Weight</th>
                <th>Height</th>
                <th>Vaccine</th>
                <th>Parent Username</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['c_name']) . "</td>
                            <td>" . htmlspecialchars($row['c_gender']) . "</td>
                            <td>" . htmlspecialchars($row['c_city']) . "</td>
                            <td>" . htmlspecialchars($row['c_birth']) . "</td>
                            <td>" . htmlspecialchars($row['c_age']) . "</td>
                            <td>" . htmlspecialchars($row['c_weight']) . "</td>
                            <td>" . htmlspecialchars($row['c_height']) . "</td>
                            <td>" . htmlspecialchars($row['c_vaccine']) . "</td>
                            <td>" . htmlspecialchars($row['p_username']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No records found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
