<?php
session_start();
include('../db_connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .button-container a {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .button-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="button-container">
            <!-- <a href="add_child.php">Add Child</a> -->
            <a href="view_records.php">View Records</a>
            <a href="addvaccine.php">Add Vaccine</a>
            <a href="view_schedule.php">View Schedule</a>
            <a href="allocate_vaccine.php">Allocate Vaccine</a>
        </div>
    </div>
</body>
</html>
