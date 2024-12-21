<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Child Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f4f8;
        }

        .box {
            width: 200px;
            height: 100px;
            margin: 20px;
            border: 2px solid #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
            background-color: #ffffff;
            border-radius: 15px;
            
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .box:hover {
         
            transform: translateY(-3px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .logout-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ffffff;
            color: #007bff;
            border: 2px solid #007bff;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .logout-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
            
            
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 500px;
        }

        .box:nth-child(2) {
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="box-container">
            <div class="box" id="add">Enter child details</div>
            <div class="box" id="view-report">View your child report</div>
            <div class="box" id="schedule">View your schedule</div>
        </div>
        <form method="post" action="../logout.php">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
    <script>
        document.getElementById("add").addEventListener("click", () => {
            window.location.href = "add_child.php";
        });
        document.getElementById("view-report").addEventListener("click", () => {
            window.location.href = "view_record.php";
        });
        document.getElementById("schedule").addEventListener("click", () => {
            window.location.href = "view_schedule.php";
        });
    </script>
</body>
</html>