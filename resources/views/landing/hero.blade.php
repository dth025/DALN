<section class="relative mx-auto max-w-7xl px-4 pt-16 pb-20 md:px-6 md:pt-32 md:pb-28">
    <div class="grid items-center gap-12 lg:grid-cols-2">
        <div class="reveal-on-scroll">
            <div class="inline-flex items-center gap-1.5 rounded-full border border-primary/30 bg-primary/10 px-3 py-1 text-xs font-medium text-primary backdrop-blur-md">
                <i data-lucide="sparkles" class="h-3 w-3"></i> Powered by GPT Health AI
            </div>
            <h1 class="mt-5 flex flex-col gap-2 md:gap-3 text-4xl font-extrabold leading-tight tracking-tight font-display sm:text-5xl lg:text-[3.25rem] xl:text-6xl text-foreground">
                <span>Sức khỏe của bạn,</span>
                <span class="pl-0 sm:pl-4 md:pl-8 lg:pl-10 xl:pl-[2.5rem] bg-gradient-to-r from-primary via-accent to-primary animate-gradient-text bg-clip-text text-transparent drop-shadow-sm whitespace-nowrap">
                    được dẫn dắt bởi AI
                </span>
            </h1>
            <p class="mt-5 max-w-xl text-base leading-relaxed text-muted-foreground md:text-lg">
                HealthAI phân tích chỉ số cơ thể, gợi ý thực đơn, lịch tập và nhắc lịch khám — tất cả trong một nền tảng thông minh và đẹp mắt.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ Auth::check() ? route('dashboard') : route('login') }}" class="group inline-flex items-center gap-2 rounded-2xl gradient-primary px-6 py-3 text-sm font-semibold text-primary-foreground shadow-glow transition-transform hover:scale-[1.03]">
                    {{ Auth::check() ? 'Vào Dashboard' : 'Bắt đầu miễn phí' }}
                    <i data-lucide="{{ Auth::check() ? 'layout-dashboard' : 'arrow-right' }}" class="h-4 w-4 transition-transform group-hover:translate-x-1"></i>
                </a>
                <a href="{{ Auth::check() ? '#features' : '#features' }}" class="inline-flex items-center gap-2 rounded-2xl border border-border/60 bg-background/60 px-6 py-3 text-sm font-semibold text-foreground backdrop-blur-md transition-colors hover:bg-accent/50">
                    Xem tính năng
                </a>
            </div>

            <div class="mt-8 flex items-center gap-5 text-xs text-muted-foreground">
                <div class="flex -space-x-2">
                    @for($i=1; $i<=4; $i++)
                        <div class="h-8 w-8 rounded-full border-2 border-background bg-gradient-to-br from-primary to-accent"></div>
                    @endfor
                </div>
                <div>
                    <div class="flex items-center gap-0.5 text-amber-400">
                        @for($i=1; $i<=5; $i++)
                            <i data-lucide="star" class="h-3.5 w-3.5 fill-current"></i>
                        @endfor
                    </div>
                    <p class="mt-0.5">
                        <span class="font-semibold text-foreground">12.500+</span> người dùng tin tưởng
                    </p>
                </div>
            </div>
        </div>

        <!-- Hero visual -->
        <div class="relative lg:ml-10">
            <div class="absolute -inset-10 -z-10 animate-pulse-slow">
                <img src="/healthai_hero_visual_1778277319266.png" class="w-full h-full object-contain opacity-20 blur-2xl" alt="AI Background">
            </div>

            <div class="relative mx-auto max-w-md rounded-[2rem] border border-border/50 bg-card/70 p-5 shadow-elevated backdrop-blur-xl">
                <div class="absolute -inset-2 -z-10 rounded-[2.2rem] gradient-primary opacity-20 blur-2xl"></div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl gradient-primary shadow-glow">
                            <i data-lucide="heart-pulse" class="h-4 w-4 text-primary-foreground"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-foreground">Sức khỏe hôm nay</p>
                            <p class="text-[10px] text-muted-foreground">Cập nhật vừa xong</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-success/15 px-2 py-0.5 text-[10px] font-bold text-success">
                        Tốt
                    </span>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    @php
                        $metrics = [
                            ['label' => 'Nhịp tim', 'value' => '72', 'unit' => 'bpm', 'color' => 'text-rose-500'],
                            ['label' => 'Bước chân', 'value' => '8.4k', 'unit' => '/10k', 'color' => 'text-primary'],
                            ['label' => 'Giấc ngủ', 'value' => '7.5h', 'unit' => 'tốt', 'color' => 'text-violet-500'],
                            ['label' => 'Nước', 'value' => '1.8L', 'unit' => '/2L', 'color' => 'text-cyan-500'],
                        ];
                    @endphp
                    @foreach($metrics as $m)
                    <div class="rounded-2xl border border-border/40 bg-background/60 p-3">
                        <p class="text-[10px] uppercase tracking-wider text-muted-foreground">{{ $m['label'] }}</p>
                        <p class="mt-1 text-xl font-bold {{ $m['color'] }}">
                            {{ $m['value'] }}
                            <span class="ml-1 text-[10px] font-medium text-muted-foreground">{{ $m['unit'] }}</span>
                        </p>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 rounded-2xl border border-primary/20 bg-primary/5 p-3">
                    <div class="flex items-start gap-2">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg gradient-primary">
                            <i data-lucide="bot" class="h-3.5 w-3.5 text-primary-foreground"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-foreground">AI Insight</p>
                            <p class="mt-0.5 text-[11px] leading-relaxed text-muted-foreground">
                                Bạn đang ngủ ít hơn 0.5h so với tuần trước. Hãy đi ngủ trước 23:00 tối nay.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating cards -->
            <div class="absolute -left-8 top-10 hidden rounded-2xl border border-border/50 bg-card/80 p-3 shadow-soft backdrop-blur-md md:block animate-bounce-slow">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-success/20 text-success">
                        <i data-lucide="activity" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-muted-foreground">Streak</p>
                        <p class="text-sm font-bold text-foreground">12 ngày 🔥</p>
                    </div>
                </div>
            </div>

            <div class="absolute -right-8 bottom-10 hidden rounded-2xl border border-border/50 bg-card/80 p-3 shadow-soft backdrop-blur-md md:block animate-bounce-slow" style="animation-delay: 1s;">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/20 text-primary">
                        <i data-lucide="brain" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-muted-foreground">AI Score</p>
                        <p class="text-sm font-bold text-foreground">94/100</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes bounce-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.animate-bounce-slow {
    animation: bounce-slow 4s ease-in-out infinite;
}
</style>
