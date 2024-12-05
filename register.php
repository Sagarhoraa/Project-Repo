<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <h1>Register</h1>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <label for="gender">Gender</label>
        <input type="text" name="gender" id="gender">
        <label for="city">City</label>
        <input type="text" name="city" id="city">
        <label for="birth">Birth Date</label>
        <input type="date" name="birth" id="birth">
        <label for="role">Role</label>
        <select name="role" id="role" required>
            <option value="none" selected disabled hidden>Select an Option</option>
            <option value="admin">Admin</option>
            <option value="parent">Parent</option>
        </select>
        <input type="submit" value="Register">
    </form>

    <?php
    session_start();
    include 'db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $city = $_POST['city'];
        $birth = $_POST['birth'];
        $role = $_POST['role'];

        if (empty($username) || empty($password) || empty($email) || empty($gender) || empty($city) || empty($birth) || empty($role)) {
            echo "All fields are required.";
        } elseif (strlen($password) <= 4) {
            echo "Password must be greater than 4 characters.";
        } else {
            $check_query = "SELECT * FROM users WHERE email = ? OR username = ?";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("ss", $email, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "Email or Username already registered!";
            } else {
                $insert_query = "INSERT INTO users (username, password, email, gender, city, birth, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("sssssss", $username, $password, $email, $gender, $city, $birth, $role);

                if ($stmt->execute()) {
                    echo "Registration successful!";
                    header("Location: test.php");
                    exit();
                } else {
                    echo "Error: Could not register.";
                }
            }
            $stmt->close();
        }
    }
    ?>
</body>
</html>
