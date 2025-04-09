<?php
require_once 'includes/config.php';
requireLogin();

// Get current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Leadex</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Base Styles */
        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(17, 19, 24, 0.95);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            overflow: hidden;
            background: rgba(17, 19, 24, 0.95);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .avatar-fallback {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #3a86ff 0%, #00f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 40px rgba(58, 134, 255, 0.2);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .profile-avatar:hover img {
            transform: scale(1.1);
        }

        .hidden {
            display: none;
        }

        .hidden-input {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        .profile-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #fff 0%, #3a86ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .profile-type {
            display: inline-block;
            padding: 0.4rem 1rem;
            background: rgba(58, 134, 255, 0.1);
            border: 1px solid rgba(58, 134, 255, 0.2);
            border-radius: 20px;
            color: #3a86ff;
            font-size: 0.9rem;
        }

        /* Form Styles */
        .profile-form {
            display: grid;
            gap: 1.5rem;
        }

        .form-group {
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #888;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(58, 134, 255, 0.5);
            box-shadow: 0 0 0 2px rgba(58, 134, 255, 0.1);
        }

        textarea.form-input {
            min-height: 120px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3a86ff 0%, #00f2fe 100%);
            color: white;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #888;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background: rgba(0, 200, 83, 0.1);
            border: 1px solid rgba(0, 200, 83, 0.2);
            color: #00c853;
        }

        .alert-error {
            background: rgba(255, 23, 68, 0.1);
            border: 1px solid rgba(255, 23, 68, 0.2);
            color: #ff1744;
        }

        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Stats Section */
        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(58, 134, 255, 0.05);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 600;
            color: #3a86ff;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #888;
            font-size: 0.9rem;
        }

        /* Navigation Styles */
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(17, 19, 24, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nav-brand {
            display: flex;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .logo-circle {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #3a86ff 0%, #00f2fe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .logo:hover .logo-circle {
            transform: scale(1.1);
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-btn {
            position: relative;
            padding: 0.6rem 1.2rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            color: #888;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            overflow: hidden;
        }

        .nav-btn.active {
            background: rgba(58, 134, 255, 0.1);
            border-color: rgba(58, 134, 255, 0.2);
            color: #3a86ff;
        }

        .nav-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            transition: 0.5s;
        }

        .nav-btn:hover {
            background: rgba(58, 134, 255, 0.1);
            border-color: rgba(58, 134, 255, 0.2);
            color: #3a86ff;
            transform: translateY(-2px);
        }

        .nav-btn:hover::before {
            left: 100%;
        }

        .nav-btn svg {
            width: 16px;
            height: 16px;
            stroke-width: 2px;
        }

        /* Adjust main container margin for fixed nav */
        .profile-container {
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <!-- Replace this line: <?php include 'includes/nav.php'; ?> -->
    <!-- With this navigation code: -->
    <nav class="top-nav">
        <div class="nav-brand">
            <a href="dashboard.php" class="logo">
                <div class="logo-circle">L</div>
                Leadex
            </a>
        </div>
        <div class="nav-links">
            <a href="dashboard.php" class="nav-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                </svg>
                Dashboard
            </a>
            <a href="profile.php" class="nav-btn active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Profile
            </a>
            <a href="logout.php" class="nav-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
                Logout
            </a>
        </div>
    </nav>

    <div class="profile-container">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="profile-header">
            <div class="profile-avatar">
                <div class="avatar-fallback">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </div>
            </div>
            <h1 class="profile-title">Profile Settings</h1>
            <span class="profile-type"><?php echo ucfirst($user['user_type']); ?></span>
        </div>

        <?php if ($user['user_type'] === 'seller'): ?>
        <div class="profile-stats">
            <div class="stat-card">
                <div class="stat-value">
                    <?php
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM leads WHERE seller_id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    echo $stmt->fetchColumn();
                    ?>
                </div>
                <div class="stat-label">Total Leads</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?php
                    $stmt = $conn->prepare("
                        SELECT COALESCE(SUM(amount), 0) 
                        FROM transactions t 
                        JOIN leads l ON t.lead_id = l.lead_id 
                        WHERE l.seller_id = ?
                    ");
                    $stmt->execute([$_SESSION['user_id']]);
                    echo '$' . number_format($stmt->fetchColumn(), 2);
                    ?>
                </div>
                <div class="stat-label">Total Sales</div>
            </div>
        </div>
        <?php endif; ?>

        <form class="profile-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" 
                    value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" 
                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-input" 
                    value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Bio</label>
                <textarea name="bio" class="form-input"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html> 