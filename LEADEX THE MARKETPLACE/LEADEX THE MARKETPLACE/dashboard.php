<?php
require_once 'includes/config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Leadex</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dashboard-container {
            background: linear-gradient(135deg, #0f1117 0%, #1a1d24 100%);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: rgba(17, 19, 24, 0.95);
            padding: 2rem;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .user-info {
            text-align: center;
            margin-bottom: 3rem;
        }

        .user-avatar {
            width: 90px;
            height: 90px;
            background: linear-gradient(-45deg, #3a86ff, #00f2fe, #00b4d8, #0077b6);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: white;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .welcome-text {
            color: white;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .user-type {
            display: inline-block;
            background: rgba(58, 134, 255, 0.15);
            color: #3a86ff;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 2rem;
            animation: fadeInUp 0.5s ease;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .dashboard-header h1 {
            background: linear-gradient(135deg, #fff 0%, #888 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.2rem;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        .action-btn {
            position: relative;
            overflow: hidden;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn svg {
            width: 20px;
            height: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3a86ff 0%, #00f2fe 100%);
            box-shadow: 0 4px 15px rgba(58, 134, 255, 0.2);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(17, 19, 24, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 2rem;
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease forwards;
            backdrop-filter: blur(10px);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(58, 134, 255, 0.3);
            box-shadow: 0 8px 32px rgba(58, 134, 255, 0.1);
        }

        .stat-title {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            background: linear-gradient(135deg, #3a86ff, #00f2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1;
        }

        /* Dashboard Overview Section */
        .overview-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .overview-card {
            background: rgba(17, 19, 24, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease forwards;
        }

        .overview-card:hover {
            transform: translateY(-5px);
            border-color: rgba(58, 134, 255, 0.2);
            background: rgba(30, 32, 37, 0.95);
        }

        .overview-card h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .overview-value {
            color: #3a86ff;
            font-size: 2rem;
            font-weight: 600;
        }

        /* Refined Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Refined Sidebar Styles */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
            color: #888;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            stroke-width: 2px;
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(58, 134, 255, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);
            color: #3a86ff;
        }

        .nav-item.active svg {
            color: #3a86ff;
        }

        /* Animation Delays for Cards */
        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .overview-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .overview-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        /* Enhanced Overview Cards */
        .overview-card {
            background: rgba(17, 19, 24, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease forwards;
        }

        .overview-card:hover {
            transform: translateY(-5px);
            border-color: rgba(58, 134, 255, 0.2);
            background: rgba(30, 32, 37, 0.95);
        }

        .overview-card h3 svg {
            width: 20px;
            height: 20px;
            stroke-width: 2px;
            transition: all 0.3s ease;
        }

        .overview-card:hover h3 svg {
            transform: scale(1.1);
            color: #3a86ff;
        }

        .buyer-actions {
            display: flex;
            gap: 1rem;
        }

        .action-btn {
            position: relative;
            overflow: hidden;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: white;
        }

        .action-btn svg {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }

        .action-btn:hover svg {
            transform: scale(1.1);
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3a86ff 0%, #00f2fe 100%);
            box-shadow: 0 4px 15px rgba(58, 134, 255, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(58, 134, 255, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.15);
        }

        .seller-actions {
            display: flex;
            gap: 1rem;
        }

        /* Update the action buttons container */
        .overview-actions {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <div class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
                <div class="user-type"><?php echo ucfirst($_SESSION['user_type']); ?></div>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    </svg>
                    Dashboard
                </a>
                <a href="profile.php" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Profile
                </a>
                <a href="logout.php" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                    Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="dashboard-header">
                <h1>Dashboard Overview</h1>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">Available Leads</div>
                    <div class="stat-value">
                        <?php
                        $stmt = $conn->query("SELECT COUNT(*) FROM leads WHERE status = 'available'");
                        echo $stmt->fetchColumn();
                        ?>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">My Purchases</div>
                    <div class="stat-value">
                        <?php
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM transactions WHERE buyer_id = ?");
                        $stmt->execute([$_SESSION['user_id']]);
                        echo $stmt->fetchColumn();
                        ?>
                    </div>
                </div>
            </div>

            <!-- Overview Section -->
            <div class="overview-section">
                <div class="overview-card">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Active Leads
                    </h3>
                    <div class="overview-value">0</div>
                </div>

                <div class="overview-card">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                            <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4-4-4z"/>
                        </svg>
                        Messages
                    </h3>
                    <div class="overview-value">0</div>
                </div>

                <div class="overview-card">
                    <h3>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Notifications
                    </h3>
                    <div class="overview-value">0</div>
                </div>
            </div>

            <div class="overview-actions">
                <?php if ($_SESSION['user_type'] === 'seller'): ?>
                    <!-- Enhanced Post Lead button for sellers -->
                    <div class="seller-actions">
                        <a href="post_lead_page.php" class="action-btn btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                                <path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Post New Lead
                        </a>
                        <a href="my_leads.php" class="action-btn btn-secondary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v14z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            My Leads
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Buyer actions (unchanged) -->
                    <div class="buyer-actions">
                        <a href="browse_leads.php" class="action-btn btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Browse Leads
                        </a>
                        <a href="my_purchases.php" class="action-btn btn-secondary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                                <path d="M9 14l6-6M9 8h6v6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            My Purchases
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="overview-grid">
                <?php if ($_SESSION['user_type'] === 'seller'): ?>
                    <!-- Seller Overview Cards -->
                    <div class="overview-card">
                        <h3>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>
                            </svg>
                            Total Leads
                        </h3>
                        <div class="overview-value">
                            <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM leads WHERE seller_id = ?");
                            $stmt->execute([$_SESSION['user_id']]);
                            echo $stmt->fetchColumn();
                            ?>
                        </div>
                    </div>

                    <div class="overview-card">
                        <h3>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Total Sales
                        </h3>
                        <div class="overview-value">
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
                    </div>
                <?php else: ?>
                    <!-- Buyer Overview Cards -->
                    <div class="overview-card">
                        <h3>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                                <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Purchases
                        </h3>
                        <div class="overview-value">
                            <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM transactions WHERE buyer_id = ?");
                            $stmt->execute([$_SESSION['user_id']]);
                            echo $stmt->fetchColumn();
                            ?>
                        </div>
                    </div>

                    <div class="overview-card">
                        <h3>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="24" height="24">
                                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Total Spent
                        </h3>
                        <div class="overview-value">
                            <?php
                            $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE buyer_id = ?");
                            $stmt->execute([$_SESSION['user_id']]);
                            echo '$' . number_format($stmt->fetchColumn(), 2);
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>