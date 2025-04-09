<aside class="sidebar">
    <div class="profile-section">
        <!-- Profile section code -->
    </div>
    
    <nav class="nav-menu">
        <a href="dashboard.php" class="nav-button">
            <span class="menu-icon">
                <svg><!-- Dashboard icon --></svg>
            </span>
            <span>Dashboard</span>
        </a>
        
        <?php if ($_SESSION['user_type'] === 'seller'): ?>
        <a href="post_lead_page.php" class="nav-button">
            <span class="menu-icon">
                <svg><!-- Post icon --></svg>
            </span>
            <span>Post Lead</span>
        </a>
        <?php else: ?>
        <a href="browse_leads.php" class="nav-button">
            <span class="menu-icon">
                <svg><!-- Browse icon --></svg>
            </span>
            <span>Browse Leads</span>
        </a>
        <?php endif; ?>
        
        <a href="profile.php" class="nav-button">
            <span class="menu-icon">
                <svg><!-- Profile icon --></svg>
            </span>
            <span>Profile</span>
        </a>
        
        <a href="logout.php" class="nav-button logout">
            <span class="menu-icon">
                <svg><!-- Logout icon --></svg>
            </span>
            <span>Logout</span>
        </a>
    </nav>
</aside> 