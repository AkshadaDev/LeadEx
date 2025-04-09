<?php
require_once 'includes/config.php';
requireLogin();

header('Content-Type: application/json');

try {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    
    // Base query without LIMIT and OFFSET
    $baseQuery = "SELECT l.*, c.category_name, u.username as seller_name 
              FROM leads l 
              JOIN categories c ON l.category_id = c.category_id 
              JOIN users u ON l.seller_id = u.user_id 
              WHERE 1=1";
    $params = [];

    // Add filters using named parameters
    if (!empty($_GET['search'])) {
        $baseQuery .= " AND (l.title LIKE :search OR l.description LIKE :search)";
        $params[':search'] = "%" . $_GET['search'] . "%";
    }

    if (!empty($_GET['category'])) {
        $baseQuery .= " AND l.category_id = :category";
        $params[':category'] = (int)$_GET['category'];
    }

    if (!empty($_GET['min_price'])) {
        $baseQuery .= " AND l.price >= :min_price";
        $params[':min_price'] = (float)$_GET['min_price'];
    }

    if (!empty($_GET['max_price'])) {
        $baseQuery .= " AND l.price <= :max_price";
        $params[':max_price'] = (float)$_GET['max_price'];
    }

    // Get total count using the base query
    $countQuery = str_replace('l.*, c.category_name, u.username as seller_name', 'COUNT(*)', $baseQuery);
    $countStmt = $conn->prepare($countQuery);
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();

    // Add ORDER BY and LIMIT OFFSET to the base query
    $query = $baseQuery . " ORDER BY l.created_at DESC LIMIT :limit OFFSET :offset";
    
    // Prepare and execute the main query with named parameters
    $stmt = $conn->prepare($query);
    
    // Bind all parameters including limit and offset
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    // Bind pagination parameters
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format leads
    $formattedLeads = array_map(function($lead) {
        return [
            'lead_id' => (int)$lead['lead_id'],
            'title' => htmlspecialchars($lead['title']),
            'description' => htmlspecialchars($lead['description'] ?? ''),
            'price' => floatval($lead['price']),
            'category_name' => htmlspecialchars($lead['category_name']),
            'seller_name' => htmlspecialchars($lead['seller_name']),
            'created_at' => $lead['created_at'],
            'status' => $lead['status'] ?? 'available'
        ];
    }, $leads);

    echo json_encode([
        'success' => true,
        'leads' => $formattedLeads,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => ceil($total / $limit),
            'total_leads' => $total
        ]
    ]);

} catch (Exception $e) {
    error_log("Error in get_leads.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    echo json_encode([
        'success' => false,
        'message' => 'Error loading leads: ' . $e->getMessage()
    ]);
} 