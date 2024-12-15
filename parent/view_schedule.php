<?php
include('../db_connect.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "You must be logged in to view this page.";
    exit;
}

// Fetch approved vaccination schedule details from the database
$p_username = $_SESSION['username'];

$sql = "SELECT c.c_name, c.c_vaccine, c.scheduled_date, v.timing, v.status 
        FROM child c
        JOIN vaccine_dates v ON c.c_name = v.c_name AND c.p_username = v.p_username
        WHERE c.p_username = ? AND c.status = 'true'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $p_username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Vaccination Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;
            max-width: 800px;
            overflow-x: auto;
        }
        .fetching-message {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 1.2em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #4a90e2;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .back-button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Vaccination Schedule</h2>
        <div class="fetching-message">Fetching schedule for: <?php echo htmlspecialchars($p_username); ?></div>
        <table>
            <tr>
                <th>Child Name</th>
                <th>Vaccine</th>
                <th>Scheduled Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['c_name']) . "</td>
                            <td>" . htmlspecialchars($row['c_vaccine']) . "</td>
                            <td>" . htmlspecialchars($row['scheduled_date']) . "</td>
                            <td>" . htmlspecialchars($row['timing']) . "</td>
                            <td>" . htmlspecialchars($row['status']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No schedule found</td></tr>";
            }
            $stmt->close();
            $conn->close();
            ?>
        </table>
        <a href="parent_dashboard.php" class="back-button">Back to Parent Dashboard</a>
    </div>
</body>
</html>