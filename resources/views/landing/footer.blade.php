<footer class="border-t-2 border-border bg-card/60 backdrop-blur-2xl mt-20 relative overflow-hidden">
    <div class="absolute inset-0 grid-bg opacity-[0.05] -z-10"></div>
    <div class="mx-auto max-w-7xl px-6 py-16">
        <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-5">
            <!-- Brand Section -->
            <div class="lg:col-span-2 space-y-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl gradient-primary shadow-glow group-hover:scale-105 transition-transform">
                        <i data-lucide="heart-pulse" class="h-6 w-6 text-white"></i>
                    </div>
                    <p class="text-2xl font-black tracking-tighter text-foreground">
                        Health<span class="gradient-text">AI</span>
                    </p>
                </a>
                <p class="max-w-xs text-sm font-bold leading-relaxed text-foreground/80">
                    Tiên phong trong việc ứng dụng trí tuệ nhân tạo để cá nhân hóa hành trình chăm sóc sức khỏe cho người Việt.
                </p>
                <div class="flex items-center gap-4">
                    @foreach(['facebook', 'twitter', 'instagram', 'linkedin'] as $social)
                        <a href="#" class="flex h-10 w-10 items-center justify-center rounded-xl bg-card border border-border text-foreground/70 transition-all hover:bg-primary/10 hover:text-primary hover:border-primary/30 shadow-sm">
                            <i data-lucide="{{ $social }}" class="h-4 w-4"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            
            @php
                $footerLinks = [
                    ['title' => 'Khám phá', 'links' => [
                        ['n' => 'Tính năng', 'url' => '#features'],
                        ['n' => 'Gói dịch vụ', 'url' => route('pricing')],
                        ['n' => 'AI Chatbot', 'url' => route('chatbot')],
                        ['n' => 'Luyện tập', 'url' => route('workout')]
                    ]],
                    ['title' => 'Hỗ trợ', 'links' => [
                        ['n' => 'Về chúng tôi', 'url' => '#'],
                        ['n' => 'Trung tâm trợ giúp', 'url' => '#'],
                        ['n' => 'Liên hệ', 'url' => '#'],
                        ['n' => 'Blog sức khỏe', 'url' => '#']
                    ]],
                    ['title' => 'Pháp lý', 'links' => [
                        ['n' => 'Điều khoản sử dụng', 'url' => '#'],
                        ['n' => 'Chính sách bảo mật', 'url' => '#'],
                        ['n' => 'Cookie Policy', 'url' => '#'],
                        ['n' => 'Xác thực dữ liệu', 'url' => '#']
                    ]],
                ];
            @endphp

            @foreach($footerLinks as $section)
            <div class="space-y-6">
                <p class="text-[11px] font-black uppercase tracking-[0.2em] text-foreground underline underline-offset-8 decoration-primary/30 decoration-2">{{ $section['title'] }}</p>
                <ul class="space-y-4">
                    @foreach($section['links'] as $link)
                    <li>
                        <a href="{{ $link['url'] }}" class="text-sm font-bold text-foreground/70 transition-colors hover:text-primary">{{ $link['n'] }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>

        <div class="mt-16 flex flex-col items-center justify-between gap-6 border-t-2 border-border pt-8 md:flex-row">
            <p class="text-[11px] font-black text-foreground/60 uppercase tracking-widest">
                © 2026 <span class="text-primary font-black">HealthAI</span> · Smart Health Tracking System
            </p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-[11px] font-black text-foreground/60 hover:text-primary transition-colors uppercase tracking-widest">Status</a>
                <a href="#" class="text-[11px] font-black text-foreground/60 hover:text-primary transition-colors uppercase tracking-widest">Privacy</a>
                <a href="#" class="text-[11px] font-black text-foreground/60 hover:text-primary transition-colors uppercase tracking-widest">Terms</a>
            </div>
        </div>
    </div>
</footer>
