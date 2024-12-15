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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        .container h1 {
            margin-bottom: 30px;
            color: #343a40;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .button-container a {
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .button-container a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="button-container">
            <a href="view_records.php">View Records</a>
            <a href="view_schedule.php">View Schedule</a>
            <a href="approve_vaccines.php">Approve Vaccines</a>
        </div>
    </div>
</body>
</html>