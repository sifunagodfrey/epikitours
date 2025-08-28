<?php
// config/database.php

$host = 'localhost';
$dbname = 'epikitours';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch as associative array
        PDO::ATTR_EMULATE_PREPARES => false, // use real prepared statements
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
