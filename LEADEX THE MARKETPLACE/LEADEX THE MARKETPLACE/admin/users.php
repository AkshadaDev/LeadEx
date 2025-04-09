<?php
require_once '../includes/config.php';

// Basic admin authentication (you should improve this in production)
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== 'admin' || $_SERVER['PHP_AUTH_PW'] !== 'adminpass') {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Please enter valid credentials';
    exit;
}

// Fetch all users
try {
    $stmt = $conn->query("SELECT user_id, username, email, user_type, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Leadex Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container {
            padding: 2rem;
            margin-top: 60px;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
        }
        .users-table th,
        .users-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .users-table th {
            background: #111;
            font-weight: 600;
        }
        .users-table tr:hover {
            background: #222;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <div class="logo">
                <h1>Leadex Admin</h1>
            </div>
        </div>
    </nav>

    <div class="admin-container">
        <div class="container">
            <h2>Registered Users</h2>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['user_type']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html> 