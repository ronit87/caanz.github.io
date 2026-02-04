<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register - CAANZ</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Segoe UI',Tahoma,Arial,sans-serif;line-height:1.6;color:#333;background:#f8f9fa}
        header{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;padding:1rem 0}
        nav{display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto;padding:0 2rem}
        .logo{font-weight:700}
        .nav-links{display:flex;gap:1rem;list-style:none}
        .nav-links a{color:white;text-decoration:none}
        .page-content{min-height:calc(100vh - 60px);display:flex;align-items:center;justify-content:center;padding:2rem 1rem}
        .container{background:#fff;border-radius:10px;box-shadow:0 10px 40px rgba(0,0,0,.12);width:100%;max-width:540px;padding:2rem}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
        label{display:block;margin-bottom:.5rem;font-weight:600}
        input,select{width:100%;padding:.75rem;border:2px solid #e0e0e0;border-radius:6px}
        .btn{display:block;width:100%;padding:.75rem;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none;border-radius:6px;font-weight:700;cursor:pointer}
        .details{margin-top:1rem;background:#f0f4f8;padding:1rem;border-radius:8px;border:1px solid #dce6fb}
        .details pre{white-space:pre-wrap;word-break:break-word;font-family:monospace}
        @media(max-width:768px){.form-row{grid-template-columns:1fr}.nav-links{flex-direction:column}}
    </style>
</head>
<body>
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

    <main class="page-content">
        <div class="container">
            <div style="text-align:center;margin-bottom:1rem">
                <h1>CAANZ</h1>
                <p>Create your account</p>
            </div>

            <form id="registerForm">
                <div style="margin-bottom:1rem">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" placeholder="you@example.com" required>
                </div>
                <div class="form-row">
                    <div>
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" placeholder="At least 8 characters" required>
                    </div>
                    <div>
                        <label for="confirm_password">Confirm Password</label>
                        <input id="confirm_password" name="confirm_password" type="password" placeholder="Re-enter password" required>
                    </div>
                </div>
                <div style="margin-top:1rem">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select your gender</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                        <option>Prefer not to say</option>
                    </select>
                </div>
                <div class="form-row" style="margin-top:1rem">
                    <div>
                        <label for="city">City</label>
                        <input id="city" name="city" type="text" placeholder="Your city" required>
                    </div>
                    <div>
                        <label for="dob">Date of Birth</label>
                        <input id="dob" name="dob" type="date" required>
                    </div>
                </div>

                <button class="btn" type="submit" style="margin-top:1rem">Create Account</button>
            </form>

            <div id="output" class="details" style="display:none"></div>

            <div style="margin-top:1rem;text-align:center">
                <a href="login.php">Already have an account? Sign in</a>
            </div>
        </div>
    </main>

    <script>
        async function sha256Hex(message){ const enc=new TextEncoder(); const data=enc.encode(message); const hash=await crypto.subtle.digest('SHA-256',data); return Array.from(new Uint8Array(hash)).map(b=>b.toString(16).padStart(2,'0')).join('') }

        function ageFromDob(dob){ const b=new Date(dob); if (Number.isNaN(b.getTime())) return null; const today=new Date(); let age=today.getFullYear()-b.getFullYear(); const m=today.getMonth()-b.getMonth(); if (m<0 || (m===0 && today.getDate()<b.getDate())) age--; return age }

        function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }) }

        document.getElementById('registerForm').addEventListener('submit', async function(e){
            e.preventDefault();
            const email=document.getElementById('email').value.trim();
            const password=document.getElementById('password').value||'';
            const confirm=document.getElementById('confirm_password').value||'';
            const gender=document.getElementById('gender').value;
            const city=document.getElementById('city').value.trim();
            const dob=document.getElementById('dob').value;
            const errors=[];
            if (!email) errors.push('Email is required'); else { const re=/^[^@\s]+@[^@\s]+\.[^@\s]+$/; if (!re.test(email)) errors.push('Invalid email format') }
            if (!password) errors.push('Password is required'); else if (password.length<8) errors.push('Password must be at least 8 characters');
            if (!confirm) errors.push('Confirm password is required'); else if (password!==confirm) errors.push('Passwords do not match');
            if (!gender) errors.push('Please select a gender');
            if (!city || city.length<2) errors.push('City must be at least 2 characters');
            const age = ageFromDob(dob);
            if (!dob) errors.push('Date of birth is required'); else if (age===null) errors.push('Invalid date of birth'); else if (age<13) errors.push('You must be at least 13 years old');

            const out=document.getElementById('output');
            if (errors.length){ out.style.display='block'; out.innerHTML='<strong>Errors:</strong><ul>'+errors.map(x=>'<li>'+escapeHtml(x)+'</li>').join('')+'</ul>'; return }

            const hash=await sha256Hex(password);
            out.style.display='block';
            out.innerHTML = '<h3>Registered Details</h3>'+
                '<div><strong>Email:</strong> '+escapeHtml(email)+'</div>'+
                '<div><strong>Password:</strong> ••••••••</div>'+
                '<div><strong>Password SHA-256:</strong><pre>'+hash+'</pre></div>'+
                '<div><strong>Gender:</strong> '+escapeHtml(gender)+'</div>'+
                '<div><strong>City:</strong> '+escapeHtml(city)+'</div>'+
                '<div><strong>Date of Birth:</strong> '+escapeHtml(dob)+' (Age: '+age+')</div>'+
                '<div><strong>Registered At:</strong> '+new Date().toISOString()+'</div>';
        });
    </script>

</body>
</html>
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
            min-height: calc(100vh - 60px);
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
        
        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }

            .register-header h1 {
                font-size: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .nav-links {
                flex-direction: column;
                gap: 1rem;
                font-size: 0.9rem;
            }

            .detail-item {
                grid-template-columns: 1fr;
            }
        }
            .nav-links {
                flex-direction: column;
                gap: 1rem;
                font-size: 0.9rem;
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
    </div>
</body>
</html>
