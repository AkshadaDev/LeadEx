<?php
require_once 'includes/config.php';
requireLogin();

// Ensure only sellers can access this page
if ($_SESSION['user_type'] !== 'seller') {
    header("Location: dashboard.php");
    exit();
}

// Get categories for dropdown
$stmt = $conn->query("SELECT * FROM categories ORDER BY category_name");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Lead - Leadex</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .post-lead-container {
            max-width: 1000px;
            margin: 100px auto 2rem;
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

        .page-title {
            font-size: 2.2rem;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, #fff 0%, #3a86ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
        }

        .lead-options {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .option-btn {
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #888;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .option-btn.active {
            background: rgba(58, 134, 255, 0.1);
            border-color: rgba(58, 134, 255, 0.2);
            color: #3a86ff;
        }

        .option-btn:hover {
            transform: translateY(-2px);
            background: rgba(58, 134, 255, 0.1);
            color: #3a86ff;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        .form-group {
            margin-bottom: 1.5rem;
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

        .bulk-upload-area {
            border: 2px dashed rgba(58, 134, 255, 0.3);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .bulk-upload-area:hover {
            border-color: #3a86ff;
            background: rgba(58, 134, 255, 0.05);
        }

        .bulk-upload-icon {
            font-size: 2rem;
            color: #3a86ff;
            margin-bottom: 1rem;
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

        .template-download {
            color: #3a86ff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .template-download:hover {
            transform: translateY(-2px);
            color: #00f2fe;
        }

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
        }

        .nav-btn svg {
            width: 16px;
            height: 16px;
            stroke-width: 2px;
        }

        .nav-btn:hover {
            background: rgba(58, 134, 255, 0.1);
            border-color: rgba(58, 134, 255, 0.2);
            color: #3a86ff;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
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

    <div class="post-lead-container">
        <h1 class="page-title">Post New Lead</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <div class="lead-options">
            <button class="option-btn active" data-form="single-lead">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                    <path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Single Lead
            </button>
            <button class="option-btn" data-form="bulk-lead">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                    <path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Bulk Upload
            </button>
        </div>

        <!-- Single Lead Form -->
        <div class="form-section active" id="single-lead">
            <form action="post_lead.php" method="POST" class="post-lead-form">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-input" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['category_id']; ?>">
                                <?php echo htmlspecialchars($category['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Price ($)</label>
                    <input type="number" name="price" class="form-input" step="0.01" min="0" required>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Post Lead
                    </button>
                </div>
            </form>
        </div>

        <!-- Bulk Upload Form -->
        <div class="form-section" id="bulk-lead">
            <form action="process_csv.php" method="POST" enctype="multipart/form-data" class="bulk-upload-form">
                <div class="bulk-upload-area" onclick="document.getElementById('bulk_file').click()">
                    <div class="bulk-upload-icon">📄</div>
                    <p>Click to upload CSV file or drag and drop</p>
                    <input type="file" id="bulk_file" name="bulk_file" accept=".csv" style="display: none" required>
                    <p class="selected-file"></p>
                </div>
                
                <a href="templates/bulk_upload_template.csv" class="template-download">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Download Template
                </a>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Upload Leads
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle between single and bulk upload forms
        document.querySelectorAll('.option-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and forms
                document.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.form-section').forEach(form => form.classList.remove('active'));
                
                // Add active class to clicked button and corresponding form
                button.classList.add('active');
                document.getElementById(button.dataset.form).classList.add('active');
            });
        });

        // Handle file selection
        document.getElementById('bulk_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.querySelector('.selected-file').textContent = `Selected: ${fileName}`;
            }
        });

        // Handle drag and drop
        const dropArea = document.querySelector('.bulk-upload-area');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropArea.classList.add('highlight');
        }

        function unhighlight(e) {
            dropArea.classList.remove('highlight');
        }

        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('bulk_file').files = files;
            
            if (files[0]) {
                document.querySelector('.selected-file').textContent = `Selected: ${files[0].name}`;
            }
        }

        document.querySelector('.bulk-upload-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            try {
                // Disable button and show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" width="20" height="20">
                        <path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83" stroke-width="2"/>
                    </svg>
                    Uploading...
                `;
                
                const response = await fetch('process_csv.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success';
                    alert.textContent = data.message;
                    this.insertBefore(alert, this.firstChild);
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
                    }, 2000);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                // Show error message
                const alert = document.createElement('div');
                alert.className = 'alert alert-error';
                alert.textContent = error.message;
                this.insertBefore(alert, this.firstChild);
            } finally {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    </script>
</body>
</html> 