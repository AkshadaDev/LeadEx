<?php
require_once 'includes/config.php';
requireLogin();

header('Content-Type: application/json');

try {
    $stats = [];
    
    if ($_SESSION['user_type'] === 'seller') {
        // Get active leads count
        $stmt = $conn->prepare("SELECT COUNT(*) FROM leads WHERE seller_id = ? AND status = 'available'");
        $stmt->execute([$_SESSION['user_id']]);
        $stats['activeLeads'] = $stmt->fetchColumn();
        
        // Get total sales
        $stmt = $conn->prepare("SELECT COUNT(*) FROM transactions WHERE seller_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $stats['totalSales'] = $stmt->fetchColumn();
    } else {
        // Get available leads count
        $stmt = $conn->prepare("SELECT COUNT(*) FROM leads WHERE status = 'available'");
        $stmt->execute();
        $stats['availableLeads'] = $stmt->fetchColumn();
        
        // Get purchases count
        $stmt = $conn->prepare("SELECT COUNT(*) FROM transactions WHERE buyer_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $stats['purchases'] = $stmt->fetchColumn();
    }
    
    echo json_encode([
        'success' => true,
        'stats' => $stats
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 