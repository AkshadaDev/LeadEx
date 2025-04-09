<?php
require_once 'includes/config.php';
requireLogin();

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if (!isset($_FILES['profile_picture'])) {
        throw new Exception('No file uploaded');
    }

    $file = $_FILES['profile_picture'];
    
    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Upload failed');
    }

    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        throw new Exception('Invalid file type');
    }

    // Validate file size (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('File too large');
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('profile_') . '.' . $extension;
    $upload_path = 'uploads/' . $filename;

    // Create uploads directory if it doesn't exist
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new Exception('Failed to save file');
    }

    // Delete old profile picture if exists
    $stmt = $conn->prepare("SELECT profile_image FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $old_picture = $stmt->fetchColumn();

    if ($old_picture && file_exists('uploads/' . $old_picture)) {
        unlink('uploads/' . $old_picture);
    }

    // Update database
    $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
    $stmt->execute([$filename, $_SESSION['user_id']]);

    echo json_encode([
        'success' => true,
        'filename' => $filename
    ]);

} catch (Exception $e) {
    error_log('Upload error: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'upload_dir_exists' => file_exists(__DIR__ . '/uploads/'),
            'upload_dir_writable' => is_writable(__DIR__ . '/uploads/'),
            'php_error' => error_get_last()
        ]
    ]);
} 