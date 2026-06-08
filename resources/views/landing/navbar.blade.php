<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     class="fixed w-full z-50 transition-all duration-500 py-4"
     :class="{ 'py-2': scrolled }">
    
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between rounded-[2rem] px-6 transition-all duration-500 border border-border/40"
             :class="{ 'bg-sidebar/90 backdrop-blur-2xl shadow-elevated border-border/60': scrolled, 'bg-sidebar/40 backdrop-blur-md': !scrolled }">
            
            <!-- Logo Section -->
            <div class="flex items-center gap-10">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl gradient-primary shadow-glow group-hover:scale-105 transition-transform">
                        <i data-lucide="heart-pulse" class="h-5 w-5 text-white"></i>
                    </div>
                    <div class="block">
                        <h1 class="text-base font-black tracking-tighter text-foreground">Health<span class="gradient-text">AI</span></h1>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-muted-foreground leading-none">Smart Health</p>
                    </div>
                </a>

                <!-- Horizontal Navigation -->
                <nav class="hidden lg:flex items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-full px-5 py-2.5 text-[10px] font-black uppercase tracking-widest transition-all border {{ request()->routeIs('dashboard') ? 'bg-primary/10 border-primary/30 text-primary shadow-glow-sm' : 'border-transparent text-muted-foreground hover:bg-sidebar-accent hover:border-border hover:text-foreground' }}">
                            <i data-lucide="layout-dashboard" class="h-3.5 w-3.5"></i> Dashboard
                        </a>
                        <a href="{{ route('health') }}" class="flex items-center gap-2 rounded-full px-5 py-2.5 text-[10px] font-black uppercase tracking-widest transition-all border {{ request()->routeIs('health') ? 'bg-primary/10 border-primary/30 text-primary shadow-glow-sm' : 'border-transparent text-muted-foreground hover:bg-sidebar-accent hover:border-border hover:text-foreground' }}">
                            <i data-lucide="activity" class="h-3.5 w-3.5"></i> Theo dõi
                        </a>
                        <a href="{{ route('chatbot') }}" class="flex items-center gap-2 rounded-full px-5 py-2.5 text-[10px] font-black uppercase tracking-widest transition-all border border-transparent text-muted-foreground hover:bg-sidebar-accent hover:border-border hover:text-foreground">
                            <i data-lucide="bot" class="h-3.5 w-3.5"></i> AI Chatbot
                        </a>
                        <a href="{{ route('pricing') }}" class="flex items-center gap-2 rounded-full px-5 py-2.5 text-[10px] font-black uppercase tracking-widest transition-all border border-transparent text-muted-foreground hover:bg-sidebar-accent hover:border-border hover:text-foreground">
                            <i data-lucide="credit-card" class="h-3.5 w-3.5"></i> Bảng giá
                        </a>
                    @else
                        <a href="#features" class="px-5 py-2.5 rounded-full border border-transparent text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:bg-sidebar-accent hover:border-border hover:text-primary transition-all">Tính năng</a>
                        <a href="#how" class="px-5 py-2.5 rounded-full border border-transparent text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:bg-sidebar-accent hover:border-border hover:text-primary transition-all">Cách hoạt động</a>
                        <a href="#pricing" class="px-5 py-2.5 rounded-full border border-transparent text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:bg-sidebar-accent hover:border-border hover:text-primary transition-all">Gói dịch vụ</a>
                        <a href="#reviews" class="px-5 py-2.5 rounded-full border border-transparent text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:bg-sidebar-accent hover:border-border hover:text-primary transition-all">Đánh giá</a>
                    @endauth
                </nav>
            </div>

            <!-- Actions Section -->
            <div class="flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-3">
                        <button onclick="toggleDark()" class="flex h-9 w-9 items-center justify-center rounded-xl border border-border bg-card/40 text-muted-foreground transition-all hover:border-primary/40 hover:text-primary">
                            <i data-lucide="moon" class="h-4 w-4 block dark:hidden"></i>
                            <i data-lucide="sun" class="h-4 w-4 hidden dark:block"></i>
                        </button>

                        <div class="relative group cursor-pointer">
                            <div class="flex items-center gap-2 rounded-full border border-border bg-card/40 p-1 pr-3 transition-all hover:border-primary/30 hover:bg-card/60 shadow-sm">
                                <div class="relative shrink-0">
                                        <img src="{{ Auth::user()->avatar_url }}" alt="user" class="h-8 w-8 rounded-full object-cover ring-1 ring-border">
                                </div>
                                <i data-lucide="chevron-down" class="h-4 w-4 text-muted-foreground/60 transition-transform group-hover:translate-y-0.5"></i>
                            </div>
                            <!-- Dropdown -->
                            <div class="absolute right-0 top-full mt-3 w-48 origin-top-right rounded-2xl border border-border bg-card p-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold hover:bg-muted transition-colors text-foreground">
                                    <i data-lucide="user" class="h-3.5 w-3.5 text-primary"></i> Hồ sơ cá nhân
                                </a>
                                <div class="my-1 border-t border-border/50"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold text-destructive hover:bg-destructive/10 transition-colors">
                                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <button onclick="toggleDark()" class="flex h-9 w-9 items-center justify-center rounded-xl border border-border/60 text-muted-foreground transition-colors hover:bg-accent/50 hover:text-foreground">
                            <i data-lucide="moon" class="h-4 w-4 block dark:hidden"></i>
                            <i data-lucide="sun" class="h-4 w-4 hidden dark:block"></i>
                        </button>
                        <a href="{{ route('login') }}" class="hidden md:flex items-center justify-center h-10 px-5 rounded-xl border border-border/60 bg-muted/20 text-[10px] font-black uppercase tracking-widest text-foreground hover:bg-muted/40 hover:border-primary/30 transition-all">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 h-10 px-6 rounded-xl gradient-primary text-[10px] font-black uppercase tracking-widest text-white shadow-glow transition-transform hover:scale-[1.03]">
                            Bắt đầu <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="lg:hidden flex h-10 w-10 items-center justify-center rounded-xl gradient-primary text-white shadow-glow">
                    <i data-lucide="menu" class="h-5 w-5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="lg:hidden fixed inset-x-4 top-24 z-30 bg-sidebar/95 backdrop-blur-2xl rounded-[2rem] border border-border p-6 shadow-elevated">
        <nav class="flex flex-col gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-black uppercase tracking-widest {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-glow-sm' : 'text-foreground hover:bg-primary/10 hover:text-primary' }} transition-all border border-transparent {{ request()->routeIs('dashboard') ? 'border-primary/20' : '' }}">
                    <i data-lucide="layout-dashboard" class="h-5 w-5"></i> Dashboard
                </a>
                <a href="{{ route('health') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-black uppercase tracking-widest {{ request()->routeIs('health') ? 'bg-primary text-white shadow-glow-sm' : 'text-foreground hover:bg-primary/10 hover:text-primary' }} transition-all border border-transparent {{ request()->routeIs('health') ? 'border-primary/20' : '' }}">
                    <i data-lucide="activity" class="h-5 w-5"></i> Theo dõi
                </a>
                <a href="{{ route('chatbot') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-black uppercase tracking-widest {{ request()->routeIs('chatbot') ? 'bg-primary text-white shadow-glow-sm' : 'text-foreground hover:bg-primary/10 hover:text-primary' }} transition-all border border-transparent {{ request()->routeIs('chatbot') ? 'border-primary/20' : '' }}">
                    <i data-lucide="bot" class="h-5 w-5"></i> AI Chatbot
                </a>
                <a href="{{ route('workout') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-black uppercase tracking-widest {{ request()->routeIs('workout') ? 'bg-primary text-white shadow-glow-sm' : 'text-foreground hover:bg-primary/10 hover:text-primary' }} transition-all border border-transparent {{ request()->routeIs('workout') ? 'border-primary/20' : '' }}">
                    <i data-lucide="dumbbell" class="h-5 w-5"></i> Luyện tập
                </a>
                <a href="{{ route('pricing') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-black uppercase tracking-widest {{ request()->routeIs('pricing') ? 'bg-primary text-white shadow-glow-sm' : 'text-foreground hover:bg-primary/10 hover:text-primary' }} transition-all border border-transparent {{ request()->routeIs('pricing') ? 'border-primary/20' : '' }}">
                    <i data-lucide="credit-card" class="h-5 w-5"></i> Bảng giá
                </a>
                <div class="my-2 border-t border-border/50"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-black uppercase tracking-widest text-destructive hover:bg-destructive/10 transition-all">
                        <i data-lucide="log-out" class="h-5 w-5"></i> Đăng xuất
                    </button>
                </form>
            @else
                <a href="#features" @click="open = false" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-black uppercase tracking-widest text-foreground hover:bg-primary/10 hover:text-primary transition-all">Tính năng</a>
                <a href="#pricing" @click="open = false" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-black uppercase tracking-widest text-foreground hover:bg-primary/10 hover:text-primary transition-all">Bảng giá</a>
                <a href="#reviews" @click="open = false" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-black uppercase tracking-widest text-foreground hover:bg-primary/10 hover:text-primary transition-all">Đánh giá</a>
                <div class="my-2 border-t border-border/50"></div>
                <a href="{{ route('login') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-black uppercase tracking-widest text-foreground hover:bg-primary/10 hover:text-primary transition-all border border-border/20 bg-muted/10">
                    <i data-lucide="log-in" class="h-5 w-5 text-primary"></i> Đăng nhập
                </a>
                <a href="{{ route('register') }}" class="mt-2 flex items-center justify-center rounded-2xl gradient-primary py-4 text-[10px] font-black uppercase tracking-widest text-white shadow-glow">
                    Bắt đầu miễn phí
                </a>
            @endauth
        </nav>
    </div>
</nav>
