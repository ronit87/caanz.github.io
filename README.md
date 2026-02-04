# caanz.github.io

Quick test instructions to run the PHP pages locally.

Prerequisites
- PHP 8+ installed (check with `php -v`).

Run PHP built-in server
1. Open a terminal in the repository root (this folder).

2. Start the server:

```bash
php -S 0.0.0.0:8000 -t .
```

3. Open in a browser:

```bash
$BROWSER http://localhost:8000/index.html
```

Notes
- The site is a static/demo site with `index.html` and two PHP pages: `login.php` and `registration.php`.
- `login.php` and `registration.php` do client/server-side validation and, for demo purposes, display submitted details on the page instead of storing them in a database.
- If you see a permissions or binding error, try a different port (e.g. `:8080`).

Quick verification (optional)
- Lint PHP files:

```bash
php -l login.php registration.php
```

- Test HTTP with curl:

```bash
curl -I http://localhost:8000/index.html
```