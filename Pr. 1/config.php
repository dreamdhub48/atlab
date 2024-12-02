<?php
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'login_register_db';

    try {
        $conn = new PDO("mysql:host=$host", $username, password: $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create database if not exists
        $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");
        $conn->exec("USE $dbname");

        // Create table if not exists
        $conn->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                contact_number VARCHAR(15),
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
            )
        ");
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>