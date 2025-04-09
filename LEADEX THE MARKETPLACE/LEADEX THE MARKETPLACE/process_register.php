<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];
    
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        die("Please fill all required fields");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    try {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            die("Username or email already exists");
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, $user_type]);
        
        // Redirect to login page
        header("Location: login.php?registered=1");
        exit();
        
    } catch(PDOException $e) {
        die("Registration failed: " . $e->getMessage());
    }
}
?> 