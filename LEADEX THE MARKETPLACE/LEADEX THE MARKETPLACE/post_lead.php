<?php
require_once 'includes/config.php';
requireLogin();

// Ensure only sellers can post leads
if ($_SESSION['user_type'] !== 'seller') {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate inputs
        $title = trim($_POST['title'] ?? '');
        $category_id = trim($_POST['category_id'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);

        if (empty($title)) {
            throw new Exception('Title is required');
        }

        if (empty($category_id)) {
            throw new Exception('Category is required');
        }

        if (empty($description)) {
            throw new Exception('Description is required');
        }

        if ($price <= 0) {
            throw new Exception('Price must be greater than 0');
        }

        // Insert the lead
        $stmt = $conn->prepare("
            INSERT INTO leads (seller_id, category_id, title, description, price, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $_SESSION['user_id'],
            $category_id,
            $title,
            $description,
            $price
        ]);

        $_SESSION['success_message'] = 'Lead posted successfully!';
        header("Location: dashboard.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: post_lead_page.php");
        exit();
    }
} else {
    header("Location: post_lead_page.php");
    exit();
} 