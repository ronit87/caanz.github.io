<?php
session_start();

// Database configuration (for demo purposes)
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'caanz_db';

$errors = [];
$success = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $gender = trim($_POST['gender'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    
    // Email validation
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    // Password validation
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }
    
    if (empty($confirm_password)) {
        $errors[] = 'Password confirmation is required';
    } elseif ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    // Gender validation
    if (empty($gender)) {
        $errors[] = 'Please select a gender';
    } elseif (!in_array($gender, ['Male', 'Female', 'Other', 'Prefer not to say'])) {
        $errors[] = 'Invalid gender selection';
    }
    
    // City validation
    if (empty($city)) {
        $errors[] = 'City is required';
    } elseif (strlen($city) < 2) {
        $errors[] = 'City must be at least 2 characters';
    } elseif (strlen($city) > 50) {
        $errors[] = 'City must not exceed 50 characters';
    }
    
    // Date of birth validation
    if (empty($dob)) {
        $errors[] = 'Date of birth is required';
    } else {
        $dob_timestamp = strtotime($dob);
        $today_timestamp = strtotime(date('Y-m-d'));
        
        if ($dob_timestamp === false) {
            $errors[] = 'Invalid date format';
        } elseif ($dob_timestamp > $today_timestamp) {
            $errors[] = 'Date of birth cannot be in the future';
        } else {
            $age = date_diff(date_create($dob), date_create('today'))->y;
            if ($age < 13) {
                $errors[] = 'You must be at least 13 years old to register';
            }
        }
    }
    
    // If no validation errors, display the registration details
    if (empty($errors)) {
        $success = 'Registration form submitted successfully!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CAANZ</title>
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
            max-width: 500px;
            padding: 2rem;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .register-header p {
            color: #666;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .form-row .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group select:focus {
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
            font-size: 0.9rem;
        }
        
        .success {
            background: #efe;
            color: #3c3;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #3c3;
            text-align: center;
        }
        
        .password-hint {
            font-size: 0.85rem;
            color: #999;
            margin-top: 0.3rem;
        }
        
        .btn-register {
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
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.9rem;
        }
        
        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-footer a:hover {
            text-decoration: underline;
        }
        
        .terms {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 1.5rem;
            text-align: center;
            line-height: 1.4;
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
            grid-template-columns: 150px 1fr;
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
        
        @media (max-width: 480px) {
            .container {
                padding: 1.5rem;
            }
            
            .register-header h1 {
                font-size: 1.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-header">
            <h1>CAANZ</h1>
            <p>Create your account</p>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li>✗ <?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
            
            <!-- Display Registered Details -->
            <div class="details-section">
                <h2>Registered User Details</h2>
                
                <div class="detail-item">
                    <span class="detail-label">Email Address:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($email); ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Password (Hashed):</span>
                    <span class="detail-value detail-password"><?php echo password_hash($password, PASSWORD_BCRYPT); ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Gender:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($gender); ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">City:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($city); ?></span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Date of Birth:</span>
                    <span class="detail-value">
                        <?php 
                            $dob_formatted = date('F d, Y', strtotime($dob));
                            $age = date_diff(date_create($dob), date_create('today'))->y;
                            echo htmlspecialchars($dob_formatted) . ' (Age: ' . $age . ')';
                        ?>
                    </span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Registration Date:</span>
                    <span class="detail-value"><?php echo date('F d, Y H:i:s'); ?></span>
                </div>
            </div>
            
            <button type="button" class="btn-register" onclick="location.reload();">Register Another User</button>
        <?php endif; ?>
        
        <form method="POST" action="">
            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="your.email@example.com"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required
                >
            </div>
            
            <!-- Password and Confirm Password -->
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="At least 8 characters"
                        required
                    >
                    <div class="password-hint">Minimum 8 characters</div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        placeholder="Re-enter password"
                        required
                    >
                </div>
            </div>
            
            <!-- Gender -->
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Select your gender</option>
                    <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    <option value="Prefer not to say" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Prefer not to say') ? 'selected' : ''; ?>>Prefer not to say</option>
                </select>
            </div>
            
            <!-- City and Date of Birth -->
            <div class="form-row">
                <div class="form-group">
                    <label for="city">City</label>
                    <input 
                        type="text" 
                        id="city" 
                        name="city" 
                        placeholder="Your city"
                        value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input 
                        type="date" 
                        id="dob" 
                        name="dob" 
                        value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>"
                        required
                    >
                </div>
            </div>
            
            <!-- Terms -->
            <div class="terms">
                By registering, you agree to our <a href="#" style="color: #667eea;">Terms of Service</a> and <a href="#" style="color: #667eea;">Privacy Policy</a>
            </div>
            
            <button type="submit" class="btn-register">Create Account</button>
        </form>
        
        <div class="register-footer">
            <p>Already have an account? <a href="login.php">Sign in here</a></p>
        </div>
        
        <div class="home-link">
            <a href="index.html">← Back to Home</a>
        </div>
    </div>
</body>
</html>
