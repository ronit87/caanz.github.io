<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - CAANZ</title>
    <style>
/* styles kept similar to previous layout */
/* (shortened for brevity in patch but preserved in file) */
                * { margin:0; padding:0; box-sizing:border-box }
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height:1.6; color:#333; background:#f8f9fa }
                header { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:#fff; padding:1rem 0; position:sticky; top:0 }
                nav{ display:flex; justify-content:space-between; align-items:center; max-width:1200px; margin:0 auto; padding:0 2rem }
                .logo{ font-size:1.5rem; font-weight:700 }
                .nav-links{ display:flex; gap:1rem; list-style:none }
                .nav-links a{ color:white; text-decoration:none }
                .page-content{ min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem 1rem }
                .container{ background:#fff; border-radius:10px; box-shadow:0 10px 40px rgba(0,0,0,.12); width:100%; max-width:420px; padding:2rem }
                .login-header{ text-align:center; margin-bottom:1.25rem }
                .form-group{ margin-bottom:1rem }
                label{ display:block; margin-bottom:.5rem; font-weight:600 }
                input[type=email], input[type=password]{ width:100%; padding:.75rem; border:2px solid #e0e0e0; border-radius:6px }
                .btn{ display:block; width:100%; padding:.75rem; background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:#fff; border:none; border-radius:6px; font-weight:700; cursor:pointer }
                .details{ margin-top:1rem; background:#f0f4f8; padding:1rem; border-radius:8px; border:1px solid #dce6fb }
                .details pre{ white-space:pre-wrap; word-break:break-word; font-family:monospace }
                @media(max-width:768px){ .nav-links{ flex-direction:column; gap:.5rem } }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">CAANZ</div>
            <ul class="nav-links">
                <li><a href="index.html#contact">Contact</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="registration.php">Register</a></li>
            </ul>
        </nav>
    </header>

    <main class="page-content">
        <div class="container">
            <div class="login-header">
                <h1>CAANZ</h1>
                <p>Sign in to your account</p>
            </div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" placeholder="you@example.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" placeholder="Minimum 8 characters" required>
                </div>
                <button class="btn" type="submit">Sign In</button>
            </form>

            <div id="output" class="details" style="display:none; margin-top:1rem"></div>

            <div style="margin-top:1rem; text-align:center">
                <a href="registration.php">Don't have an account? Register</a>
            </div>
        </div>
    </main>

    <script>
        async function sha256Hex(message) {
            const enc = new TextEncoder();
            const data = enc.encode(message);
            const hash = await crypto.subtle.digest('SHA-256', data);
            const bytes = new Uint8Array(hash);
            return Array.from(bytes).map(b => b.toString(16).padStart(2,'0')).join('');
        }

        document.getElementById('loginForm').addEventListener('submit', async function(e){
            e.preventDefault();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value || '';
            const errors = [];
            if (!email) errors.push('Email is required');
            else { const re = /^[^@\s]+@[^@\s]+\.[^@\s]+$/; if (!re.test(email)) errors.push('Invalid email format'); }
            if (!password) errors.push('Password is required');
            else if (password.length < 8) errors.push('Password must be at least 8 characters');

            const out = document.getElementById('output');
            if (errors.length) {
                out.style.display = 'block';
                out.innerHTML = '<strong>Errors:</strong><ul>' + errors.map(e=>'<li>'+e+'</li>').join('') + '</ul>';
                return;
            }

            const hash = await sha256Hex(password);
            const now = new Date().toISOString();
            out.style.display = 'block';
            out.innerHTML = '<h3>Submitted Details</h3>' +
                '<div><strong>Email:</strong> ' + escapeHtml(email) + '</div>' +
                '<div><strong>Password:</strong> ••••••••</div>' +
                '<div><strong>Password SHA-256:</strong> <pre>' + hash + '</pre></div>' +
                '<div><strong>Submitted At:</strong> ' + now + '</div>';
            // Optionally clear form
            // this.reset();
        });

        function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CAANZ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }
        
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        
        .nav-links a:hover {
            opacity: 0.8;
        }
        
        .page-content {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-group input::placeholder {
            color: #999;
        }
        
        .errors {
            background: #fee;
            color: #c33;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #c33;
        }
        
        .errors ul {
            list-style: none;
        }
        
        .errors li {
            margin: 0.5rem 0;
        }
        
        .success {
            background: #efe;
            color: #3c3;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #3c3;
        }
        
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.9rem;
        }
        
        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .password-hint {
            font-size: 0.85rem;
            color: #999;
            margin-top: 0.3rem;
        }
        
        .home-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .home-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .home-link a:hover {
            text-decoration: underline;
        }
        
        .details-section {
            background: #f0f4f8;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            border: 2px solid #667eea;
        }
        
        .details-section h2 {
            color: #667eea;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .detail-item {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #ddd;
            align-items: center;
        }
        
        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .detail-label {
            font-weight: bold;
            color: #333;
            font-size: 0.95rem;
        }
        
        .detail-value {
            color: #555;
            font-size: 0.95rem;
            word-break: break-all;
            background: white;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
        }
        
        .detail-password {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #888;
        }
        
        .btn-login-again {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 1rem;
        }
        
        .btn-login-again:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }

            .nav-links {
                flex-direction: column;
                gap: 1rem;
            }

            .detail-item {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <header>
        <nav>
            <div class="logo">CAANZ</div>
            <ul class="nav-links">
                <li><a href="index.html#features">Features</a></li>
                <li><a href="index.html#about">About</a></li>
                <li><a href="index.html#contact">Contact</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="registration.php">Register</a></li>
            </ul>
        </nav>
    </header>

    <div class="page-content">
        <div class="container">
            <div class="login-header">
                <h1>CAANZ</h1>
                <p>Sign in to your account</p>
            </div>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
            
            <?php if ($login_details): ?>
                <!-- Display Login Details -->
                <div class="details-section">
                    <h2>Login Credentials Submitted</h2>
                    
                    <div class="detail-item">
                        <span class="detail-label">Email Address:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($login_details['email']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Password:</span>
                        <span class="detail-value">••••••••</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Password Hash:</span>
                        <span class="detail-value detail-password"><?php echo htmlspecialchars($login_details['password_hash']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Login Time:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($login_details['login_time']); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">IP Address:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($login_details['ip_address']); ?></span>
                    </div>
                </div>
                
                <button type="button" class="btn-login-again" onclick="location.reload();">Login Another User</button>
            <?php endif; ?>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Enter your password"
                    required
                >
                <div class="password-hint">Minimum 8 characters</div>
            </div>
            
            <button type="submit" class="btn-login">Sign In</button>
        </form>
        
        <div class="login-footer">
            <p>Don't have an account? <a href="registration.php">Sign up here</a></p>
        </div>
        
        <div class="home-link">
            <a href="index.html">← Back to Home</a>
        </div>
        </div>
    </div>
</body>
</html>
