<?php

    require 'config.php';

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Invalid username or password.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
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
            <h1>Login</h1>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p><?= $message ?></p>
            <p><a href="register.php">Don't have an account? Register here.</a></p>
        </div>
    </body>
</html>