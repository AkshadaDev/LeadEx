<?php
require_once 'includes/config.php';
requireLogin();

// Ensure only sellers can access this
if ($_SESSION['user_type'] !== 'seller') {
    header("Location: dashboard.php");
    exit();
}

header('Content-Type: application/json');

try {
    if (!isset($_FILES['bulk_file'])) {
        throw new Exception('No file uploaded');
    }

    $file = $_FILES['bulk_file'];
    
    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Upload failed');
    }

    // Validate file type
    $mimeType = mime_content_type($file['tmp_name']);
    if ($mimeType !== 'text/csv' && $mimeType !== 'text/plain') {
        throw new Exception('Invalid file type. Please upload a CSV file.');
    }

    // Read CSV file
    $handle = fopen($file['tmp_name'], 'r');
    if (!$handle) {
        throw new Exception('Failed to read file');
    }

    // Skip header row
    $header = fgetcsv($handle);
    if (!$header) {
        throw new Exception('Empty file');
    }

    // Expected headers
    $expectedHeaders = ['title', 'category_id', 'description', 'price'];
    if (array_diff($expectedHeaders, array_map('strtolower', $header))) {
        throw new Exception('Invalid CSV format. Please use the provided template.');
    }

    $conn->beginTransaction();

    $stmt = $conn->prepare("
        INSERT INTO leads (seller_id, category_id, title, description, price, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    $successCount = 0;
    $errors = [];

    while (($row = fgetcsv($handle)) !== false) {
        try {
            if (count($row) !== 4) {
                throw new Exception('Invalid row format');
            }

            [$title, $category_id, $description, $price] = $row;

            // Validate data
            if (empty($title) || empty($category_id) || empty($description) || !is_numeric($price)) {
                throw new Exception('Invalid data format');
            }

            $stmt->execute([
                $_SESSION['user_id'],
                $category_id,
                $title,
                $description,
                floatval($price)
            ]);

            $successCount++;
        } catch (Exception $e) {
            $errors[] = "Row error: " . $e->getMessage();
        }
    }

    fclose($handle);

    if ($successCount > 0) {
        $conn->commit();
        echo json_encode([
            'success' => true,
            'message' => "Successfully imported $successCount leads",
            'errors' => $errors
        ]);
    } else {
        $conn->rollBack();
        throw new Exception('No leads were imported. Please check your CSV file.');
    }

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 