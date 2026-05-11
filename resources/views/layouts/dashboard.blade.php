<!DOCTYPE html>
<html lang="vi" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HealthAI')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script>
        function toggleDark() {
            document.documentElement.classList.toggle('dark');
            let isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                if(backdrop) backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                if(backdrop) backdrop.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
</head>
<body class="bg-background text-foreground antialiased min-h-screen relative">
    <div class="pointer-events-none fixed inset-0 -z-10" style="background: var(--gradient-hero)"></div>
    <div class="pointer-events-none fixed inset-0 -z-10 grid-bg opacity-40"></div>

    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="sticky top-0 z-40 w-full border-b border-sidebar-border bg-sidebar/95 backdrop-blur-xl shadow-sm">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-4">
                    <!-- Logo Section -->
                    <div class="flex items-center gap-8">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                            <div class="relative">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl gradient-primary shadow-glow group-hover:scale-105 transition-transform">
                                    <i data-lucide="heart-pulse" class="h-5 w-5 text-primary-foreground"></i>
                                </div>
                                <span class="absolute -right-1 -top-1 flex h-3 w-3">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success opacity-75"></span>
                                    <span class="relative inline-flex h-3 w-3 rounded-full bg-success ring-2 ring-sidebar"></span>
                                </span>
                            </div>
                            <div class="hidden sm:block">
                                <h1 class="text-base font-bold tracking-tight">Health<span class="gradient-text">AI</span></h1>
                                <p class="text-[9px] font-medium uppercase tracking-wider text-muted-foreground leading-none">Smart Health</p>
                            </div>
                        </a>

                        <!-- Horizontal Navigation -->
                        <nav class="hidden lg:flex items-center gap-1">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                                <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('health') }}" class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all {{ request()->routeIs('health') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                                <i data-lucide="activity" class="h-4 w-4"></i>
                                <span>Theo dõi</span>
                            </a>
                            <a href="{{ route('chatbot') }}" class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all {{ request()->routeIs('chatbot') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                                <i data-lucide="bot" class="h-4 w-4"></i>
                                <span>AI Chatbot</span>
                                <span class="rounded-md bg-primary/15 px-1 py-0.5 text-[8px] font-bold text-primary">AI</span>
                            </a>
                            <a href="{{ route('workout') }}" class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all {{ request()->routeIs('workout') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                                <i data-lucide="dumbbell" class="h-4 w-4"></i>
                                <span>Luyện tập</span>
                            </a>
                            <a href="{{ route('appointments') }}" class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all {{ request()->routeIs('appointments') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                                <i data-lucide="calendar-days" class="h-4 w-4"></i>
                                <span>Lịch khám</span>
                            </a>
                            <a href="{{ route('pricing') }}" class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all {{ request()->routeIs('pricing') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                                <i data-lucide="credit-card" class="h-4 w-4"></i>
                                <span>Gói dịch vụ</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Actions Section -->
                    <div class="flex items-center gap-2 md:gap-4 flex-1 justify-end">
                        <!-- Search (Hidden on small screens) -->
                        <div class="relative hidden md:block max-w-xs w-full">
                            <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-muted-foreground"></i>
                            <input placeholder="Tìm kiếm..." class="h-9 w-full rounded-full border border-border bg-card/40 pl-9 pr-4 text-xs outline-none transition-all focus:border-primary/50 focus:ring-4 focus:ring-primary/10">
                        </div>

                        <div class="flex items-center gap-2">
                            <button onclick="toggleDark()" class="flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-card/40 text-muted-foreground transition-all hover:border-primary/40 hover:text-primary">
                                <i data-lucide="moon" class="h-4 w-4 block dark:hidden"></i>
                                <i data-lucide="sun" class="h-4 w-4 hidden dark:block"></i>
                            </button>

                            <button class="relative flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-card/40 text-muted-foreground transition-all hover:border-primary/40 hover:text-primary">
                                <i data-lucide="bell" class="h-4 w-4"></i>
                                <span class="absolute right-2 top-2 h-1.5 w-1.5 rounded-full bg-destructive"></span>
                            </button>

                            <div class="h-8 w-px bg-border mx-1 hidden sm:block"></div>

                            <!-- User Profile Dropdown -->
                            <div class="flex items-center gap-2 pl-1">
                                <div class="relative group cursor-pointer">
                                    <div class="flex items-center gap-2 rounded-full border border-border bg-card/40 p-1 pr-2.5 transition-all hover:border-primary/30 hover:bg-card/60 shadow-sm group-hover:shadow-md">
                                        <div class="relative shrink-0">
                                            @if(Auth::user()->avatar)
                                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="user" class="h-8 w-8 aspect-square rounded-full object-cover shadow-sm ring-1 ring-border">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" alt="user" class="h-8 w-8 aspect-square rounded-full object-cover shadow-sm ring-1 ring-border">
                                            @endif
                                            <span class="absolute -right-0.5 -bottom-0.5 flex h-2.5 w-2.5">
                                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success opacity-75"></span>
                                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-success ring-2 ring-sidebar"></span>
                                            </span>
                                        </div>
                                        <i data-lucide="chevron-down" class="h-4 w-4 text-muted-foreground/60 transition-transform group-hover:translate-y-0.5"></i>
                                    </div>
                                    
                                    <!-- Simple Dropdown -->
                                    <div class="absolute right-0 top-full mt-2 w-48 origin-top-right rounded-xl border border-border bg-card p-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium hover:bg-muted transition-colors">
                                            <i data-lucide="user" class="h-3.5 w-3.5"></i> Hồ sơ của tôi
                                        </a>
                                        <div class="my-1 border-t border-border"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-destructive hover:bg-destructive/10 transition-colors">
                                                <i data-lucide="log-out" class="h-3.5 w-3.5"></i> Đăng xuất
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Menu Toggle -->
                            <button onclick="toggleMobileNav()" class="lg:hidden flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-primary-foreground shadow-lg shadow-primary/20">
                                <i data-lucide="menu" class="h-5 w-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-nav" class="lg:hidden fixed inset-x-0 top-16 z-30 hidden border-b border-border bg-sidebar/95 backdrop-blur-xl p-4 shadow-xl">
            <nav class="flex flex-col gap-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium hover:bg-sidebar-accent">
                    <i data-lucide="home" class="h-5 w-5"></i> Trang chủ
                </a>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-primary text-primary-foreground shadow-glow' : 'hover:bg-sidebar-accent' }}">
                    <i data-lucide="layout-dashboard" class="h-5 w-5"></i> Dashboard
                </a>
                <a href="{{ route('health') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('health') ? 'bg-primary text-primary-foreground shadow-glow' : 'hover:bg-sidebar-accent' }}">
                    <i data-lucide="activity" class="h-5 w-5"></i> Theo dõi sức khỏe
                </a>
                <a href="{{ route('chatbot') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('chatbot') ? 'bg-primary text-primary-foreground shadow-glow' : 'hover:bg-sidebar-accent' }}">
                    <i data-lucide="bot" class="h-5 w-5"></i> AI Chatbot
                </a>
                <a href="{{ route('workout') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('workout') ? 'bg-primary text-primary-foreground shadow-glow' : 'hover:bg-sidebar-accent' }}">
                    <i data-lucide="dumbbell" class="h-5 w-5"></i> Luyện tập AI
                </a>
                <a href="{{ route('appointments') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('appointments') ? 'bg-primary text-primary-foreground shadow-glow' : 'hover:bg-sidebar-accent' }}">
                    <i data-lucide="calendar-days" class="h-5 w-5"></i> Lịch khám
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="flex-1 max-w-[1600px] mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            @yield('content')
            
            @include('landing.footer')
        </main>
    </div>

    <script>
        function toggleMobileNav() {
            const nav = document.getElementById('mobile-nav');
            nav.classList.toggle('hidden');
        }
    </script>

    
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
