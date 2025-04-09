<?php
require_once 'includes/config.php';
requireLogin();

header('Content-Type: application/json');

try {
    $response = ['success' => false];
    
    // Create uploads directory if it doesn't exist
    $uploadDir = 'uploads/profiles/';
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            throw new Exception("Failed to create upload directory");
        }
    }
    
    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            throw new Exception("Invalid file type. Allowed types: " . implode(', ', $allowed));
        }
        
        // Delete old profile image if exists
        $oldImage = getProfileImage($_SESSION['user_id']);
        if ($oldImage && file_exists($oldImage)) {
            unlink($oldImage);
        }
        
        $newFilename = uniqid() . '.' . $ext;
        $destination = $uploadDir . $newFilename;
        
        if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
            throw new Exception("Failed to move uploaded file");
        }
        
        $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
        if (!$stmt->execute([$destination, $_SESSION['user_id']])) {
            throw new Exception("Failed to update database with new image");
        }
        
        $_SESSION['profile_image'] = $destination;
        $response['profileImage'] = $destination;
    }
    
    // Update username if provided
    if (!empty($_POST['username'])) {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
        $stmt->execute([$_POST['username'], $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            throw new Exception("Username already taken");
        }
        
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE user_id = ?");
        if (!$stmt->execute([$_POST['username'], $_SESSION['user_id']])) {
            throw new Exception("Failed to update username");
        }
        
        $_SESSION['username'] = $_POST['username'];
        $response['username'] = $_POST['username'];
    }
    
    $response['success'] = true;
    $response['message'] = 'Profile updated successfully';
    
} catch (Exception $e) {
    error_log("Profile update error: " . $e->getMessage());
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response); 