<?php
require_once 'includes/config.php';
requireLogin();

if ($_SESSION['user_type'] !== 'buyer') {
    http_response_code(403);
    exit('Unauthorized');
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $lead_id = $data['lead_id'] ?? 0;

    // Verify purchase
    $stmt = $conn->prepare("
        SELECT t.*, l.*, c.category_name, u.username as seller_name,
               u.email as seller_email, u.phone as seller_phone
        FROM transactions t
        JOIN leads l ON t.lead_id = l.lead_id
        JOIN categories c ON l.category_id = c.category_id
        JOIN users u ON l.seller_id = u.user_id
        WHERE t.lead_id = ? AND t.buyer_id = ?
    ");
    
    $stmt->execute([$lead_id, $_SESSION['user_id']]);
    $lead = $stmt->fetch();

    if (!$lead) {
        throw new Exception('Lead not found or not purchased');
    }

    // Generate CSV content
    $csvData = [
        ['Lead Details'],
        ['Title', $lead['title']],
        ['Category', $lead['category_name']],
        ['Description', $lead['description']],
        ['Price', '$' . number_format($lead['amount'], 2)],
        [''],
        ['Seller Information'],
        ['Name', $lead['seller_name']],
        ['Email', $lead['seller_email']],
        ['Phone', $lead['seller_phone']],
        [''],
        ['Transaction Details'],
        ['Transaction ID', $lead['transaction_id']],
        ['Purchase Date', date('Y-m-d H:i:s', strtotime($lead['created_at']))],
    ];

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="lead-' . $lead_id . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Output CSV
    $output = fopen('php://output', 'w');
    foreach ($csvData as $row) {
        fputcsv($output, $row);
    }
    fclose($output);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} 