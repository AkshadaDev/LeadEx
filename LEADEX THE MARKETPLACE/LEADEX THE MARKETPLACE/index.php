<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leadex - Buy & Sell Leads</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <div class="logo">
                <a href="index.php"><h1>Leadex</h1></a>
            </div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#browse">Browse Leads</a></li>
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="login.php" class="btn-secondary">Login</a></li>
                <li><a href="register.php" class="btn-primary">Get Started</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container hero-content">
            <h1>Buy and Sell High-Quality Leads</h1>
            <p>Connect with verified buyers and sellers in our secure marketplace</p>
            <div class="cta-buttons">
                <a href="register.php?type=buyer" class="btn-primary">Buy Leads</a>
                <a href="register.php?type=seller" class="btn-secondary">Sell Leads</a>
            </div>
        </div>
    </header>

    <!-- Rest of the HTML remains the same -->
    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2>Why Choose Leadex?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="icon-wrapper">
                        <img src="img/verified.svg" alt="Verified" class="feature-icon">
                    </div>
                    <h3>Verified Sellers</h3>
                    <p>All sellers are thoroughly vetted to ensure quality leads</p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrapper">
                        <img src="img/secure.svg" alt="Secure" class="feature-icon">
                    </div>
                    <h3>Secure Transactions</h3>
                    <p>Your payments and data are protected</p>
                </div>
                <div class="feature-card">
                    <div class="icon-wrapper">
                        <img src="img/quality.svg" alt="Quality" class="feature-icon">
                    </div>
                    <h3>Quality Guaranteed</h3>
                    <p>Only high-quality, verified leads</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <div class="container">
            <h2>Browse by Category</h2>
            <div class="category-grid">
                <a href="#" class="category-card">
                    <h3>Business</h3>
                    <p>B2B and B2C leads</p>
                </a>
                <a href="#" class="category-card">
                    <h3>Technology</h3>
                    <p>Tech and IT leads</p>
                </a>
                <a href="#" class="category-card">
                    <h3>Real Estate</h3>
                    <p>Property leads</p>
                </a>
                <a href="#" class="category-card">
                    <h3>Marketing</h3>
                    <p>Marketing qualified leads</p>
                </a>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <h2>How It Works</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Create Account</h3>
                    <p>Sign up as a buyer or seller</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Browse or List</h3>
                    <p>Find leads or list your own</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Transact Securely</h3>
                    <p>Buy or sell with confidence</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Leadex</h3>
                    <p>Your trusted platform for buying and selling leads</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#browse">Browse Leads</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <ul>
                        <li>Email: support@leadex.com</li>
                        <li>Phone: (555) 123-4567</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Leadex. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html> 