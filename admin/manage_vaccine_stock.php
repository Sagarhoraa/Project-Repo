<?php
include('../db_connect.php');
session_start();

// Check if the user is logged in as admin
// if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
//     echo "You must be logged in as an admin to view this page.";
//     exit;
// }

$messages = [];

// Fetch available vaccines for the dropdown
$vaccine_query = "SELECT vaccine_name FROM vaccine_stock";
$vaccine_result = $conn->query($vaccine_query);
$vaccine_names = [];
if ($vaccine_result->num_rows > 0) {
    while ($row = $vaccine_result->fetch_assoc()) {
        $vaccine_names[] = $row['vaccine_name'];
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vaccine_name = $_POST['vaccine_name'];
    $quantity = $_POST['quantity'];

    // Check if the vaccine already exists
    $check_query = "SELECT * FROM vaccine_stock WHERE vaccine_name = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("s", $vaccine_name);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Update existing vaccine stock
        $update_query = "UPDATE vaccine_stock SET quantity = quantity + ? WHERE vaccine_name = ?";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bind_param("is", $quantity, $vaccine_name);

        if ($stmt_update->execute()) {
            $messages[] = "Stock updated successfully for $vaccine_name.";
        } else {
            $messages[] = "Error updating stock: " . $stmt_update->error;
        }
    } else {
        $messages[] = "Vaccine not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vaccine Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 20px auto;
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        select, input[type="number"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
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
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Vaccine Stock</h2>
        
        <?php foreach ($messages as $message): ?>
            <div class="notification <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endforeach; ?>

        <form method="POST">
            <select name="vaccine_name" required>
                <option value="" disabled selected>Select Vaccine</option>
                <?php foreach ($vaccine_names as $vaccine_name): ?>
                    <option value="<?php echo $vaccine_name; ?>"><?php echo $vaccine_name; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <button type="submit">Add/Update Stock</button>
        </form>

    
        <a href="admin_dashboard.php" class="back-link">Back to Admin Panel</a>
    </div>
</body>
</html>