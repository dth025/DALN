@extends('layouts.dashboard')

@section('title', 'Gói dịch vụ — HealthAI')

@section('content')
<div class="max-w-7xl mx-auto space-y-16 pb-16 animate-in fade-in duration-700">
    
    <!-- 1. HEADER -->
    <div class="mx-auto max-w-2xl text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Bảng giá dịch vụ</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight font-display md:text-4xl text-foreground">
            Chọn gói <span class="gradient-text">phù hợp với bạn</span>
        </h2>
        <p class="mt-4 text-sm text-muted-foreground font-medium">Hủy bất cứ lúc nào. Không phí ẩn. Nâng tầm sức khỏe ngay hôm nay.</p>
    </div>

    <!-- 2. PRICING GRID (EXACTLY LIKE LANDING PAGE) -->
    <div class="grid gap-8 md:grid-cols-3 mt-12">
        @php
            $plans = [
                [
                    'name' => 'Free',
                    'price' => '0',
                    'desc' => 'Cho người mới bắt đầu',
                    'features' => ['Theo dõi sức khỏe cơ bản', 'AI Chatbot 20 câu/ngày', 'Báo cáo tuần'],
                    'cta' => 'Đang sử dụng',
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
        <div class="relative rounded-[2.5rem] border p-10 shadow-soft backdrop-blur-xl transition-all hover:-translate-y-2 hover:shadow-elevated flex flex-col justify-between min-h-[500px] {{ $p['popular'] ? 'border-primary/50 bg-card/80 scale-105 z-10' : 'border-border/50 bg-card/60' }}">
            @if($p['popular'])
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 rounded-full gradient-primary px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-white shadow-glow">
                    Phổ biến
                </div>
            @endif
            
            <div>
                <h3 class="text-xl font-black text-foreground tracking-tight">{{ $p['name'] }}</h3>
                <p class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mt-1">{{ $p['desc'] }}</p>
                
                <div class="mt-8 flex items-baseline gap-1">
                    <span class="text-5xl font-black tracking-tighter text-foreground font-display">{{ $p['price'] }}</span>
                    <span class="text-sm font-bold text-muted-foreground">đ/tháng</span>
                </div>

                <ul class="mt-10 space-y-4">
                    @foreach($p['features'] as $f)
                    <li class="flex items-start gap-3 text-sm">
                        <i data-lucide="check" class="mt-0.5 h-4 w-4 shrink-0 text-success"></i>
                        <span class="text-foreground/90 font-medium">{{ $f }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <button class="mt-10 block w-full rounded-2xl py-4 text-center text-xs font-black uppercase tracking-widest transition-all hover:scale-[1.02] active:scale-95 {{ $p['popular'] ? 'gradient-primary text-white shadow-glow' : 'border-2 border-border bg-background/60 text-foreground' }}">
                {{ $p['cta'] }}
            </button>
        </div>
        @endforeach
    </div>

    <!-- 3. COMPACT & BEAUTIFUL REVIEWS SECTION -->
    <div class="pt-8 space-y-10">
        <div class="flex items-center justify-between border-b border-border pb-6">
            <div>
                <h3 class="text-xl font-bold text-foreground">Đánh giá cộng đồng</h3>
                <p class="text-xs text-muted-foreground font-medium mt-1">Hơn 2.500 người dùng đã tin tưởng và sử dụng</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 rounded-2xl bg-amber-400/10 border border-amber-400/20">
                <span class="text-lg font-bold text-amber-500">4.9</span>
                <div class="flex text-amber-400"><i data-lucide="star" class="h-4 w-4 fill-current"></i></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 items-start">
            <!-- Testimonials Grid (More Compact) -->
            <div class="grid gap-4">
                @php
                    $reviews = [
                        ['n' => 'Mai Linh', 't' => 'Giảm 5kg trong 2 tháng cực hiệu quả!', 'a' => 'https://i.pravatar.cc/100?img=5'],
                        ['n' => 'Trần Hùng', 't' => 'AI Chatbot trả lời cực kỳ thông minh.', 'a' => 'https://i.pravatar.cc/100?img=11'],
                        ['n' => 'Bs. Phương', 't' => 'Rất tiện lợi để theo dõi sức khỏe tại nhà.', 'a' => 'https://i.pravatar.cc/100?img=12'],
                    ];
                @endphp
                @foreach($reviews as $r)
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-card/40 border border-border/50 hover:border-primary/30 transition-all group">
                    <img src="{{ $r['a'] }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-border group-hover:ring-primary/20">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-0.5">
                            <span class="text-xs font-bold text-foreground">{{ $r['n'] }}</span>
                            <div class="flex text-amber-400 scale-75 origin-right"><i data-lucide="star" class="h-4 w-4 fill-current"></i><i data-lucide="star" class="h-4 w-4 fill-current"></i><i data-lucide="star" class="h-4 w-4 fill-current"></i><i data-lucide="star" class="h-4 w-4 fill-current"></i><i data-lucide="star" class="h-4 w-4 fill-current"></i></div>
                        </div>
                        <p class="text-[11px] text-muted-foreground font-medium leading-relaxed italic">"{{ $r['t'] }}"</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Compact Submission Form -->
            <div class="bg-card/60 border border-border rounded-[2rem] p-6 shadow-soft relative overflow-hidden group">
                <div class="absolute inset-0 gradient-primary opacity-[0.02]"></div>
                <form x-data="{ rating: 5, hover: 0 }" class="relative z-10 space-y-5">
                    <h4 class="text-sm font-bold text-center uppercase tracking-widest text-foreground">Chia sẻ trải nghiệm</h4>
                    
                    <div class="flex justify-center gap-1.5">
                        @foreach([1,2,3,4,5] as $s)
                            <button type="button" @click="rating = {{ $s }}" @mouseenter="hover = {{ $s }}" @mouseleave="hover = 0" class="transition-transform hover:scale-125">
                                <i data-lucide="star" class="h-7 w-7 transition-colors" :class="(hover || rating) >= {{ $s }} ? 'fill-amber-400 text-amber-400' : 'text-muted-foreground/20'"></i>
                            </button>
                        @endforeach
                    </div>

                    <textarea rows="3" placeholder="Viết cảm nhận của bạn..." class="w-full bg-muted/20 border border-border focus:border-primary/30 p-4 rounded-xl text-xs font-bold outline-none transition-all resize-none"></textarea>
                    
                    <div class="flex justify-center">
                        <button type="submit" class="group flex items-center gap-2 rounded-xl gradient-primary px-8 py-2.5 text-[10px] font-black uppercase tracking-widest text-white shadow-glow transition-all hover:scale-[1.05] active:scale-95">
                            Gửi phản hồi
                            <i data-lucide="send" class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
