<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ứng dụng Laravel')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Vite Assets (CSS/JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Thư viện Lucide CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-background text-foreground antialiased min-h-screen flex flex-col">
    
    <!-- Navbar Area -->
    <header class="border-b border-border bg-card/60 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="font-bold text-xl flex items-center gap-2">
                <i data-lucide="heart-pulse" class="text-primary h-6 w-6"></i>
                HealthAI
            </a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-4 py-8">
        @yield('content')
        
        <!-- ĐOẠN CODE CỦA BẠN ĐƯỢC CHÈN THỬ NGHIỆM TẠI ĐÂY -->
        <div class="mt-12 p-6 bg-card border border-border rounded-2xl">
            <h3 class="text-sm font-semibold mb-4 text-foreground">Kết nối với chúng tôi</h3>
            <div class="flex items-center gap-4">
                @foreach(['facebook', 'twitter', 'instagram', 'linkedin'] as $social)
                    <a href="#" class="flex h-10 w-10 items-center justify-center rounded-xl bg-card border border-border text-foreground/70 transition-all hover:bg-primary/10 hover:text-primary hover:border-primary/30 shadow-sm hover:-translate-y-1">
                        <x-dynamic-component :component="'icons.' . $social" class="h-4 w-4" />
                    </a>
                @endforeach
            </div>
        </div>
    </main>

    <!-- Footer Area -->
    <footer class="border-t border-border py-6 text-center text-sm text-foreground/60">
        <p>© {{ date('Y') }} HealthAI. All rights reserved.</p>
    </footer>

    <!-- KHỞI TẠO LUCIDE ICONS (Rất quan trọng) -->
    <script>
        // Sử dụng DOMContentLoaded để đảm bảo HTML đã tải xong trước khi render icon
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
