<footer class="mt-24 border-t border-border/40 bg-gradient-to-b from-transparent to-muted/30 dark:to-muted/10 relative overflow-hidden">
    <!-- Hiệu ứng viền sáng mờ phía trên (Glow effect) -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-[1px] bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>
    <!-- Lưới nền chìm -->
    <div class="absolute inset-0 grid-bg opacity-[0.03] dark:opacity-[0.05] -z-10"></div>
    
    <div class="mx-auto max-w-7xl px-6 py-16">
        <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-5 xl:gap-16">
            <!-- Brand Section -->
            <div class="lg:col-span-2 space-y-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group w-fit">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl gradient-primary shadow-glow group-hover:scale-[1.05] transition-transform duration-300">
                        <i data-lucide="heart-pulse" class="h-6 w-6 text-white"></i>
                    </div>
                    <p class="text-2xl font-extrabold tracking-tight text-foreground">
                        Health<span class="text-primary">AI</span>
                    </p>
                </a>
                
                <p class="max-w-xs text-sm leading-relaxed text-muted-foreground font-medium">
                    Tiên phong ứng dụng trí tuệ nhân tạo để cá nhân hóa hành trình chăm sóc sức khỏe cho mọi nhà.
                </p>
                
                <!-- Social Icons -->
                <div class="flex items-center gap-3 pt-2">
                    @foreach(['facebook', 'twitter', 'instagram', 'linkedin'] as $social)
                        <a href="#" class="group flex h-10 w-10 items-center justify-center rounded-xl bg-card border border-border/50 text-muted-foreground transition-all duration-300 hover:bg-primary/10 hover:border-primary/40 hover:text-primary hover:-translate-y-1 shadow-sm">
                            <x-dynamic-component :component="'icons.' . $social" class="h-4 w-4" />
                        </a>
                    @endforeach
                </div>
            </div>
            
            @php
                $footerLinks = [
                    ['title' => 'Khám phá', 'links' => [
                        ['n' => 'Tính năng nổi bật', 'url' => '#features'],
                        ['n' => 'Gói dịch vụ', 'url' => route('pricing')],
                        ['n' => 'Trợ lý AI Chatbot', 'url' => route('chatbot')],
                        ['n' => 'Chương trình tập luyện', 'url' => route('workout')]
                    ]],
                    ['title' => 'Hỗ trợ', 'links' => [
                        ['n' => 'Về chúng tôi', 'url' => '#'],
                        ['n' => 'Trung tâm trợ giúp', 'url' => '#'],
                        ['n' => 'Liên hệ hỗ trợ', 'url' => '#'],
                        ['n' => 'Blog sức khỏe', 'url' => '#']
                    ]],
                    ['title' => 'Pháp lý', 'links' => [
                        ['n' => 'Điều khoản sử dụng', 'url' => '#'],
                        ['n' => 'Chính sách bảo mật', 'url' => '#'],
                        ['n' => 'Quy định Cookie', 'url' => '#'],
                        ['n' => 'Bảo mật dữ liệu y tế', 'url' => '#']
                    ]],
                ];
            @endphp

            <!-- Links Sections -->
            @foreach($footerLinks as $section)
            <div class="space-y-6">
                <h4 class="text-sm font-bold text-foreground tracking-wide">{{ $section['title'] }}</h4>
                <ul class="space-y-3.5">
                    @foreach($section['links'] as $link)
                    <li>
                        <a href="{{ $link['url'] }}" class="text-sm font-medium text-muted-foreground transition-all duration-200 hover:text-primary inline-flex items-center gap-2 group">
                            <span class="w-1 h-1 rounded-full bg-primary/0 transition-all duration-200 group-hover:bg-primary"></span>
                            <span class="group-hover:translate-x-1 transition-transform duration-200">{{ $link['n'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>

        <!-- Bottom Copyright -->
        <div class="mt-16 flex flex-col items-center justify-between gap-6 border-t border-border/40 pt-8 sm:flex-row">
            <p class="text-sm text-muted-foreground font-medium">
                © {{ date('Y') }} <span class="font-bold text-foreground">HealthAI</span>. All rights reserved.
            </p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors">Trạng thái hệ thống</a>
                <a href="#" class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors">Quyền riêng tư</a>
            </div>
        </div>
    </div>
</footer>
