<?php
$errors = [];
$success = '';
$login_details = null;

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }
    
    // If no validation errors, display login details
    if (empty($errors)) {
        $success = 'Login credentials submitted successfully!';
        $login_details = [
            'email' => $email,
            'password' => $password,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'login_time' => date('F d, Y H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
        ];
    }
}
?>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
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
    </style>
</head>
<body>
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
</body>
</html>
