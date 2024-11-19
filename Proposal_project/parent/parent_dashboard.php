<?php
include('../db_connect.php');
;
session_start();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent dashboard</title>
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
            max-width: 600px;
        }

        .container div {
            background-color: #007bff;
            color: #ffffff;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .container div:hover {
            background-color: #0056b3;
        }

        #add {
            background-color: #28a745;
        }

        #add:hover {
            background-color: #218838;
        }

        #view-report {
            background-color: #ffc107;
            color: #000000;
        }

        #view-report:hover {
            background-color: #e0a800;
        }

        #schedule {
            background-color: #dc3545;
        }

        #schedule:hover {
            background-color: #c82333;
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div id="add">Enter child details</div>
        <div id="view-report">View your child report</div>
        <div id="schedule">View your schedule</div>
    </div>
<script>
    document.getElementById("add").addEventListener("click",()=>{
        window.location.href = "add_child.php"
    
    })
    document.getElementById("view-report").addEventListener("click",()=>{
        window.location.href = "view_record.php"
    })
    document.getElementById("schedule").addEventListener("click",()=>{
        window.location.href ="view_schedule.php"
    })
    </script>
</body>
</html>