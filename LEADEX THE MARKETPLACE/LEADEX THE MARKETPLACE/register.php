<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Leadex</title>
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

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
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

        .auth-header p {
            color: #888;
            font-size: 0.9rem;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards 0.8s;
        }

        .user-type-switch {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .user-type-option {
            flex: 1;
            padding: 0.8rem;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #fff;
            opacity: 0;
            transform: translateY(20px);
        }

        .user-type-option:nth-child(1) {
            animation: slideUp 0.5s ease forwards 0.4s;
        }

        .user-type-option:nth-child(2) {
            animation: slideUp 0.5s ease forwards 0.6s;
        }

        .user-type-option:hover {
            background: rgba(58, 134, 255, 0.1);
            transform: translateY(-2px);
        }

        .user-type-option.active {
            border-color: #3a86ff;
            background: rgba(58, 134, 255, 0.1);
            box-shadow: 0 0 15px rgba(58, 134, 255, 0.2);
        }

        .form-group {
            margin-bottom: 1rem;
            opacity: 0;
            transform: translateX(-20px);
            animation: slideRight 0.5s ease forwards;
        }

        .form-group:nth-child(1) { animation-delay: 0.8s; }
        .form-group:nth-child(2) { animation-delay: 1s; }
        .form-group:nth-child(3) { animation-delay: 1.2s; }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateX(-20px);
        }

        .input-group:nth-child(1) { animation: slideRight 0.5s ease forwards 0.8s; }
        .input-group:nth-child(2) { animation: slideRight 0.5s ease forwards 1s; }
        .input-group:nth-child(3) { animation: slideRight 0.5s ease forwards 1.2s; }

        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: #3a86ff;
            box-shadow: 0 0 0 2px rgba(58, 134, 255, 0.1);
            transform: translateX(5px);
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            transition: all 0.3s ease;
        }

        .input-group input:focus + .input-icon {
            color: #3a86ff;
            animation: iconPulse 0.5s ease;
        }

        .input-group input:focus {
            box-shadow: 0 0 15px rgba(58, 134, 255, 0.2);
        }

        .register-btn {
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
            animation: slideUp 0.5s ease forwards 1.4s;
        }

        .register-btn::before {
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

        .register-btn:hover::before {
            left: 100%;
        }

        .register-btn:hover {
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

        @keyframes slideUp {
            to {
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

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards 1.6s;
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

        .register-btn:hover::after {
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

        .user-type-option:hover {
            box-shadow: 0 0 15px rgba(58, 134, 255, 0.4);
        }

        .user-type-option.active {
            box-shadow: 0 0 20px rgba(58, 134, 255, 0.6);
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
                <li><a href="login.php" class="btn-primary">Login</a></li>
            </ul>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo-text">Leadex</div>
                <p>Join our community of buyers and sellers</p>
            </div>

            <form action="process_register.php" method="POST" class="auth-form">
                <div class="user-type-switch">
                    <div class="user-type-option" data-type="buyer">Buyer</div>
                    <div class="user-type-option" data-type="seller">Seller</div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" id="username" name="username" placeholder="Choose a username" required>
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </span>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-group">
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="4" width="20" height="16" rx="3" stroke="currentColor" stroke-width="2"/>
                                <path d="M2 8L10.1649 13.7154C11.2721 14.4531 12.7279 14.4531 13.8351 13.7154L22 8" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </span>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
                                <path d="M7 11V7C7 4.23858 9.23858 2 12 2C14.7614 2 17 4.23858 17 7V11" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </span>
                    </div>
                </div>
                
                <input type="hidden" id="user_type" name="user_type" value="buyer">
                
                <button type="submit" class="register-btn">Create Account</button>
            </form>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>

    <script>
        const userTypeOptions = document.querySelectorAll('.user-type-option');
        const userTypeInput = document.getElementById('user_type');

        userTypeOptions.forEach(option => {
            option.addEventListener('click', function() {
                userTypeOptions.forEach(opt => {
                    opt.classList.remove('active');
                    opt.style.transform = 'translateY(0)';
                });
                this.classList.add('active');
                this.style.transform = 'translateY(-2px)';
                userTypeInput.value = this.dataset.type;
            });
        });

        userTypeOptions[0].classList.add('active');

        document.querySelectorAll('.input-group input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-icon').style.animation = 'none';
                setTimeout(() => {
                    this.parentElement.querySelector('.input-icon').style.animation = 'iconPulse 0.5s ease';
                }, 10);
            });
        });

        document.querySelector('.auth-form').addEventListener('submit', function() {
            const button = this.querySelector('.register-btn');
            button.style.opacity = '0.7';
            button.innerHTML = 'Creating Account...';
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
        const interactiveElements = document.querySelectorAll('.input-group, .register-btn, .user-type-option');
        
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