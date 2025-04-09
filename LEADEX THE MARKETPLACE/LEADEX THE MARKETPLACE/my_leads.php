<?php
require_once 'includes/config.php';
requireLogin();

if ($_SESSION['user_type'] !== 'seller') {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Leads - Leadex</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Use the same navigation styles as post_lead_page.php */
        .nav-container {
            background: rgba(17, 19, 24, 0.95);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* Main Content Styles */
        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.8rem;
            color: white;
            margin: 0;
        }

        .leads-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .lead-card {
            background: rgba(30, 32, 37, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .lead-card:hover {
            transform: translateY(-2px);
            border-color: rgba(58, 134, 255, 0.3);
        }

        .lead-status {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-available {
            background: rgba(46, 213, 115, 0.15);
            color: #2ed573;
        }

        .status-sold {
            background: rgba(255, 71, 87, 0.15);
            color: #ff4757;
        }

        .lead-title {
            font-size: 1.2rem;
            color: white;
            margin-bottom: 0.5rem;
            padding-right: 80px;
        }

        .lead-price {
            color: #3a86ff;
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .lead-description {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .lead-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .lead-category {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #888;
        }

        .lead-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            background: none;
            border: none;
            padding: 0.4rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #888;
        }

        .action-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .action-btn.delete:hover {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #888;
        }

        .confirmation-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(30, 32, 37, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            z-index: 1000;
            width: 90%;
            max-width: 400px;
            display: none;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 999;
            display: none;
        }

        .modal-title {
            color: white;
            margin-bottom: 1rem;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .lead-card {
            animation: fadeIn 0.3s ease forwards;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="nav-container">
        <!-- Include your navigation here -->
    </div>

    <div class="main-container">
        <div class="page-header">
            <h1 class="page-title">My Leads</h1>
            <a href="post_lead_page.php" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18">
                    <path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Post New Lead
            </a>
        </div>

        <div class="leads-grid" id="leadsGrid">
            <!-- Leads will be loaded here -->
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-backdrop" id="modalBackdrop"></div>
    <div class="confirmation-modal" id="deleteModal">
        <h3 class="modal-title">Delete Lead</h3>
        <p>Are you sure you want to delete this lead? This action cannot be undone.</p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn btn-primary delete" id="confirmDelete">Delete</button>
        </div>
    </div>

    <script>
        let currentLeadId = null;

        async function loadLeads() {
            const leadsGrid = document.getElementById('leadsGrid');
            
            try {
                const response = await fetch('get_my_leads.php');
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message);
                }

                if (data.leads.length === 0) {
                    leadsGrid.innerHTML = `
                        <div class="empty-state">
                            <h3>No leads yet</h3>
                            <p>Start by posting your first lead</p>
                        </div>
                    `;
                    return;
                }

                leadsGrid.innerHTML = data.leads.map((lead, index) => `
                    <div class="lead-card" style="animation-delay: ${index * 0.1}s">
                        <span class="lead-status ${lead.status === 'available' ? 'status-available' : 'status-sold'}">
                            ${lead.status === 'available' ? 'Available' : 'Sold'}
                        </span>
                        <h3 class="lead-title">${lead.title}</h3>
                        <div class="lead-price">$${parseFloat(lead.price).toFixed(2)}</div>
                        <p class="lead-description">${lead.description || 'No description provided'}</p>
                        <div class="lead-meta">
                            <span class="lead-category">${lead.category_name}</span>
                            <div class="lead-actions">
                                <button class="action-btn delete" onclick="showDeleteModal(${lead.lead_id})">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18">
                                        <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6" 
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');

            } catch (error) {
                leadsGrid.innerHTML = `
                    <div class="error">
                        Error loading leads: ${error.message}
                        <button onclick="loadLeads()" class="retry-btn">Retry</button>
                    </div>
                `;
            }
        }

        function showDeleteModal(leadId) {
            currentLeadId = leadId;
            document.getElementById('modalBackdrop').style.display = 'block';
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('modalBackdrop').style.display = 'none';
            document.getElementById('deleteModal').style.display = 'none';
            currentLeadId = null;
        }

        document.getElementById('confirmDelete').addEventListener('click', async () => {
            if (!currentLeadId) return;

            try {
                const response = await fetch('delete_lead.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ lead_id: currentLeadId })
                });

                const data = await response.json();

                if (data.success) {
                    closeDeleteModal();
                    loadLeads();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                alert(error.message);
            }
        });

        // Initial load
        loadLeads();
    </script>
</body>
</html> 