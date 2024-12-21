<?php
include('../db_connect.php');
session_start();

$delete_message = ''; // Initialize message variable

// Check if a delete request has been made
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM child WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        $delete_message = "Record deleted successfully";
    } else {
        $delete_message = "Error deleting record: " . $conn->error;
    }
}

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
            font-family:  sans-serif;
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
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 90%;
            max-width: 800px;
            text-align: center;
        }
        h2 {
            margin-bottom: 30px;
            color: #007bff;
            font-size: 2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #e9ecef;
            color: #007bff;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .error {
            background-color: #ffebee;
            color: #c62828;
        }
        .dashboard-button {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 30px;
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
        }
        
        .delete-button {
            color: #ffffff;
            background-color: #dc3545;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }
        .delete-button:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }
        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }
        a:hover {
            color: #0056b3;
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
                <th>Action</th>
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
                            <td><a href='?delete_id=" . $row['id'] . "' class='delete-button' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No records found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
        <?php if (!empty($delete_message)): ?>
            <div class="message <?php echo strpos($delete_message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $delete_message; ?>
            </div>
        <?php endif; ?>
        <a href="admin_dashboard.php" class="dashboard-button">Back to Admin Dashboard</a>
    </div>
</body>
</html>