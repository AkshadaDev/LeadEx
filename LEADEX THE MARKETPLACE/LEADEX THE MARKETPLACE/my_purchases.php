<?php
require_once 'includes/config.php';
requireLogin();

if ($_SESSION['user_type'] !== 'buyer') {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Purchases - Leadex</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f1117 0%, #1a1d24 100%);
            color: #fff;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* Enhanced Navigation */
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

        /* Main Content Adjustments */
        .main-content {
            margin-top: 80px;
            padding: 2rem;
        }

        /* Enhanced Purchase Cards */
        .purchases-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .purchase-card {
            position: relative;
            background: rgba(17, 19, 24, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .purchase-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            padding: 1px;
            background: linear-gradient(135deg, #3a86ff, transparent);
            -webkit-mask: 
                linear-gradient(#fff 0 0) content-box, 
                linear-gradient(#fff 0 0);
            mask: 
                linear-gradient(#fff 0 0) content-box, 
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .purchase-card:hover {
            transform: translateY(-5px);
        }

        .purchase-card:hover::after {
            opacity: 1;
        }

        /* Enhanced Page Header */
        .page-header {
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .page-title {
            font-size: 2.2rem;
            background: linear-gradient(135deg, #fff 0%, #3a86ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: titleGlow 2s ease-in-out infinite;
        }

        @keyframes titleGlow {
            0%, 100% { filter: drop-shadow(0 0 2px rgba(58, 134, 255, 0.2)); }
            50% { filter: drop-shadow(0 0 8px rgba(58, 134, 255, 0.4)); }
        }

        /* Enhanced Purchase Cards */
        .purchase-card {
            background: linear-gradient(145deg, rgba(17, 19, 24, 0.95), rgba(30, 32, 37, 0.95));
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: cardFloat 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .purchase-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 10px 40px rgba(0, 0, 0, 0.2),
                0 0 20px rgba(58, 134, 255, 0.1),
                inset 0 0 0 1px rgba(58, 134, 255, 0.2);
        }

        @keyframes cardFloat {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced Card Header */
        .purchase-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .lead-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #fff;
            margin: 0;
            transition: color 0.3s ease;
        }

        .purchase-card:hover .lead-title {
            background: linear-gradient(135deg, #fff, #3a86ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .purchase-date {
            font-size: 0.9rem;
            color: #888;
            padding: 0.4rem 0.8rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 6px;
            backdrop-filter: blur(5px);
        }

        /* Enhanced Details Section */
        .purchase-details {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            backdrop-filter: blur(10px);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row:hover {
            background: rgba(255, 255, 255, 0.02);
            transform: translateX(5px);
            padding-left: 10px;
            border-radius: 6px;
        }

        /* Enhanced Download Button */
        .download-btn {
            position: relative;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(135deg, rgba(58, 134, 255, 0.1), rgba(0, 242, 254, 0.1));
            border: 1px solid rgba(58, 134, 255, 0.2);
            border-radius: 12px;
            color: #3a86ff;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            overflow: hidden;
        }

        .download-btn::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(58, 134, 255, 0.2),
                transparent
            );
            transform: translateX(-100%) rotate(45deg);
            transition: transform 0.6s ease;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, rgba(58, 134, 255, 0.15), rgba(0, 242, 254, 0.15));
            border-color: rgba(58, 134, 255, 0.4);
            box-shadow: 0 5px 15px rgba(58, 134, 255, 0.1);
        }

        .download-btn:hover::before {
            transform: translateX(100%) rotate(45deg);
        }

        .download-btn svg {
            transition: transform 0.3s ease;
        }

        .download-btn:hover svg {
            transform: translateY(2px);
        }

        .download-btn.loading {
            opacity: 0.7;
            cursor: wait;
        }

        .download-btn.loading svg {
            animation: downloadSpin 1s linear infinite;
        }

        @keyframes downloadSpin {
            to { transform: rotate(360deg); }
        }

        /* Enhanced Seller Info */
        .seller-info {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .seller-info:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }

        .seller-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3a86ff, #00f2fe);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
        }

        .seller-info:hover .seller-avatar {
            transform: scale(1.1);
        }

        /* Enhanced Purchase Amount */
        .purchase-amount {
            font-size: 1.6rem;
            font-weight: 600;
            color: #3a86ff;
            text-shadow: 0 0 10px rgba(58, 134, 255, 0.2);
            transition: all 0.3s ease;
        }

        .purchase-card:hover .purchase-amount {
            transform: scale(1.05);
            color: #00f2fe;
        }

        /* Add staggered animation for cards */
        .purchases-grid {
            perspective: 1000px;
        }

        .purchase-card:nth-child(1) { animation-delay: 0.1s; }
        .purchase-card:nth-child(2) { animation-delay: 0.2s; }
        .purchase-card:nth-child(3) { animation-delay: 0.3s; }
        .purchase-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Single Navigation Bar -->
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
                <a href="profile.php" class="nav-btn">
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

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">My Purchases</h1>
            </div>

            <div class="purchases-grid">
                <?php
                $stmt = $conn->prepare("
                    SELECT 
                        t.transaction_id,
                        t.lead_id,
                        t.amount,
                        t.purchase_date,
                        t.status,
                        l.title,
                        l.description,
                        c.category_name,
                        u.username as seller_name
                    FROM transactions t
                    JOIN leads l ON t.lead_id = l.lead_id
                    JOIN categories c ON l.category_id = c.category_id
                    JOIN users u ON t.seller_id = u.user_id
                    WHERE t.buyer_id = ?
                    ORDER BY t.purchase_date DESC
                ");
                
                $stmt->execute([$_SESSION['user_id']]);
                $purchases = $stmt->fetchAll();

                if (empty($purchases)) {
                    echo '<div class="empty-state">
                            <h3>No purchases yet</h3>
                            <p>Start exploring our available leads</p>
                            <a href="browse_leads.php" class="browse-btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Browse Leads
                            </a>
                          </div>';
                } else {
                    foreach ($purchases as $purchase) {
                        $purchaseDate = new DateTime($purchase['purchase_date']);
                        ?>
                        <div class="purchase-card">
                            <div class="purchase-header">
                                <h3 class="lead-title"><?php echo htmlspecialchars($purchase['title']); ?></h3>
                                <span class="purchase-date"><?php echo $purchaseDate->format('M d, Y'); ?></span>
                            </div>

                            <div class="purchase-details">
                                <div class="detail-row">
                                    <span>Category</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($purchase['category_name']); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span>Transaction ID</span>
                                    <span class="detail-value"><?php echo $purchase['transaction_id']; ?></span>
                                </div>
                            </div>

                            <div class="purchase-footer">
                                <div class="seller-info">
                                    <div class="seller-avatar">
                                        <?php echo strtoupper(substr($purchase['seller_name'], 0, 1)); ?>
                                    </div>
                                    <span>Sold by <?php echo htmlspecialchars($purchase['seller_name']); ?></span>
                                </div>
                                <div class="actions">
                                    <div class="purchase-amount">
                                        $<?php echo number_format($purchase['amount'], 2); ?>
                                    </div>
                                    <button onclick="downloadLead(<?php echo $purchase['lead_id']; ?>)" class="download-btn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18">
                                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" 
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Download Lead
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </main>
    </div>

    <script>
    async function downloadLead(leadId) {
        const button = event.currentTarget;
        button.classList.add('loading');
        
        try {
            const response = await fetch('download_lead.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ lead_id: leadId })
            });

            if (!response.ok) {
                throw new Error('Download failed');
            }

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `lead-${leadId}.csv`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            a.remove();

        } catch (error) {
            alert('Error downloading lead: ' + error.message);
        } finally {
            button.classList.remove('loading');
        }
    }

    // Add intersection observer for card animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.purchase-card').forEach(card => {
        observer.observe(card);
    });
    </script>
</body>
</html> 