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
                            <a href="{{ route('menu') }}" class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all {{ request()->routeIs('menu') ? 'bg-primary/10 text-primary shadow-sm' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                                <i data-lucide="salad" class="h-4 w-4"></i>
                                <span>Thực đơn</span>
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

                            <div x-data="notificationBell()" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)" class="relative">
                                <button @click="open = !open; if(open) fetchNotifications()" class="relative flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-card/40 text-muted-foreground transition-all hover:border-primary/40 hover:text-primary cursor-pointer">
                                    <i data-lucide="bell" class="h-4 w-4"></i>
                                    <span x-show="unreadCount > 0" x-transition
                                          class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-destructive text-[9px] font-black text-white shadow-lg animate-pulse"
                                          x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                                </button>

                                <!-- Notification Dropdown -->
                                <div x-show="open" @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                     class="absolute right-0 top-full mt-3 w-[340px] sm:w-[380px] rounded-2xl border border-border/60 bg-card/95 backdrop-blur-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] overflow-hidden z-50"
                                     style="display: none;">

                                    <!-- Header -->
                                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-border/40 bg-muted/20">
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-7 w-7 items-center justify-center rounded-lg gradient-primary shadow-sm">
                                                <i data-lucide="bell-ring" class="h-3.5 w-3.5 text-white"></i>
                                            </div>
                                            <h4 class="text-xs font-bold uppercase tracking-widest text-foreground">Thông báo</h4>
                                        </div>
                                        <button x-show="unreadCount > 0" @click="markAllRead()" class="text-[10px] font-bold text-primary hover:text-primary/80 transition-colors cursor-pointer uppercase tracking-wider">
                                            Đọc tất cả
                                        </button>
                                    </div>

                                    <!-- Notification List -->
                                    <div class="max-h-[360px] overflow-y-auto overscroll-contain" style="scrollbar-width: thin;">
                                        <template x-if="notifications.length === 0">
                                            <div class="flex flex-col items-center justify-center py-10 px-6 text-center">
                                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-muted/30 border border-border/30 mb-3">
                                                    <i data-lucide="bell-off" class="h-6 w-6 text-muted-foreground/50"></i>
                                                </div>
                                                <p class="text-xs font-semibold text-muted-foreground">Chưa có thông báo nào</p>
                                                <p class="text-[10px] text-muted-foreground/60 mt-1">Thông báo từ Admin sẽ hiển thị ở đây</p>
                                            </div>
                                        </template>

                                        <template x-for="n in notifications" :key="n.id">
                                            <a :href="n.link || '#'" @click="markRead(n)"
                                               class="flex items-start gap-3 px-5 py-3.5 border-b border-border/20 transition-all hover:bg-muted/20 cursor-pointer"
                                               :class="!n.is_read ? 'bg-primary/[0.03]' : ''">
                                                <!-- Icon -->
                                                <div class="shrink-0 mt-0.5">
                                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl shadow-sm border"
                                                         :class="n.type === 'admin_reply' ? 'bg-blue-500/10 border-blue-500/20 text-blue-500' : 'bg-amber-500/10 border-amber-500/20 text-amber-500'">
                                                        <i :data-lucide="n.type === 'admin_reply' ? 'message-circle' : 'heart'" class="h-4 w-4"></i>
                                                    </div>
                                                </div>
                                                <!-- Content -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <p class="text-xs font-bold text-foreground truncate" x-text="n.title"></p>
                                                        <span x-show="!n.is_read" class="shrink-0 h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                                                    </div>
                                                    <p class="text-[11px] text-muted-foreground mt-0.5 line-clamp-2 leading-relaxed" x-text="n.message"></p>
                                                    <span class="text-[9px] text-muted-foreground/60 font-medium mt-1 block" x-text="timeAgo(n.created_at)"></span>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>

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
                <a href="{{ route('menu') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs('menu') ? 'bg-primary text-primary-foreground shadow-glow' : 'hover:bg-sidebar-accent' }}">
                    <i data-lucide="salad" class="h-5 w-5"></i> Thực đơn
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

        function notificationBell() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,

                async fetchNotifications() {
                    try {
                        const res = await fetch('/notifications', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (res.ok) {
                            const data = await res.json();
                            this.notifications = data.notifications;
                            this.unreadCount = data.unread_count;
                            this.$nextTick(() => { if(typeof lucide !== 'undefined') lucide.createIcons(); });
                        }
                    } catch(e) { console.error('Notification fetch error:', e); }
                },

                async markRead(n) {
                    if (n.is_read) return;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.content;
                        await fetch(`/notifications/${n.id}/read`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                        });
                        n.is_read = true;
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    } catch(e) { console.error(e); }
                },

                async markAllRead() {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.content;
                        await fetch('/notifications/read-all', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                        });
                        this.notifications.forEach(n => n.is_read = true);
                        this.unreadCount = 0;
                    } catch(e) { console.error(e); }
                },

                timeAgo(dateStr) {
                    const date = new Date(dateStr);
                    const now = new Date();
                    const seconds = Math.floor((now - date) / 1000);
                    if (seconds < 60) return 'Vừa xong';
                    const minutes = Math.floor(seconds / 60);
                    if (minutes < 60) return minutes + ' phút trước';
                    const hours = Math.floor(minutes / 60);
                    if (hours < 24) return hours + ' giờ trước';
                    const days = Math.floor(hours / 24);
                    if (days < 7) return days + ' ngày trước';
                    return date.toLocaleDateString('vi-VN');
                }
            };
        }
    </script>

</body>
</html>
