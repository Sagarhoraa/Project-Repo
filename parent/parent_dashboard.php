<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
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
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 90%;
            max-width: 800px;
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
            color: #343a40;
            font-size: 2.5rem;
        }

        h2 {
            margin-bottom: 20px;
            color: #007bff;
            font-size: 1.5rem;
        }

        p.quote {
            font-style: italic;
            color: #555;
            margin-bottom: 30px;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .box {
            width: 200px;
            height: 100px;
            margin: 10px;
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
            background-color: #e0f3ff;
            border-color: #0056b3;
            transform: translateY(-10px);
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
            background-color: #007bff;
            color: #ffffff;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, Parent!</h1>
        <h2>Your Child's Health, Our Priority</h2>
        <p class="quote">"The greatest wealth is health." - Virgil</p>
        <div class="box-container">
            <div class="box" id="add">Enter Child Details</div>
            <div class="box" id="view-report">View Child Report</div>
            <div class="box" id="schedule">View Schedule</div>
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