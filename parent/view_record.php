<?php
include('../db_connect.php');
session_start();

// // Check if the user is logged in
// if (!isset($_SESSION['username'])) {
//     echo "You must be logged in to view this page.";
//     exit;
// }

// Fetch child details from the database
$p_username = $_SESSION['username'];

$sql = "SELECT * FROM child WHERE p_username = ?";
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
    <title>View Child Record</title>
    <style>
       
        body {
            font-family: 'Poppins', sans-serif;
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
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 90%;
            max-width: 800px;
            overflow-x: auto;
            text-align: center;
        }

        h2 {
            color: #343a40;
            margin-bottom: 20px;
            font-size: 2rem;
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
            background-color:rgb(0, 166, 255);
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        .back-button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .back-button:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Child Records</h2>
        <div class="fetching-message">Fetching records for: <?php echo htmlspecialchars($p_username); ?></div>
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
                <th>Status</th>
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
                            <td>" . htmlspecialchars($row['status']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No records found</td></tr>";
            }
            $stmt->close();
            $conn->close();
            ?>
        </table>
        <a href="parent_dashboard.php" class="back-button">Back to Parent Dashboard</a>
    </div>
</body>
</html>