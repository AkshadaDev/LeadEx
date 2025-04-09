<?php
require_once 'includes/config.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['profile_image'] = $user['profile_image'];
        
        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Leadex</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.9)),
                        url('https://images.unsplash.com/photo-1557683311-eac922347aa1?auto=format&fit=crop&w=1920');
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }

        .auth-card {
            background: rgba(17, 17, 17, 0.95);
            padding: 2rem;
            border-radius: 12px;
            width: 100%;
            max-width: 340px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
            animation: cardFloat 1s ease-out forwards;
        }

        .logo-text {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #3a86ff, #00f2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            opacity: 0;
            transform: translateY(20px);
            animation: logoReveal 0.8s ease forwards 0.3s;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateX(-20px);
        }

        .input-group:nth-child(1) { animation: slideRight 0.5s ease forwards 0.8s; }
        .input-group:nth-child(2) { animation: slideRight 0.5s ease forwards 1s; }

        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 45px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
        }

        .input-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
        }

        .input-group input:focus {
            border-color: #3a86ff;
            box-shadow: 0 0 0 2px rgba(58, 134, 255, 0.1);
            transform: translateX(5px);
        }

        .input-group input:focus + .input-icon {
            color: #3a86ff;
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .login-btn {
            background: linear-gradient(45deg, #3a86ff, #00f2fe);
            border: none;
            padding: 12px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.5s ease forwards 1.2s;
        }

        .login-btn::before {
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

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(58, 134, 255, 0.4);
        }

        @keyframes cardFloat {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes logoReveal {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        @keyframes iconPulse {
            0% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.2); }
            100% { transform: translateY(-50%) scale(1); }
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards 1.4s;
        }

        .auth-footer a {
            color: #3a86ff;
            text-decoration: none;
            position: relative;
        }

        .auth-footer a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 1px;
            background: #3a86ff;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .auth-footer a:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* Glowing effect for active input */
        .input-group input:focus {
            box-shadow: 0 0 15px rgba(58, 134, 255, 0.2);
        }

        /* Add new neon effects */
        .cursor-glow {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(58, 134, 255, 0.15), transparent 70%);
            position: fixed;
            pointer-events: none;
            z-index: 0;
            transition: all 0.1s ease;
            mix-blend-mode: screen;
        }

        .input-group:hover::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #3a86ff, #00f2fe);
            border-radius: 10px;
            z-index: -1;
            animation: neonPulse 1.5s ease-in-out infinite;
            opacity: 0.5;
        }

        .login-btn:hover::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #3a86ff, #00f2fe);
            border-radius: 10px;
            z-index: -1;
            filter: blur(8px);
            animation: neonPulse 1.5s ease-in-out infinite;
        }

        @keyframes neonPulse {
            0% { opacity: 0.5; }
            50% { opacity: 0.8; }
            100% { opacity: 0.5; }
        }

        .logo-text {
            text-shadow: 0 0 10px rgba(58, 134, 255, 0.5),
                         0 0 20px rgba(58, 134, 255, 0.3),
                         0 0 30px rgba(58, 134, 255, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <div class="logo">
                <a href="index.php"><h1>Leadex</h1></a>
            </div>
            <ul class="nav-links">
                <li><a href="register.php" class="btn-primary">Register</a></li>
            </ul>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo-text">Leadex</div>
                <p>Welcome back! Please login to continue</p>
            </div>

            <?php
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-error">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            if (isset($_GET['success'])) {
                echo '<div class="alert alert-success">Registration successful! Please login.</div>';
            }
            ?>

            <form action="login.php" method="POST" class="auth-form">
                <div class="input-group">
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"/>
                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                </div>
                
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M7 11V7C7 4.23858 9.23858 2 12 2C14.7614 2 17 4.23858 17 7V11" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <script>
        // Add loading state to login button on form submit
        document.querySelector('.auth-form').addEventListener('submit', function() {
            const button = this.querySelector('.login-btn');
            button.style.opacity = '0.7';
            button.innerHTML = 'Logging in...';
        });

        // Input field animations
        document.querySelectorAll('.input-group input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-icon').style.animation = 'none';
                setTimeout(() => {
                    this.parentElement.querySelector('.input-icon').style.animation = 'iconPulse 0.5s ease';
                }, 10);
            });
        });

        // Create cursor glow effect
        const cursorGlow = document.createElement('div');
        cursorGlow.classList.add('cursor-glow');
        document.body.appendChild(cursorGlow);

        // Update cursor glow position
        document.addEventListener('mousemove', (e) => {
            const x = e.clientX - 100;
            const y = e.clientY - 100;
            cursorGlow.style.transform = `translate(${x}px, ${y}px)`;
        });

        // Add neon effect to interactive elements
        const interactiveElements = document.querySelectorAll('.input-group, .login-btn');
        
        interactiveElements.forEach(element => {
            element.addEventListener('mouseenter', () => {
                cursorGlow.style.width = '300px';
                cursorGlow.style.height = '300px';
                cursorGlow.style.opacity = '1';
            });

            element.addEventListener('mouseleave', () => {
                cursorGlow.style.width = '200px';
                cursorGlow.style.height = '200px';
                cursorGlow.style.opacity = '0.7';
            });
        });
    </script>
</body>
</html> 