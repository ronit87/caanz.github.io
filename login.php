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
