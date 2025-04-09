<?php
require_once 'includes/config.php';
requireLogin();

header('Content-Type: application/json');

if ($_SESSION['user_type'] !== 'seller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['lead_id'])) {
        throw new Exception('Lead ID is required');
    }

    // Verify lead ownership
    $stmt = $conn->prepare("
        SELECT seller_id, status 
        FROM leads 
        WHERE lead_id = ?
    ");
    $stmt->execute([$data['lead_id']]);
    $lead = $stmt->fetch();

    if (!$lead) {
        throw new Exception('Lead not found');
    }

    if ($lead['seller_id'] != $_SESSION['user_id']) {
        throw new Exception('Unauthorized');
    }

    if ($lead['status'] === 'sold') {
        throw new Exception('Cannot delete sold leads');
    }

    // Delete the lead
    $stmt = $conn->prepare("DELETE FROM leads WHERE lead_id = ?");
    $stmt->execute([$data['lead_id']]);

    echo json_encode([
        'success' => true,
        'message' => 'Lead deleted successfully'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 