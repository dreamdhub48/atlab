<?php
    require 'config.php';

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact_number = $_POST['contact_number'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);

        if ($stmt->rowCount() > 0) {
            $message = "Email or Username already exists!";
        } else {
            $stmt = $conn->prepare("
                INSERT INTO users (name, email, contact_number, username, password) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $email, $contact_number, $username, $password]);
            $message = "Registration successful! You can now log in.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <style>
            body {
                background-color: #121212;
                color: #fff;
                font-family: Arial, sans-serif;
                text-align: center;
            }
            .container {
                max-width: 400px;
                margin: 50px auto;
                padding: 20px;
                background-color: #1e1e1e;
                border-radius: 8px;
            }
            input {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #555;
                border-radius: 4px;
                background-color: #333;
                color: #fff;
            }
            button {
                width: 100%;
                padding: 10px;
                background-color: red;
                color: #fff;
                border: none;
                border-radius: 4px;
                font-size: 16px;
                cursor: pointer;
            }
            button:hover {
                background-color: darkred;
            }
            a {
                color: red;
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Register</h1>
            <form method="POST">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="contact_number" placeholder="Contact Number" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>
            <p><?= $message ?></p>
            <p><a href="login.php">Already have an account? Log in here.</a></p>
        </div>
    </body>
</html>