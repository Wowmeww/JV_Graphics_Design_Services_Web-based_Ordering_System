<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />
    <style>
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            line-height: 1.5;
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #0f172a;
        }

        p {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            color: #475569;
        }

        .illustration {
            width: 300px;
            margin: 0 auto 2rem;
        }

        .btn {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #2563eb;
        }

        .error-code {
            color: #94a3b8;
            font-size: 0.875rem;
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="illustration">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="90" fill="#fee2e2" />
                <path d="M100 40L100 80M100 110L100 120" stroke="#ef4444" stroke-width="6" stroke-linecap="round" />
                <circle cx="100" cy="140" r="10" fill="#ef4444" />
                <path d="M50 50L30 30M170 30L150 50M170 170L150 150M30 170L50 150" stroke="#b91c1c" stroke-width="4"
                    stroke-linecap="round" />
                <path d="M70 70C70 70 90 50 130 50C170 50 190 70 190 70M70 130C70 130 50 150 50 190" stroke="#b91c1c"
                    stroke-width="2" stroke-linecap="round" stroke-dasharray="4 4" />
            </svg>
        </div>

        <h1>Access Denied</h1>
        <p>You don't have permission to access this page. This could be because:</p>
        <ul style="text-align: left; max-width: 400px; margin: 0 auto 2rem;">
            <li>Your account doesn't have the required privileges</li>
            <li>You're trying to access restricted content</li>
            <li>The page requires authentication</li>
        </ul>

        <a href="/" class="btn">Return to Homepage</a>
        <div class="error-code">Error code: 403 Forbidden</div>
    </div>
</body>

</html>
