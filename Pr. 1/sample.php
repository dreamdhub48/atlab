                                    <!-- REGISTER.PHP PAGE -->
<?php
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'sample_db';
    $message = '';

    try {
        $conn = new PDO("mysql:host=$host", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Create database if not exists
        $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    
        // Switch to the created database
        $conn->exec("USE $dbname");
    
        // Create table if not exists
        $conn->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )");
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("SELECT 1 FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $message = "Email already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
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
                <p><?= htmlspecialchars($message) ?></p>
            <form method="POST">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>
            <p><a href="login.php">Already have an account? Log in here.</a></p>
        </div>
    </body>
</html>


                                    <!-- LOGIN.PHP PAGE -->
<?php
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'sample_db';
    $message = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['name'] = $user['name']; 
            $message = "Login Successful, Redirecting!";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'dashboard.php';
                    }, 2000); // 2 seconds delay
                  </script>";
        } else {
            $message = "Invalid email or password.";
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
            <p><?= htmlspecialchars($message) ?></p>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p><a href="register.php">Don't have an account? Register here.</a></p>
        </div>
    </body>
</html>


                                    <!-- DASHBOARD.PHP PAGE -->
<?php
    session_start();
    if (!isset($_SESSION['name'])) {
        header("Location: login.php");
        exit;
    }
            // Logout logic
    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #121212;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }
            h1 {
                color: #fff;
                margin-bottom: 20px;
            }
            .container {
                text-align: center;
                padding: 30px;
                background-color: #333;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }
            .logout-button {
                display: inline-block;
                padding: 10px 20px;
                color: #fff;
                background-color: #e63946;
                border: none;
                border-radius: 5px;
                text-decoration: none;
                font-size: 16px;
                cursor: pointer;
                margin-top: 20px;
            }
            .logout-button:hover {
                background-color: #d62839;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h1>
            <a href="?logout=true" class="logout-button">Logout</a>
        </div>
    </body>
</html>