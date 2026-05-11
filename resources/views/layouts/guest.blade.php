<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap"
          rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-float-slow {
            animation: float-slow 8s ease-in-out infinite;
        }
    </style>
</head>

<body class="font-sans text-foreground antialiased bg-background relative min-h-screen overflow-x-hidden">

    <!-- Background -->
    <div class="pointer-events-none fixed inset-0 -z-10"
         style="background: var(--gradient-hero)">
    </div>

    <div class="pointer-events-none fixed inset-0 -z-10 grid-bg opacity-40"></div>

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 relative z-10">

        <div class="flex w-full max-w-6xl h-full min-h-[650px]
                    rounded-[2.5rem] overflow-hidden glass
                    border border-white/20 shadow-2xl">

            <!-- LEFT SIDE -->
            <div class="hidden lg:block w-1/2 relative overflow-hidden bg-[#0a0a0a]">

                <!-- Animated Background Image -->
                <div class="absolute inset-0 w-full h-full animate-float" style="transition: transform 0.5s ease-out;">
                    <img src="{{ asset('img/dangky.png') }}"
                         alt="HealthAI Life"
                         class="absolute inset-0 w-full h-full object-cover">
                </div>

                <!-- Floating Data Cards -->
                <div class="absolute top-20 left-10 z-20 animate-float-slow" style="animation-delay: -2s">
                    <div class="bg-black/60 backdrop-blur-xl p-4 rounded-2xl border border-white/20 shadow-[0_20px_50px_rgba(0,0,0,0.3)]">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-success/30 flex items-center justify-center border border-success/30">
                                <i data-lucide="activity" class="h-5 w-5 text-success"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-black tracking-widest text-success/90">Nhịp tim</p>
                                <p class="text-xl font-black text-white">72 <span class="text-xs font-bold text-white/70">BPM</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="absolute top-44 right-10 z-20 animate-float" style="animation-delay: -4s">
                    <div class="bg-black/60 backdrop-blur-xl p-4 rounded-2xl border border-white/20 shadow-[0_20px_50px_rgba(0,0,0,0.3)]">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-primary/30 flex items-center justify-center border border-primary/30">
                                <i data-lucide="brain" class="h-5 w-5 text-primary-foreground"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-black tracking-widest text-primary-foreground/90">AI Phân tích</p>
                                <p class="text-sm font-black text-white">Sức khỏe Tốt</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overlay -->
                <div class="absolute inset-0
                            bg-gradient-to-t
                            from-black/90
                            via-black/20
                            to-transparent
                            pointer-events-none z-10">
                </div>

                <!-- Content -->
                <div class="absolute bottom-12 left-12 right-12
                            text-white z-20 pointer-events-none">

                    <div class="flex h-12 w-12 items-center justify-center
                                rounded-2xl bg-primary/20 backdrop-blur-md
                                mb-6 border border-white/20 shadow-xl animate-bounce" style="animation-duration: 3s">

                        <i data-lucide="sparkles"
                           class="h-6 w-6 text-yellow-300"></i>
                    </div>

                    <h3 class="text-4xl font-bold font-display
                               leading-tight drop-shadow-lg">
                        HealthAI <span class="text-primary font-black">X</span>
                    </h3>

                    <p class="mt-4 text-white/80 text-base leading-relaxed
                               max-w-sm drop-shadow-sm font-medium">
                        Tham gia cuộc cách mạng chăm sóc sức khỏe bằng Trí tuệ Nhân tạo.
                    </p>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="w-full lg:w-1/2 flex flex-col items-center
                        justify-center py-12 px-8 sm:px-12
                        bg-card/20 backdrop-blur-md overflow-y-auto">

                <!-- Logo -->
                <div class="mb-9 text-center">

                    <a href="/" class="flex flex-col items-center gap-3 group">

                        <div class="flex h-14 w-14 items-center justify-center
                                    rounded-2xl gradient-primary shadow-glow
                                    group-hover:scale-105
                                    transition-transform duration-300">

                            <i data-lucide="heart-pulse"
                               class="h-7 w-7 text-primary-foreground"></i>
                        </div>

                        <h1 class="text-xl font-bold tracking-tight
                                   text-foreground font-display">

                            Health<span class="gradient-text">AI</span>
                        </h1>
                    </a>
                </div>

                <!-- Slot -->
                <div class="w-full sm:max-w-md">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <p class="mt-10 text-[10px]
                          text-muted-foreground font-medium
                          uppercase tracking-widest text-center">

                    © 2026 HealthAI · Smart AI Health Hub
                </p>
            </div>
        </div>
    </div>

    <!-- Magnifier Script Removed -->

    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>