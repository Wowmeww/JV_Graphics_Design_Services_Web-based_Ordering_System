<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Server Error</title>
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />

    <style>
        :root {
            --primary: #7c3aed;
            --primary-dark: #6d28d9;
            --error: #ef4444;
            --text: #1e293b;
            --text-light: #64748b;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            line-height: 1.6;
            background-image:
                radial-gradient(circle at 25% 25%, rgba(124, 58, 237, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(239, 68, 68, 0.05) 0%, transparent 50%);
        }

        .container {
            text-align: center;
            max-width: 680px;
            padding: 2rem;
        }

        .card {
            background: var(--card);
            border-radius: 1rem;
            padding: 3rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        h1 {
            font-size: 5rem;
            margin: 0;
            line-height: 1;
            background: linear-gradient(45deg, var(--primary), var(--error));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 800;
            letter-spacing: -0.05em;
        }

        h2 {
            font-size: 1.75rem;
            margin: 1rem 0 0.5rem;
            color: var(--text);
        }

        p {
            font-size: 1.125rem;
            margin: 0 auto 2rem;
            color: var(--text-light);
            max-width: 80%;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            border: 2px solid var(--primary);
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
        }

        .btn-outline:hover {
            background: rgba(124, 58, 237, 0.1);
        }

        .illustration {
            width: 220px;
            margin: 0 auto 1.5rem;
            filter: drop-shadow(0 10px 8px rgba(0, 0, 0, 0.04));
        }

        .error-details {
            background: #f1f5f9;
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 1.5rem auto 0;
            max-width: 80%;
            text-align: left;
            font-family: 'Menlo', monospace;
            font-size: 0.875rem;
            color: #475569;
            overflow-x: auto;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.6;
            }
        }

        @media (max-width: 640px) {
            h1 {
                font-size: 3.5rem;
            }

            .illustration {
                width: 180px;
            }

            .btn-group {
                flex-direction: column;
            }

            p,
            .error-details {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="illustration">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="90" fill="#ede9fe" />
                    <path d="M100 40L100 80M100 120L100 160M60 100L80 100M120 100L140 100" stroke="#7c3aed"
                        stroke-width="6" stroke-linecap="round" />
                    <circle cx="100" cy="100" r="40" fill="white" stroke="#7c3aed" stroke-width="4" />
                    <path d="M70 70L130 130M70 130L130 70" stroke="#ef4444" stroke-width="6" stroke-linecap="round" />
                    <circle class="pulse" cx="100" cy="100" r="10" fill="#ef4444" />
                </svg>
            </div>

            <h1>500</h1>
            <h2>Internal Server Error</h2>
            <p>Something went wrong on our end. Our team has been notified and we're working to fix it.</p>

            <div class="btn-group">
                <a href="/" class="btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3 12L5 10M5 10L12 3L19 10M5 10V20C5 20.5523 5.44772 21 6 21H9M19 10L21 12M19 10V20C19 20.5523 18.5523 21 18 21H15M9 21C9.55228 21 10 20.5523 10 20V16C10 15.4477 10.4477 15 11 15H13C13.5523 15 14 15.4477 14 16V20C14 20.5523 14.4477 21 15 21M9 21H15"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Return Home
                </a>
                <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}" class="btn btn-outline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3 8L10.8906 13.2604C11.5624 13.7083 12.4376 13.7083 13.1094 13.2604L21 8M5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Contact Support
                </a>
            </div>

            <div class="error-details">
                <div>Error: Internal Server Error</div>
                <div>Request ID: #5A3B9C2E</div>
                <div>Timestamp: <span id="timestamp"></span></div>
            </div>
        </div>
    </div>

    <script>
        // Set current timestamp
        document.getElementById('timestamp').textContent = new Date().toLocaleString();

        // Generate a random request ID (in a real app, this would come from your backend)
        const requestId = Math.random().toString(16).substr(2, 8).toUpperCase();
        document.querySelector('.error-details div:nth-child(2)').textContent = `Request ID: ${requestId}`;
    </script>
</body>

</html>
