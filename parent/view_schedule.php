<?php
include('../db_connect.php');
session_start();

// Fetch vaccine schedules from the database
$sql = "SELECT * FROM vaccine_dates";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Vaccine Schedule</title>
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
        <h2>Vaccine Schedule</h2>
        <table>
            <tr>
                <th>Child Name</th>
                <th>Parent Username</th>
                <th>Vaccine Name</th>
                <th>Date</th>
                <th>Timing</th>
                <th>Status</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['c_name']) . "</td>
                            <td>" . htmlspecialchars($row['p_username']) . "</td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['v_date']) . "</td>
                            <td>" . htmlspecialchars($row['timing']) . "</td>
                            <td>" . htmlspecialchars($row['status']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No schedules found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
