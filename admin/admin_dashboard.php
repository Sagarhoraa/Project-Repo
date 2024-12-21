<?php
session_start();
include('../db_connect.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <style>
      body {
        font-family:  sans-serif;
        background: linear-gradient(135deg, #e9ecef, #f8f9fa);
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }

      .container {
        background-color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 90%;
        max-width: 450px;
      }

      .container h1 {
        margin-bottom: 30px;
        color: #343a40;
        font-size: 2rem;
      }

      .button-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
      }

      .button-container a {
        background-color: #007bff;
        color: #ffffff;
        padding: 15px 30px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 1.1rem;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.3s ease;
      }

      .button-container a:hover {
        background-color: #0056b3;
        transform: translateY(-3px);
      }

      .logout {
        background-color: #dc3545;
        margin-top: 25px;
        padding: 12px;
        width: fit-content;
        border-radius: 8px;
      }

      .logout a {
        text-decoration: none;
        color: #fff;
        font-size: 1rem;
        font-weight: bold;
        transition: transform 0.3s ease;
      }

      .logout:hover {
        transform: translateY(-3px);
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
        <a href="manage_vaccine_stock.php">Manage Vaccine Stock</a>
      </div>
      <div class="logout">
        <a href="../logout.php">Logout</a>
      </div>
    </div>
  </body>
</html>