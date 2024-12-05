<?php
include('../db_connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $c_name = $_POST['c_name'];
    $p_username = $_POST['p_username'];
    $birth_date = new DateTime($_POST['c_birth']); // Assuming birth date is provided

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

        $insert_query = "INSERT INTO vaccine_dates (c_name, p_username, name, v_date, timing, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssss", $c_name, $p_username, $vaccine['name'], $v_date->format('Y-m-d'), '09:00:00', $status); // Default time set to 09:00

        if ($stmt->execute()) {
            echo "Vaccine allocated successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
