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
    <title>Browse Leads - Leadex</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Modern Navigation Styles */
        .nav-container {
            background: rgba(17, 19, 24, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Logo Styles */
        .nav-logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: opacity 0.3s ease;
        }

        .nav-logo:hover {
            opacity: 0.9;
        }

        .logo-circle {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3a86ff 0%, #00f2fe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Navigation Links */
        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            color: #888;
            text-decoration: none;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #3a86ff 0%, #00f2fe 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
            border-radius: 10px;
        }

        .nav-link:hover {
            color: white;
        }

        .nav-link:hover::before {
            opacity: 0.1;
        }

        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, #3a86ff 0%, #00f2fe 100%);
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s ease;
        }

        .nav-link:hover svg {
            transform: scale(1.1);
        }

        /* User Menu */
        .user-menu {
            position: relative;
            margin-left: 1rem;
        }

        .user-button {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1.2rem;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .user-button:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateY(-1px);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #3a86ff 0%, #00f2fe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            font-size: 1rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .user-name {
            font-weight: 500;
            font-size: 0.95rem;
        }

        .user-role {
            color: #888;
            font-size: 0.8rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        .action-btn {
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3a86ff 0%, #00f2fe 100%);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .browse-container {
            padding: 2rem;
        }

        .filters-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .search-bar {
            margin-bottom: 1rem;
        }

        .search-input {
            width: 100%;
            padding: 0.8rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
        }

        .filter-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            align-items: center;
        }

        .filter-select {
            padding: 0.8rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: white;
            width: 100%;
        }

        .leads-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .lead-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .lead-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .lead-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .lead-title {
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .lead-seller {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .seller-avatar {
            width: 24px;
            height: 24px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
        }

        .lead-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .lead-category {
            background: rgba(58, 134, 255, 0.1);
            color: var(--primary-color);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .lead-price {
            font-weight: 600;
            color: #00f2fe;
            font-size: 1.2rem;
        }

        .buy-btn {
            width: 100%;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .buy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(58, 134, 255, 0.4);
        }

        .buy-btn svg {
            width: 18px;
            height: 18px;
        }

        .lead-date {
            color: #888;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .loading, .no-leads, .error {
            text-align: center;
            padding: 2rem;
            color: #888;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <!-- Updated Navigation HTML -->
    <div class="nav-container">
        <div class="nav-content">
            <a href="dashboard.php" class="nav-logo">
                <div class="logo-circle">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <span>Leadex</span>
            </a>
            
            <div class="nav-links">
                <a href="dashboard.php" class="nav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a>
                <a href="browse_leads.php" class="nav-link active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Browse Leads
                </a>
                <a href="my_purchases.php" class="nav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 14l6-6M9 8h6v6" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    My Purchases
                </a>

                <div class="user-menu">
                    <button class="user-button">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                        <div class="user-info">
                            <span class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <span class="user-role"><?php echo ucfirst($_SESSION['user_type']); ?></span>
                        </div>
                    </button>
                </div>

                <a href="logout.php" class="nav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <main class="main-content">
            <div class="browse-container">
                <div class="filters-section">
                    <div class="search-bar">
                        <input type="text" id="searchLeads" class="search-input" placeholder="Search leads...">
                    </div>
                    
                    <div class="filter-group">
                        <select id="categoryFilter" class="filter-select">
                            <option value="">All Categories</option>
                            <?php
                            $stmt = $conn->query("SELECT * FROM categories ORDER BY category_name");
                            while ($category = $stmt->fetch()) {
                                echo '<option value="' . $category['category_id'] . '">' . 
                                     htmlspecialchars($category['category_name']) . '</option>';
                            }
                            ?>
                        </select>
                        
                        <input type="number" id="minPrice" class="filter-select" placeholder="Min Price">
                        <input type="number" id="maxPrice" class="filter-select" placeholder="Max Price">
                        
                        <button id="applyFilters" class="buy-btn">Apply Filters</button>
                    </div>
                </div>

                <div id="leadsGrid" class="leads-grid">
                    <!-- Leads will be loaded here -->
                </div>
            </div>
        </main>
    </div>

    <script>
        async function loadLeads(page = 1) {
            const leadsGrid = document.getElementById('leadsGrid');
            const params = new URLSearchParams({
                search: document.getElementById('searchLeads')?.value || '',
                category: document.getElementById('categoryFilter')?.value || '',
                min_price: document.getElementById('minPrice')?.value || '',
                max_price: document.getElementById('maxPrice')?.value || '',
                page: page
            });

            try {
                leadsGrid.innerHTML = '<div class="loading">Loading leads...</div>';
                
                // Debug: Log the request
                console.log('Fetching leads with params:', params.toString());
                
                const response = await fetch(`get_leads.php?${params.toString()}`);
                const data = await response.json();
                
                // Debug: Log the response
                console.log('Server response:', data);

                if (!data.success) {
                    throw new Error(data.message || 'Error loading leads');
                }

                if (!Array.isArray(data.leads)) {
                    throw new Error('Invalid leads data received');
                }

                if (data.leads.length === 0) {
                    leadsGrid.innerHTML = '<div class="no-leads">No leads found</div>';
                    return;
                }

                leadsGrid.innerHTML = data.leads.map(lead => `
                    <div class="lead-card">
                        <div class="lead-header">
                            <div>
                                <div class="lead-title">${lead.title || 'Untitled Lead'}</div>
                                <div class="lead-seller">
                                    <div class="seller-avatar">${(lead.seller_name || 'U').charAt(0).toUpperCase()}</div>
                                    <span>${lead.seller_name || 'Unknown Seller'}</span>
                                </div>
                                ${lead.description ? `
                                    <div class="lead-description">${lead.description}</div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <div class="lead-meta">
                            <span class="lead-category">${lead.category_name || 'Uncategorized'}</span>
                            <div class="lead-price">$${parseFloat(lead.price || 0).toFixed(2)}</div>
                        </div>
                        
                        <div class="lead-footer">
                            <div class="lead-date">Posted ${formatDate(lead.created_at)}</div>
                            <button class="buy-btn" onclick="buyLead(${lead.lead_id})">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18">
                                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" 
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Buy Lead
                            </button>
                        </div>
                    </div>
                `).join('');

            } catch (error) {
                console.error('Error:', error);
                leadsGrid.innerHTML = `
                    <div class="error">
                        Error loading leads: ${error.message}
                        <button onclick="loadLeads()" class="retry-btn">Retry</button>
                    </div>
                `;
            }
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));

            if (days === 0) {
                return 'Today';
            } else if (days === 1) {
                return 'Yesterday';
            } else if (days < 7) {
                return `${days} days ago`;
            } else {
                return date.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
            }
        }

        // Add the buy lead function
        async function buyLead(leadId) {
            if (!confirm('Are you sure you want to buy this lead?')) {
                return;
            }

            try {
                const response = await fetch('buy_lead.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ lead_id: leadId })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Lead purchased successfully!');
                    loadLeads(); // Refresh the leads list
                } else {
                    alert(data.message || 'Error purchasing lead');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error purchasing lead');
            }
        }

        // Debounce function for search
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Add event listeners
        document.getElementById('searchLeads')?.addEventListener('input', debounce(() => loadLeads(), 500));
        document.getElementById('categoryFilter')?.addEventListener('change', () => loadLeads());
        document.getElementById('applyFilters')?.addEventListener('click', () => loadLeads());

        // Initial load
        loadLeads();
    </script>
</body>
</html> 