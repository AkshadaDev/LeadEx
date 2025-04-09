<?php
require_once 'includes/config.php';
requireLogin();

header('Content-Type: application/json');

if ($_SESSION['user_type'] !== 'buyer') {
    echo json_encode(['success' => false, 'message' => 'Only buyers can purchase leads']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $lead_id = $data['lead_id'] ?? null;

    if (!$lead_id) {
        throw new Exception('Lead ID is required');
    }

    // Start transaction
    $conn->beginTransaction();

    // Get lead details
    $stmt = $conn->prepare("SELECT * FROM leads WHERE lead_id = ? AND status = 'available'");
    $stmt->execute([$lead_id]);
    $lead = $stmt->fetch();

    if (!$lead) {
        throw new Exception('Lead not found or already sold');
    }

    // Create transaction record
    $stmt = $conn->prepare("INSERT INTO transactions (lead_id, buyer_id, seller_id, amount) VALUES (?, ?, ?, ?)");
    $stmt->execute([$lead_id, $_SESSION['user_id'], $lead['seller_id'], $lead['price']]);

    // Update lead status
    $stmt = $conn->prepare("UPDATE leads SET status = 'sold' WHERE lead_id = ?");
    $stmt->execute([$lead_id]);

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Lead purchased successfully'
    ]);

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 