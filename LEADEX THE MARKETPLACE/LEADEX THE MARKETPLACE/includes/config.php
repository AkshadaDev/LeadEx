<?php
session_start();

require_once 'db.php';

// Define base URL based on environment
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    define('BASE_URL', '/leadex');
} else {
    define('BASE_URL', 'https://leadex.infinityfree.com');
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . BASE_URL . "/login.php");
        exit();
    }
}

function getProfileImage($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT profile_image FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['profile_image'] ?? null;
}
?> 