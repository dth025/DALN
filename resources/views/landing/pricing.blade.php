<section id="pricing" class="mx-auto max-w-7xl px-4 py-20 md:px-6 md:py-28">
    <div class="mx-auto max-w-2xl text-center mb-14">
        <p class="text-xs font-semibold uppercase tracking-widest text-primary">
            Bảng giá
        </p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight font-display md:text-4xl text-foreground">
            Chọn gói <span class="gradient-text">phù hợp với bạn</span>
        </h2>
        <p class="mt-4 text-base text-muted-foreground">
            Hủy bất cứ lúc nào. Không phí ẩn.
        </p>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        @php
            $plans = [
                [
                    'name' => 'Free',
                    'price' => '0',
                    'desc' => 'Cho người mới bắt đầu',
                    'features' => ['Theo dõi sức khỏe cơ bản', 'AI Chatbot 20 câu/ngày', 'Báo cáo tuần'],
                    'cta' => 'Bắt đầu miễn phí',
                    'popular' => false,
                ],
                [
                    'name' => 'Premium',
                    'price' => '99K',
                    'desc' => 'Phổ biến nhất',
                    'features' => [
                        'Tất cả tính năng Free',
                        'AI không giới hạn',
                        'Thực đơn & lịch tập cá nhân hóa',
                        'Báo cáo chuyên sâu hằng ngày',
                        'Đặt lịch bác sĩ ưu tiên',
                    ],
                    'cta' => 'Nâng cấp ngay',
                    'popular' => true,
                ],
                [
                    'name' => 'Family',
                    'price' => '199K',
                    'desc' => 'Cho cả gia đình (4 người)',
                    'features' => ['Tất cả tính năng Premium', 'Quản lý 4 thành viên', 'Bác sĩ riêng 24/7'],
                    'cta' => 'Chọn gói',
                    'popular' => false,
                ],
            ];
        @endphp

        @foreach($plans as $p)
        <div class="relative rounded-3xl border p-7 shadow-soft backdrop-blur-xl transition-all hover:-translate-y-1 hover:shadow-elevated {{ $p['popular'] ? 'border-primary/50 bg-card/80 scale-105 z-10' : 'border-border/50 bg-card/60' }}">
            @if($p['popular'])
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full gradient-primary px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-primary-foreground shadow-glow">
                    Phổ biến
                </div>
            @endif
            <h3 class="text-lg font-bold text-foreground">{{ $p['name'] }}</h3>
            <p class="text-xs text-muted-foreground">{{ $p['desc'] }}</p>
            <div class="mt-5 flex items-baseline gap-1">
                <span class="text-4xl font-bold font-display text-foreground">{{ $p['price'] }}</span>
                <span class="text-sm text-muted-foreground">đ/tháng</span>
            </div>
            <ul class="mt-6 space-y-3">
                @foreach($p['features'] as $f)
                <li class="flex items-start gap-2 text-sm">
                    <i data-lucide="check" class="mt-0.5 h-4 w-4 shrink-0 text-success"></i>
                    <span class="text-foreground/90">{{ $f }}</span>
                </li>
                @endforeach
            </ul>
            <a href="{{ Auth::check() ? route('pricing') : route('login') }}" class="mt-7 block w-full rounded-xl py-2.5 text-center text-sm font-semibold transition-transform hover:scale-[1.02] {{ $p['popular'] ? 'gradient-primary text-primary-foreground shadow-glow' : 'border border-border bg-background/60 text-foreground' }}">
                {{ $p['cta'] }}
            </a>
        </div>
        @endforeach
    </div>
</section>
