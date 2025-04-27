<?php

require_once '/var/www/config/config.php'; 

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $GLOBALS['pdo'] = $pdo;
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    var_dump("Database connection failed: " . $e->getMessage());
    exit;
}
