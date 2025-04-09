<?php
require_once 'includes/config.php';
requireLogin();

header('Content-Type: application/json');

if ($_SESSION['user_type'] !== 'seller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT l.*, c.category_name
        FROM leads l
        JOIN categories c ON l.category_id = c.category_id
        WHERE l.seller_id = ?
        ORDER BY l.created_at DESC
    ");

    $stmt->execute([$_SESSION['user_id']]);
    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'leads' => $leads
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading leads: ' . $e->getMessage()
    ]);
} 