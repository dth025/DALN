<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HealthAI — Trợ lý sức khỏe thông minh bằng AI</title>
    <meta name="description" content="HealthAI giúp bạn theo dõi sức khỏe, tư vấn dinh dưỡng, luyện tập và đặt lịch khám với trí tuệ nhân tạo.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Theme initialization
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleDark() {
            document.documentElement.classList.toggle('dark');
            localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        }
    </script>
</head>
<body class="antialiased bg-background text-foreground relative min-h-screen overflow-x-hidden">
    <!-- Background Elements -->
    <div class="pointer-events-none fixed inset-0 -z-10" style="background: var(--gradient-hero)"></div>
    <div class="pointer-events-none fixed inset-0 -z-10 grid-bg opacity-40"></div>
    <div class="pointer-events-none fixed -top-40 -right-40 -z-10 h-96 w-96 rounded-full bg-primary/20 blur-[100px]"></div>
    <div class="pointer-events-none fixed -bottom-40 -left-40 -z-10 h-96 w-96 rounded-full bg-accent/20 blur-[100px]"></div>

    <!-- Navbar -->
    @include('landing.navbar')

    <!-- Sections -->
    <main>
        @include('landing.hero')
        @include('landing.stats')
        @include('landing.features')
        @include('landing.how-it-works')
        @include('landing.testimonials')
        @include('landing.pricing')
        @include('landing.cta')
    </main>

    <!-- Footer -->
    @include('landing.footer')

</body>
</html>
