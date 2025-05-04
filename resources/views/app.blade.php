<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Inline script to detect system dark mode preference and apply it immediately --}}
    <script>
        (function() {
            const appearance = '{{ $appearance ?? 'system' }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    {{-- favicon --}}
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
    @routes
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>

<body class="font-montserrat antialiased text-dark dark:text-[#94A3B8] bg-[#F2F7FB] dark:bg-[#0f172a] h-full min-h-screen">
    @inertia
</body>

</html>
