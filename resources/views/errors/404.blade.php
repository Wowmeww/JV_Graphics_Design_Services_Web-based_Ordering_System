<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --text: #1e293b;
            --text-light: #64748b;
            --bg: #f8fafc;
            --card: #ffffff;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            line-height: 1.6;
            background-image: radial-gradient(circle at 10% 20%, rgba(224, 231, 255, 0.3) 0%, transparent 20%);
        }

        .container {
            text-align: center;
            max-width: 640px;
            padding: 2rem;
        }

        .card {
            background: var(--card);
            border-radius: 1rem;
            padding: 3rem 2rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        h1 {
            font-size: 6rem;
            margin: 0;
            line-height: 1;
            color: var(--primary);
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
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
        }

        .btn-outline:hover {
            background: rgba(99, 102, 241, 0.1);
        }

        .illustration {
            width: 240px;
            margin: -4rem auto 1rem;
            filter: drop-shadow(0 10px 8px rgba(0, 0, 0, 0.04));
        }

        .confetti {
            position: absolute;
            width: 12px;
            height: 12px;
            background: var(--primary);
            opacity: 0.7;
            border-radius: 50%;
            animation: fall 5s linear infinite;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh) rotate(360deg);
            }
        }

        @media (max-width: 640px) {
            h1 {
                font-size: 4rem;
            }

            .illustration {
                width: 180px;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <!-- Dynamic confetti -->
            <div id="confetti-container"></div>

            <div class="illustration">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="90" fill="#e0e7ff" />
                    <path d="M70 70L130 130M70 130L130 70" stroke="#6366f1" stroke-width="8" stroke-linecap="round" />
                    <path d="M100 50V80M100 120V150" stroke="#818cf8" stroke-width="4" stroke-linecap="round" />
                    <circle cx="100" cy="100" r="30" fill="white" stroke="#6366f1" stroke-width="4" />
                    <text x="100" y="110" font-family="Arial" font-size="40" font-weight="bold" text-anchor="middle"
                        fill="#6366f1">?</text>
                </svg>
            </div>

            <h1>404</h1>
            <h2>Page Not Found</h2>
            <p>The page you're looking for doesn't exist or has been moved. Try searching or go back to our homepage.
            </p>

            <div class="btn-group">
                <a href="/" class="btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3 12L5 10M5 10L12 3L19 10M5 10V20C5 20.5523 5.44772 21 6 21H9M19 10L21 12M19 10V20C19 20.5523 18.5523 21 18 21H15M9 21C9.55228 21 10 20.5523 10 20V16C10 15.4477 10.4477 15 11 15H13C13.5523 15 14 15.4477 14 16V20C14 20.5523 14.4477 21 15 21M9 21H15"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Go Home
                </a>
            </div>
        </div>
    </div>

    <script>
        // Generate confetti
        function createConfetti() {
            const colors = ['#6366f1', '#818cf8', '#a5b4fc', '#c7d2fe'];
            const confettiContainer = document.getElementById('confetti-container');

            for (let i = 0; i < 30; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.top = -20 + 'px';
                confetti.style.width = Math.random() * 8 + 6 + 'px';
                confetti.style.height = confetti.style.width;
                confetti.style.animationDelay = Math.random() * 5 + 's';
                confetti.style.animationDuration = Math.random() * 3 + 3 + 's';
                confettiContainer.appendChild(confetti);
            }
        }

        createConfetti();
    </script>
</body>

</html>
