<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Admin — HealthAI</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .glass-login {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .shadow-glow-purple {
            box-shadow: 0 0 40px -10px rgba(168, 85, 247, 0.25);
        }
        .gradient-primary {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }
    </style>
</head>
<body class="bg-background text-foreground antialiased min-h-screen relative overflow-hidden flex items-center justify-center p-4">
    
    <!-- Background Glow Patterns -->
    <div class="pointer-events-none fixed inset-0 -z-10" style="background: var(--gradient-hero)"></div>
    <div class="pointer-events-none fixed inset-0 -z-10 grid-bg opacity-30"></div>
    <div class="pointer-events-none fixed -top-40 -right-40 -z-10 h-[500px] w-[500px] rounded-full bg-primary/15 blur-[120px]"></div>
    <div class="pointer-events-none fixed -bottom-40 -left-40 -z-10 h-[500px] w-[500px] rounded-full bg-accent/15 blur-[120px]"></div>

    <!-- Login Card -->
    <div class="w-full max-w-md glass-login rounded-[2.5rem] p-8 sm:p-10 shadow-glow-purple relative overflow-hidden animate-fade-in-up">
        
        <!-- Subtle Glow Circle inside Card -->
        <div class="absolute -right-20 -top-20 h-44 w-44 rounded-full bg-purple-500/10 blur-3xl pointer-events-none"></div>

        <div class="text-center mb-8">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group mb-5">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl gradient-primary shadow-glow group-hover:scale-105 transition-transform">
                    <i data-lucide="heart-pulse" class="h-6 w-6 text-white animate-pulse"></i>
                </div>
                <div class="text-left">
                    <h1 class="text-lg font-black tracking-tight text-white leading-none">Health<span class="gradient-text">AI</span></h1>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-primary/80 mt-1">Smart Health Admin</p>
                </div>
            </a>
            <h2 class="text-xl font-bold text-white tracking-tight">Hệ thống Quản trị viên</h2>
            <p class="text-xs text-muted-foreground mt-2">Đăng nhập tài khoản Admin cấp cao để điều phối hệ thống.</p>
        </div>

        <!-- Form Submission -->
        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground pl-1">Email quản trị</label>
                <div class="relative">
                    <i data-lucide="mail" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                    <input type="email" name="email" value="{{ old('email', 'admin@healthai.vn') }}" required placeholder="admin@healthai.vn" 
                           class="h-11 w-full rounded-2xl border border-border/50 bg-background/50 pl-11 pr-4 text-xs font-semibold outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-white">
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex justify-between items-center pl-1">
                    <label class="text-xs font-bold uppercase tracking-widest text-muted-foreground">Mật khẩu</label>
                </div>
                <div class="relative">
                    <i data-lucide="lock" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                    <input type="password" name="password" value="admin123" required placeholder="••••••••" 
                           class="h-11 w-full rounded-2xl border border-border/50 bg-background/50 pl-11 pr-4 text-xs font-semibold outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-white">
                </div>
            </div>

            <!-- Display Errors if any -->
            @if ($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs rounded-2xl p-3.5 flex items-start gap-2.5">
                <i data-lucide="alert-circle" class="h-4 w-4 shrink-0 mt-0.5"></i>
                <span class="font-semibold">{{ $errors->first() }}</span>
            </div>
            @endif

            <!-- Submit Button -->
            <button type="submit" class="w-full h-12 rounded-2xl gradient-primary font-bold text-sm text-white shadow-glow hover:scale-[1.02] transition-transform flex items-center justify-center gap-2 mt-2">
                Đăng nhập quản trị
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </button>
        </form>

        <!-- Static Credentials Display Badge -->
        <div class="mt-8 border-t border-border/20 pt-6">
            <div class="rounded-2xl bg-primary/10 border border-primary/20 p-4">
                <p class="text-[10px] font-black text-primary uppercase tracking-widest flex items-center gap-1.5 mb-2.5">
                    <i data-lucide="shield-check" class="h-4 w-4"></i>
                    Tài khoản Admin Demo Cố Định
                </p>
                <div class="space-y-1.5 text-xs font-semibold text-muted-foreground">
                    <div class="flex justify-between">
                        <span>Email:</span>
                        <span class="text-white select-all">admin@healthai.vn</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Mật khẩu:</span>
                        <span class="text-white select-all">admin123</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Init Lucide
        lucide.createIcons();
    </script>
</body>
</html>
