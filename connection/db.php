<?php
// Database connection configuration
const DB_HOST = '127.0.0.1';
const DB_USER = 'user';
const DB_PASS = 'userpassword';
const DB_NAME = 'my_database';
const DB_PORT = '3306';

// Establish database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
/*
 *
$servername = "127.0.0.1";
$username = "user";
$password = "userpassword";
$dbname = "my_database";

// اتصال به دیتابیس
$conn = new mysqli($servername, $username, $password, $dbname,"3306");

//      environment:
//      MYSQL_ROOT_PASSWORD: rootpassword  # Change this to a secure password
//      MYSQL_DATABASE: my_database        # Replace with your database name
//      MYSQL_USER: user                   # Replace with your username
//      MYSQL_PASSWORD: userpassword       # Replace with your password
//    ports:
//      - "3306:3306"
// بررسی اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 */
