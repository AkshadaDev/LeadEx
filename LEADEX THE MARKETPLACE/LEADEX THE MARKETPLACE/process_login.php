<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['profile_image'] = $user['profile_image'];
        
        header("Location: " . BASE_URL . "/dashboard.php");
        exit();
    } else {
        header("Location: " . BASE_URL . "/login.php?error=Invalid credentials");
        exit();
    }
}
?> 