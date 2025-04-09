<?php
// Check if running on local environment
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    // Local database credentials
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'leadex');
} else {
    // InfinityFree database credentials
    define('DB_HOST', 'sql109.infinityfree.com');
    define('DB_USER', 'if0_38067690');
    define('DB_PASS', '3MQLdKio8xRZR8q');
    define('DB_NAME', 'if0_38067690_leadex');
}

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", 
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
} catch(PDOException $e) {
    // Log the error details securely
    error_log("Database connection failed: " . $e->getMessage());
    
    // Show a user-friendly message
    die("We're experiencing technical difficulties. Please try again later.");
}

// Function to get database connection
function getConnection() {
    global $conn;
    return $conn;
} 