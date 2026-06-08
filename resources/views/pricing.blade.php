@extends('layouts.dashboard')

@section('title', 'Gói dịch vụ — HealthAI')

@section('content')
<div x-data="checkoutData()" class="max-w-7xl mx-auto space-y-16 pb-16 animate-in fade-in duration-700">
    
    <!-- 1. HEADER -->
    <div class="mx-auto max-w-2xl text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Bảng giá dịch vụ</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight font-display md:text-4xl text-foreground">
            Chọn gói <span class="gradient-text">phù hợp với bạn</span>
        </h2>
        <p class="mt-4 text-sm text-muted-foreground font-medium">Hủy bất cứ lúc nào. Không phí ẩn. Nâng tầm sức khỏe ngay hôm nay.</p>
    </div>

    <!-- 2. PRICING GRID -->
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
                    'action' => 'free'
                ],
                [
                    'name' => 'Premium',
                    'price' => '199K',
                    'raw_price' => 199000,
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
                    'action' => 'upgrade'
                ],
                [
                    'name' => 'Family',
                    'price' => '399K',
                    'raw_price' => 399000,
                    'desc' => 'Cho cả gia đình (4 người)',
                    'features' => ['Tất cả tính năng Premium', 'Quản lý 4 thành viên', 'Bác sĩ riêng 24/7'],
                    'cta' => 'Chọn gói',
                    'popular' => false,
                    'action' => 'upgrade'
                ],
            ];
        @endphp

        @foreach($plans as $p)
        <div class="relative rounded-[2.5rem] border p-10 shadow-soft backdrop-blur-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-elevated flex flex-col justify-between min-h-[500px] {{ $p['popular'] ? 'border-primary/50 bg-card/80 scale-105 z-10 shadow-[0_0_40px_-15px_rgba(99,102,241,0.25)]' : 'border-border/50 bg-card/60' }}">
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

            @if($p['name'] === $currentPlan)
                <button disabled class="mt-10 block w-full rounded-2xl py-4 text-center text-xs font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 cursor-not-allowed">
                    ✓ Đang sử dụng
                </button>
            @elseif($p['action'] === 'free')
                <button disabled class="mt-10 block w-full rounded-2xl py-4 text-center text-xs font-black uppercase tracking-widest bg-muted/30 text-muted-foreground/60 border border-border/20 cursor-not-allowed">
                    {{ $p['cta'] }}
                </button>
            @else
                <button @click="openCheckout('{{ $p['name'] }}', {{ $p['raw_price'] }})"
                        class="mt-10 block w-full rounded-2xl py-4 text-center text-xs font-black uppercase tracking-widest transition-all hover:scale-[1.02] active:scale-95 cursor-pointer {{ $p['popular'] ? 'gradient-primary text-white shadow-glow hover:shadow-[0_0_25px_rgba(168,85,247,0.4)]' : 'border-2 border-border bg-background/60 text-foreground hover:bg-muted/40' }}">
                    {{ $p['cta'] }}
                </button>
            @endif
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
                <span class="text-lg font-bold text-amber-500">
                    {{ number_format($reviews->avg('rating') ?: 4.9, 1) }}
                </span>
                <div class="flex text-amber-400"><i data-lucide="star" class="h-4 w-4 fill-current"></i></div>
            </div>
        </div>

        <div class="grid lg:grid-cols-12 gap-8 items-start">
            <!-- Testimonials Grid -->
            <div class="lg:col-span-7 space-y-6" x-data="{ showAllReviews: false }">
                @forelse($reviews as $r)
                <div x-show="showAllReviews || {{ $loop->index }} < 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 rounded-[2rem] bg-card/40 border border-border/50 hover:border-primary/30 transition-all space-y-4">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex items-center gap-3.5">
                            @if($r->user && $r->user->avatar)
                                <img src="{{ $r->user->avatar_url }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-border">
                            @elseif($r->user)
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($r->user->name) }}&background=6366f1&color=fff" class="h-10 w-10 rounded-full object-cover ring-2 ring-border">
                            @else
                                <img src="{{ $r->guest_avatar ?? 'https://ui-avatars.com/api/?name=Guest&background=64748b&color=fff' }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-border">
                            @endif
                            <div>
                                <h4 class="text-xs font-bold text-foreground">{{ $r->user ? $r->user->name : ($r->guest_name ?? 'Khách') }}</h4>
                                <span class="text-[9px] text-muted-foreground">{{ $r->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1.5">
                            <div class="flex text-amber-400 scale-75 origin-right">
                                @for($s=1; $s<=5; $s++)
                                <i data-lucide="star" class="h-4 w-4 {{ $s <= $r->rating ? 'fill-current' : 'opacity-30' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-xs text-foreground/80 leading-relaxed font-medium bg-muted/10 p-4 rounded-2xl border border-border/30">
                        "{{ $r->content }}"
                    </p>

                    <!-- Like / Dislike + Reply Form Trigger -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <div class="flex items-center gap-4">
                            <button @click="reactFeedback({{ $r->id }}, 'like')" class="flex items-center gap-1.5 text-muted-foreground hover:text-primary transition-all font-bold cursor-pointer group">
                                <i data-lucide="thumbs-up" class="h-4 w-4 group-hover:scale-110 transition-transform"></i>
                                <span id="like-count-{{ $r->id }}">{{ $r->likes_count }}</span>
                            </button>
                            <button @click="reactFeedback({{ $r->id }}, 'dislike')" class="flex items-center gap-1.5 text-muted-foreground hover:text-destructive transition-all font-bold cursor-pointer group">
                                <i data-lucide="thumbs-down" class="h-4 w-4 group-hover:scale-110 transition-transform"></i>
                                <span id="dislike-count-{{ $r->id }}">{{ $r->dislikes_count }}</span>
                            </button>
                        </div>
                        
                        <button @click="toggleReplyForm({{ $r->id }})" class="text-[10px] font-black uppercase tracking-widest text-primary hover:underline cursor-pointer">
                            Trả lời
                        </button>
                    </div>

                    <!-- Nesting Replies (Admin / User replies) -->
                    @if($r->replies->count() > 0)
                    <div class="mt-4 pl-6 border-l-2 border-border/50 space-y-4" x-data="{ showAllReplies: false }">
                        @foreach($r->replies as $reply)
                        <div x-show="showAllReplies || {{ $loop->index }} < 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="p-4 rounded-2xl bg-muted/10 border border-border/20 space-y-3">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex items-center gap-3">
                                    @if($reply->user && $reply->user->avatar)
                                        <img src="{{ $reply->user->avatar_url }}" class="h-8 w-8 rounded-full object-cover ring-1 ring-border">
                                    @elseif($reply->user)
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=6366f1&color=fff" class="h-8 w-8 rounded-full object-cover ring-1 ring-border">
                                    @else
                                        <img src="{{ $reply->guest_avatar ?? 'https://ui-avatars.com/api/?name=Guest&background=64748b&color=fff' }}" class="h-8 w-8 rounded-full object-cover ring-1 ring-border">
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <h5 class="text-xs font-bold text-foreground leading-none">{{ $reply->user ? $reply->user->name : ($reply->guest_name ?? 'Khách') }}</h5>
                                            @if($reply->is_admin_reply)
                                                <span class="rounded bg-primary/10 border border-primary/20 px-1.5 py-0.5 text-[8px] font-black text-primary uppercase">Admin</span>
                                            @endif
                                        </div>
                                        <span class="text-[8px] text-muted-foreground">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-foreground/80 leading-relaxed font-medium">
                                {{ $reply->content }}
                            </p>
                            <!-- Reply Reactions -->
                            <div class="flex items-center gap-4 text-[10px] pt-1">
                                <button @click="reactFeedback({{ $reply->id }}, 'like')" class="flex items-center gap-1 text-muted-foreground hover:text-primary transition-all cursor-pointer">
                                    <i data-lucide="thumbs-up" class="h-3 w-3"></i>
                                    <span id="like-count-{{ $reply->id }}">{{ $reply->likes_count }}</span>
                                </button>
                                <button @click="reactFeedback({{ $reply->id }}, 'dislike')" class="flex items-center gap-1 text-muted-foreground hover:text-destructive transition-all cursor-pointer">
                                    <i data-lucide="thumbs-down" class="h-3 w-3"></i>
                                    <span id="dislike-count-{{ $reply->id }}">{{ $reply->dislikes_count }}</span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                        @if($r->replies->count() > 2)
                        <button x-show="!showAllReplies" @click="showAllReplies = true" class="flex items-center gap-1.5 text-[10px] font-bold text-primary hover:text-primary/80 transition-all cursor-pointer group mt-2">
                            <i data-lucide="chevron-down" class="h-3.5 w-3.5 transition-transform group-hover:translate-y-0.5"></i>
                            Xem thêm {{ $r->replies->count() - 2 }} câu trả lời khác
                        </button>
                        <button x-show="showAllReplies" @click="showAllReplies = false" class="flex items-center gap-1.5 text-[10px] font-bold text-muted-foreground hover:text-primary transition-all cursor-pointer group mt-2">
                            <i data-lucide="chevron-up" class="h-3.5 w-3.5 transition-transform group-hover:-translate-y-0.5"></i>
                            Thu gọn
                        </button>
                        @endif
                    </div>
                    @endif

                    <!-- User Reply form (hidden by default) -->
                    <div id="reply-form-{{ $r->id }}" class="hidden pt-2">
                        <form method="POST" action="{{ route('feedback.reply', $r->id) }}" class="flex gap-2">
                            @csrf
                            <input type="text" name="content" required placeholder="Viết câu trả lời của bạn..." 
                                   class="h-9 flex-1 bg-muted/20 border border-border focus:border-primary/30 px-3.5 rounded-xl text-xs font-semibold outline-none transition-all text-foreground">
                            <button type="submit" class="h-9 px-4 rounded-xl gradient-primary text-white text-xs font-bold transition-all hover:scale-[1.02] active:scale-95 cursor-pointer">
                                Trả lời
                            </button>
                        </form>
                    </div>

                </div>
                @empty
                <div class="text-center p-8 rounded-[2rem] border border-border/30 bg-muted/10">
                    <p class="text-xs text-muted-foreground font-semibold">Chưa có đánh giá nào. Hãy là người đầu tiên chia sẻ cảm nhận!</p>
                </div>
                @endforelse

                @if($reviews->count() > 2)
                <div class="flex justify-center pt-2">
                    <button x-show="!showAllReviews" @click="showAllReviews = true; $nextTick(() => { lucide.createIcons(); })" class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-card/60 border border-border/50 hover:border-primary/30 text-xs font-bold text-primary hover:text-primary/80 transition-all cursor-pointer group hover:shadow-soft">
                        <i data-lucide="chevrons-down" class="h-4 w-4 transition-transform group-hover:translate-y-0.5"></i>
                        Xem thêm {{ $reviews->count() - 2 }} đánh giá khác
                    </button>
                    <button x-show="showAllReviews" @click="showAllReviews = false" class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-card/60 border border-border/50 hover:border-primary/30 text-xs font-bold text-muted-foreground hover:text-primary transition-all cursor-pointer group hover:shadow-soft">
                        <i data-lucide="chevrons-up" class="h-4 w-4 transition-transform group-hover:-translate-y-0.5"></i>
                        Thu gọn đánh giá
                    </button>
                </div>
                @endif
            </div>

            <!-- Compact Submission Form -->
            <div class="lg:col-span-5 bg-card/60 border border-border rounded-[2rem] p-6 shadow-soft relative overflow-hidden group">
                <div class="absolute inset-0 gradient-primary opacity-[0.02]"></div>
                <form method="POST" action="{{ route('feedback.store') }}" x-data="{ rating: 5, hover: 0 }" class="relative z-10 space-y-5">
                    @csrf
                    <input type="hidden" name="rating" :value="rating">
                    <h4 class="text-sm font-bold text-center uppercase tracking-widest text-foreground">Chia sẻ trải nghiệm</h4>
                    
                    <div class="flex justify-center gap-1.5">
                        @foreach([1,2,3,4,5] as $s)
                            <button type="button" @click="rating = {{ $s }}" @mouseenter="hover = {{ $s }}" @mouseleave="hover = 0" class="transition-transform hover:scale-125">
                                <i data-lucide="star" class="h-7 w-7 transition-colors" :class="(hover || rating) >= {{ $s }} ? 'fill-amber-400 text-amber-400' : 'text-muted-foreground/20'"></i>
                            </button>
                        @endforeach
                    </div>

                    <textarea name="content" rows="3" required placeholder="Viết cảm nhận của bạn..." class="w-full bg-muted/20 border border-border focus:border-primary/30 p-4 rounded-xl text-xs font-bold outline-none transition-all resize-none text-foreground"></textarea>
                    
                    <!-- Display success alert if session has success -->
                    @if(session('success'))
                    <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-xl p-3.5 flex items-center gap-2">
                        <i data-lucide="check-circle" class="h-4.5 w-4.5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    <div class="flex justify-center">
                        <button type="submit" class="group flex items-center gap-2 rounded-xl gradient-primary px-8 py-2.5 text-[10px] font-black uppercase tracking-widest text-white shadow-glow transition-all hover:scale-[1.05] active:scale-95 cursor-pointer">
                            Gửi phản hồi
                            <i data-lucide="send" class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    <!-- 4. PREMIUM GLASSMORPHIC CHECKOUT MODAL -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md transition-all duration-300"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="display: none;">
        
        <!-- Modal Card Wrapper -->
        <div class="relative max-w-5xl w-full bg-card/95 border border-border/60 rounded-[2.5rem] shadow-[0_0_60px_-15px_rgba(99,102,241,0.3)] p-6 sm:p-10 backdrop-blur-2xl flex flex-col lg:flex-row gap-8 sm:gap-10 overflow-hidden text-foreground">
            
            <!-- Dynamic blur decor blobs -->
            <div class="pointer-events-none absolute -top-40 -right-40 h-[300px] w-[300px] rounded-full bg-primary/10 blur-[80px]"></div>
            <div class="pointer-events-none absolute -bottom-40 -left-40 h-[300px] w-[300px] rounded-full bg-emerald-500/10 blur-[80px]"></div>

            <!-- Close button -->
            <button @click="if(!isSubmitting && !isSuccess) showModal = false" 
                    class="absolute top-6 right-6 flex h-10 w-10 items-center justify-center rounded-full bg-muted/40 hover:bg-muted text-muted-foreground hover:text-foreground transition-all hover:rotate-90 z-20 cursor-pointer"
                    :class="isSubmitting ? 'opacity-30 cursor-not-allowed' : ''"
                    :disabled="isSubmitting">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>

            <!-- LEFT COLUMN: ORDER SUMMARY -->
            <div class="w-full lg:w-[40%] flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-border/50 pb-8 lg:pb-0 lg:pr-10">
                <div class="space-y-6">
                    <div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-[10px] font-bold text-primary uppercase tracking-wider">
                            <i data-lucide="shopping-cart" class="h-3 w-3"></i> Đơn hàng của bạn
                        </span>
                        <h3 class="text-xl font-bold tracking-tight text-foreground mt-3" x-text="selectedPlan.name"></h3>
                        <p class="text-xs text-muted-foreground mt-1">Đăng ký dịch vụ chăm sóc sức khoẻ thông minh AI.</p>
                    </div>

                    <!-- Details Box -->
                    <div class="space-y-3.5 rounded-2xl bg-muted/30 border border-border/30 p-5">
                        <div class="flex justify-between text-xs font-semibold text-muted-foreground">
                            <span>Thời hạn sử dụng:</span>
                            <span class="text-foreground flex items-center gap-1">
                                <i data-lucide="calendar" class="h-3.5 w-3.5 text-primary"></i> 1 Tháng
                            </span>
                        </div>
                        <div class="flex justify-between text-xs font-semibold text-muted-foreground">
                            <span>Giá gói gốc:</span>
                            <span class="text-foreground font-bold" x-text="selectedPlan.priceFormatted"></span>
                        </div>
                        <div class="flex justify-between text-xs font-semibold text-muted-foreground" x-show="discountPercent > 0">
                            <span>Giảm giá (50%):</span>
                            <span class="text-success font-black" x-text="'-' + formattedDiscount"></span>
                        </div>
                        <div class="flex justify-between text-xs font-semibold text-muted-foreground">
                            <span>Thuế VAT (10%):</span>
                            <span class="text-foreground" x-text="formattedTax"></span>
                        </div>
                    </div>

                    <!-- Discount code input -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground">Mã giảm giá</label>
                        <div class="flex gap-2">
                            <input type="text" x-model="couponCode" placeholder="Nhập HEALTHAI50" 
                                   class="h-10 flex-1 rounded-xl border border-border/50 bg-background/50 px-3.5 text-xs font-semibold outline-none focus:border-primary transition-all text-foreground uppercase"
                                   :disabled="isSubmitting || isSuccess">
                            <button @click="applyCoupon()" 
                                    class="h-10 px-4 rounded-xl bg-primary hover:bg-primary/95 text-white text-xs font-bold transition-all hover:scale-105 active:scale-95 shadow-sm cursor-pointer"
                                    :disabled="isSubmitting || isSuccess">
                                Áp dụng
                            </button>
                        </div>
                        <p x-show="couponStatus === 'success'" class="text-[10px] font-semibold text-success flex items-center gap-1 mt-1">
                            <i data-lucide="check-circle" class="h-3 w-3"></i> Áp dụng thành công mã giảm giá 50%!
                        </p>
                        <p x-show="couponStatus === 'error'" class="text-[10px] font-semibold text-destructive flex items-center gap-1 mt-1">
                            <i data-lucide="alert-circle" class="h-3 w-3"></i> Mã giảm giá không chính xác hoặc đã hết hạn.
                        </p>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-border/50 pt-5">
                        <div class="flex justify-between items-baseline">
                            <span class="text-sm font-bold text-foreground">Tổng thanh toán:</span>
                            <span class="text-2xl font-black bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-400 bg-clip-text text-transparent filter drop-shadow-sm font-display tracking-tight" x-text="formattedTotal"></span>
                        </div>
                    </div>
                </div>

                <!-- Secure Badges -->
                <div class="mt-8 space-y-3 border-t border-border/30 pt-6">
                    <div class="flex items-center gap-3 text-xs font-semibold text-muted-foreground">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-500 shrink-0">
                            <i data-lucide="lock" class="h-4 w-4"></i>
                        </div>
                        <span>🔒 Thanh toán an toàn SSL 256-bit</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold text-muted-foreground">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-500/10 text-blue-500 shrink-0">
                            <i data-lucide="shield" class="h-4 w-4"></i>
                        </div>
                        <span>🛡️ Dữ liệu được mã hóa bảo mật cao</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold text-muted-foreground">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-500/10 text-purple-500 shrink-0">
                            <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                        </div>
                        <span>✅ Hoàn tiền trong 7 ngày nếu không hài lòng</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold text-muted-foreground">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-500/10 text-amber-500 shrink-0">
                            <i data-lucide="star" class="h-4 w-4"></i>
                        </div>
                        <span>⭐ Hỗ trợ khách hàng VIP 24/7</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: PAYMENT METHODS & CONFIRMATION -->
            <div class="w-full lg:w-[60%] flex flex-col justify-between space-y-6">
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-muted-foreground mb-4">Phương thức thanh toán</h4>
                    
                    <!-- Tab selectors -->
                    <div class="grid grid-cols-3 gap-3 bg-muted/40 border border-border/40 p-1.5 rounded-2xl mb-6">
                        <button @click="paymentMethod = 'card'" 
                                class="py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                :class="paymentMethod === 'card' ? 'bg-card text-foreground shadow-sm ring-1 ring-border/20' : 'text-muted-foreground hover:text-foreground'">
                            <i data-lucide="credit-card" class="h-4 w-4"></i> Thẻ ngân hàng
                        </button>
                        <button @click="paymentMethod = 'bank'" 
                                class="py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                :class="paymentMethod === 'bank' ? 'bg-card text-foreground shadow-sm ring-1 ring-border/20' : 'text-muted-foreground hover:text-foreground'">
                            <i data-lucide="building-2" class="h-4 w-4"></i> Chuyển khoản
                        </button>
                        <button @click="paymentMethod = 'wallet'" 
                                class="py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                :class="paymentMethod === 'wallet' ? 'bg-card text-foreground shadow-sm ring-1 ring-border/20' : 'text-muted-foreground hover:text-foreground'">
                            <i data-lucide="wallet" class="h-4 w-4"></i> Ví điện tử
                        </button>
                    </div>

                    <!-- Form Contents based on Active Tab -->
                    <div>
                        <!-- TAB 1: CREDIT CARD -->
                        <div x-show="paymentMethod === 'card'" class="space-y-6">
                            <!-- Visual Bank Card Mockup -->
                            <div class="relative w-full max-w-[340px] aspect-[1.58/1] mx-auto rounded-3xl p-6 text-white bg-gradient-to-br from-slate-900 via-indigo-950 to-emerald-950 shadow-2xl border border-white/10 overflow-hidden flex flex-col justify-between transition-transform duration-500 hover:scale-105 select-none">
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_20%,rgba(99,102,241,0.15),transparent_50%)]"></div>
                                <div class="absolute inset-0 bg-grid-white/[0.02]"></div>
                                
                                <!-- Card Header -->
                                <div class="flex justify-between items-start relative z-10">
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded-lg gradient-primary flex items-center justify-center shadow-glow">
                                            <i data-lucide="heart-pulse" class="h-4.5 w-4.5 text-white"></i>
                                        </div>
                                        <span class="text-xs font-black tracking-tight">HealthAI</span>
                                    </div>
                                    
                                    <!-- Dynamic Card Brand Logo SVG -->
                                    <div class="h-7 flex items-center">
                                        <template x-if="cardBrand === 'visa'">
                                            <svg class="h-3 w-10 text-white fill-current" viewBox="0 0 24 8"><path d="M0 0h3.2l2 6.2L7 1.2A3 3 0 0 0 4.2 0H0v.1zm9.6 0h2.8l2 6.2 1.8-6.2h2.8l-3.2 8h-3l-2-6.2-2 6.2H9l-2.6-8zm10.7 0h3.2l1.2 5a9 9 0 0 1-2.4-5z"/></svg>
                                        </template>
                                        <template x-if="cardBrand === 'mastercard'">
                                            <div class="flex items-center -space-x-2">
                                                <div class="h-6 w-6 rounded-full bg-red-500/90"></div>
                                                <div class="h-6 w-6 rounded-full bg-amber-500/80"></div>
                                            </div>
                                        </template>
                                        <template x-if="cardBrand === 'amex'">
                                            <span class="text-[10px] font-black bg-blue-500 px-2 py-0.5 rounded">AMEX</span>
                                        </template>
                                        <template x-if="cardBrand === 'jcb'">
                                            <span class="text-[10px] font-black bg-blue-700 px-2 py-0.5 rounded">JCB</span>
                                        </template>
                                        <template x-if="cardBrand === 'unknown'">
                                            <i data-lucide="credit-card" class="h-5 w-5 text-white/50"></i>
                                        </template>
                                    </div>
                                </div>

                                <!-- Card Chip & Contactless -->
                                <div class="flex items-center gap-4 relative z-10">
                                    <div class="h-7 w-9 rounded-md bg-gradient-to-r from-yellow-300 via-amber-400 to-yellow-500 shadow-sm border border-yellow-200/50 relative overflow-hidden">
                                        <div class="absolute inset-x-0 top-2 h-px bg-slate-800/40"></div>
                                        <div class="absolute inset-x-0 bottom-2 h-px bg-slate-800/40"></div>
                                        <div class="absolute inset-y-0 left-3.5 w-px bg-slate-800/40"></div>
                                    </div>
                                    <svg class="h-5 w-5 text-white/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 8a9 9 0 0 1 12 0M2 5a14 14 0 0 1 18 0"/></svg>
                                </div>

                                <!-- Card Body: Number -->
                                <div class="relative z-10">
                                    <p class="text-base font-bold tracking-[0.2em] font-mono text-center" 
                                       x-text="cardNumber ? cardNumber : '•••• •••• •••• ••••'"></p>
                                </div>

                                <!-- Card Footer: Holder & Exp -->
                                <div class="flex justify-between items-end relative z-10">
                                    <div>
                                        <p class="text-[8px] font-bold text-white/40 uppercase tracking-widest leading-none">Chủ thẻ</p>
                                        <p class="text-[10px] font-black tracking-wide uppercase mt-1.5 truncate max-w-[180px]" 
                                           x-text="cardName ? cardName : 'NGUYEN VAN A'"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[8px] font-bold text-white/40 uppercase tracking-widest leading-none">Hết hạn</p>
                                        <p class="text-[10px] font-black tracking-wider mt-1.5 font-mono" 
                                           x-text="cardExpiry ? cardExpiry : 'MM/YY'"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Input form fields -->
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground pl-0.5">Tên chủ thẻ (Viết hoa không dấu)</label>
                                    <input type="text" x-model="cardName" placeholder="NGUYEN VAN A" 
                                           class="h-10 w-full rounded-xl border border-border/50 bg-background/50 px-3.5 text-xs font-semibold outline-none focus:border-primary transition-all text-foreground uppercase">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground pl-0.5">Số thẻ ngân hàng</label>
                                    <input type="text" x-model="cardNumber" @input="formatCardNumber()" placeholder="4000 1234 5678 9010" 
                                           class="h-10 w-full rounded-xl border border-border/50 bg-background/50 px-3.5 text-xs font-semibold outline-none focus:border-primary transition-all text-foreground">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground pl-0.5">Ngày hết hạn</label>
                                        <input type="text" x-model="cardExpiry" @input="formatExpiry()" placeholder="MM/YY" maxlength="5"
                                               class="h-10 w-full rounded-xl border border-border/50 bg-background/50 px-3.5 text-xs font-semibold outline-none focus:border-primary transition-all text-foreground">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground pl-0.5">Mã bảo mật CVV/CVC</label>
                                        <input type="password" x-model="cardCvv" placeholder="•••" maxlength="3"
                                               class="h-10 w-full rounded-xl border border-border/50 bg-background/50 px-3.5 text-xs font-semibold outline-none focus:border-primary transition-all text-foreground">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: BANK TRANSFER -->
                        <div x-show="paymentMethod === 'bank'" class="space-y-6 animate-in fade-in duration-300">
                            <div class="grid md:grid-cols-2 gap-6 items-center">
                                <!-- Bank details -->
                                <div class="space-y-4 rounded-2xl bg-muted/20 border border-border/30 p-5 text-xs">
                                    <div class="flex items-center gap-2 mb-2 pb-2 border-b border-border/30">
                                        <div class="h-6 w-14 rounded bg-emerald-600/10 text-emerald-500 font-bold text-[9px] flex items-center justify-center shrink-0 border border-emerald-500/20">VCB</div>
                                        <span class="font-bold text-foreground">Vietcombank Chi nhánh Hà Nội</span>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-widest leading-none">Chủ tài khoản</p>
                                        <p class="text-sm font-black text-foreground uppercase tracking-wide">HEALTHAI COMPANY</p>
                                    </div>
                                    
                                    <div class="space-y-2 relative group">
                                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-widest leading-none">Số tài khoản</p>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="text-sm font-black text-primary select-all">1234567890</span>
                                            <button @click="navigator.clipboard.writeText('1234567890'); alert('Đã sao chép STK!');" class="text-muted-foreground hover:text-foreground cursor-pointer">
                                                <i data-lucide="copy" class="h-4 w-4"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="space-y-2 pt-2 border-t border-border/30">
                                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-widest leading-none">Nội dung chuyển khoản</p>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="font-bold text-foreground uppercase" x-text="'HEALTHAI' + (txnId ? txnId : 'ORDER123')"></span>
                                            <button @click="navigator.clipboard.writeText('HEALTHAI ORDER123'); alert('Đã sao chép nội dung!');" class="text-muted-foreground hover:text-foreground cursor-pointer">
                                                <i data-lucide="copy" class="h-4 w-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Code Panel -->
                                <div class="flex flex-col items-center justify-center text-center space-y-3">
                                    <div class="relative p-4 rounded-3xl bg-white shadow-lg border border-slate-200 w-44 h-44 flex items-center justify-center overflow-hidden">
                                        <!-- Pulsing scanner line -->
                                        <div class="absolute inset-x-0 h-0.5 bg-emerald-500/80 animate-bounce top-2 shadow-[0_0_10px_#10b981] z-10"></div>
                                        
                                        <!-- Beautiful Mock QR code SVG -->
                                        <svg class="w-full h-full text-slate-900 fill-current" viewBox="0 0 100 100">
                                            <!-- Border anchors -->
                                            <path d="M0 0h25v8H8v17H0V0zm75 0h25v25h-8V8H75V0zM0 75h8v17h17v8H0V75zm75 17v-17h8v25H75v-8z" />
                                            <!-- Top-left box -->
                                            <path d="M6 6h18v18H6V6zm4 4v10h10V10H10zM12 12h6v6h-6v-6z" />
                                            <!-- Top-right box -->
                                            <path d="M76 6h18v18H76V6zm4 4v10h10V10H80zM82 12h6v6h-6v-6z" />
                                            <!-- Bottom-left box -->
                                            <path d="M6 76h18v18H6V76zm4 4v10h10V10H10zM12 82h6v6h-6v-6z" />
                                            <!-- Grid data patterns -->
                                            <path d="M30 6h4v4h-4zm8 0h8v4h-8zm12 0h4v8h-4zm8 0h4v4h-4zm8 4h4v4h-4zm-28 4h8v4h-8zm12 0h4v4h-4zm12 0h8v4h-8zm-28 8h4v4h-4zm8 0h4v8h-4zm16 0h4v4h-4zm12 0h4v4h-4zm-48 12h8v4H6zm12 0h4v4h-4zm16 0h8v4h-8zm12 0h4v8h-4zm8 0h8v4h-8zm8 0h4v4h-4zm-56 8h4v4h-4zm16 0h4v4h-4zm8 0h8v4h-8zm12 0h4v4h-4zm16 0h4v4h-4zm-56 8h8v4h-8zm16 0h4v4h-4zm12 0h8v4h-8zm12 0h4v4h-4zm16 0h4v4h-4zm-52 8h4v4h-4zm12 0h4v8h-4zm16 0h4v4h-4zm8 0h8v4h-8zm16 0h4v4h-4zm-44 8h8v4h-8zm16 0h4v4h-4zm12 0h8v4h-8zm16 0h4v4h-4z" />
                                            <!-- AI Heart Logo at QR Center -->
                                            <rect x="42" y="42" width="16" height="16" rx="4" fill="#6366f1" />
                                            <path d="M50 53.5l-2.8-2.6c-1-.9-1.6-1.5-1.6-2.2 0-.8.6-1.4 1.4-1.4.5 0 .9.2 1.1.5l.9 1 .9-1c.3-.3.7-.5 1.2-.5.8 0 1.4.6 1.4 1.4 0 .7-.6 1.3-1.6 2.2l-2.8 2.6z" fill="white" />
                                        </svg>
                                    </div>
                                    <p class="text-[10px] font-semibold text-muted-foreground">Quét mã bằng camera hoặc ứng dụng Ngân hàng</p>
                                </div>
                            </div>

                            <!-- "Tôi đã chuyển khoản" button -->
                            <div class="flex justify-center pt-2">
                                <button @click="termsAccepted = true; submitPayment()" 
                                        class="h-10 px-8 rounded-xl bg-emerald-500 hover:bg-emerald-500/90 text-white text-xs font-bold transition-all hover:scale-105 active:scale-95 shadow-md flex items-center gap-1.5 cursor-pointer"
                                        :disabled="isSubmitting || isSuccess">
                                    <i data-lucide="check-circle" class="h-4 w-4"></i> Tôi đã chuyển khoản
                                </button>
                            </div>
                        </div>

                        <!-- TAB 3: E-WALLET -->
                        <div x-show="paymentMethod === 'wallet'" class="space-y-6 animate-in fade-in duration-300">
                            <!-- Wallet grid options -->
                            <div class="grid grid-cols-2 gap-4">
                                <button @click="selectedWallet = 'momo'"
                                        class="p-4 rounded-2xl border transition-all duration-300 flex items-center justify-between gap-4 cursor-pointer"
                                        :class="selectedWallet === 'momo' ? 'border-[#A50064] bg-[#A50064]/5 shadow-[0_0_20px_-5px_rgba(165,0,100,0.3)]' : 'border-border/50 bg-muted/10 hover:bg-muted/30'">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-xl bg-[#A50064] flex items-center justify-center font-bold text-white text-xs shrink-0 shadow-sm">M</div>
                                        <div class="text-left">
                                            <p class="text-xs font-black text-foreground leading-none">Ví MoMo</p>
                                            <p class="text-[9px] text-muted-foreground mt-1">Nhanh chóng & Tiện lợi</p>
                                        </div>
                                    </div>
                                    <div class="h-4.5 w-4.5 rounded-full border flex items-center justify-center" :class="selectedWallet === 'momo' ? 'border-[#A50064] bg-[#A50064]' : 'border-border'">
                                        <div class="h-2 w-2 rounded-full bg-white" x-show="selectedWallet === 'momo'"></div>
                                    </div>
                                </button>

                                <button @click="selectedWallet = 'zalopay'"
                                        class="p-4 rounded-2xl border transition-all duration-300 flex items-center justify-between gap-4 cursor-pointer"
                                        :class="selectedWallet === 'zalopay' ? 'border-[#008FE5] bg-[#008FE5]/5 shadow-[0_0_20px_-5px_rgba(0,143,229,0.3)]' : 'border-border/50 bg-muted/10 hover:bg-muted/30'">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-xl bg-[#008FE5] flex items-center justify-center font-bold text-white text-xs shrink-0 shadow-sm">Z</div>
                                        <div class="text-left">
                                            <p class="text-xs font-black text-foreground leading-none">ZaloPay</p>
                                            <p class="text-[9px] text-muted-foreground mt-1">Liên kết Zalo tiện lợi</p>
                                        </div>
                                    </div>
                                    <div class="h-4.5 w-4.5 rounded-full border flex items-center justify-center" :class="selectedWallet === 'zalopay' ? 'border-[#008FE5] bg-[#008FE5]' : 'border-border'">
                                        <div class="h-2 w-2 rounded-full bg-white" x-show="selectedWallet === 'zalopay'"></div>
                                    </div>
                                </button>

                                <button @click="selectedWallet = 'vnpay'"
                                        class="p-4 rounded-2xl border transition-all duration-300 flex items-center justify-between gap-4 cursor-pointer"
                                        :class="selectedWallet === 'vnpay' ? 'border-[#005BAA] bg-[#005BAA]/5 shadow-[0_0_20px_-5px_rgba(0,91,170,0.3)]' : 'border-border/50 bg-muted/10 hover:bg-muted/30'">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-xl bg-[#005BAA] flex items-center justify-center font-bold text-white text-[10px] shrink-0 shadow-sm">VN</div>
                                        <div class="text-left">
                                            <p class="text-xs font-black text-foreground leading-none">VNPay</p>
                                            <p class="text-[9px] text-muted-foreground mt-1">Cổng thanh toán quốc gia</p>
                                        </div>
                                    </div>
                                    <div class="h-4.5 w-4.5 rounded-full border flex items-center justify-center" :class="selectedWallet === 'vnpay' ? 'border-[#005BAA] bg-[#005BAA]' : 'border-border'">
                                        <div class="h-2 w-2 rounded-full bg-white" x-show="selectedWallet === 'vnpay'"></div>
                                    </div>
                                </button>

                                <button @click="selectedWallet = 'shopeepay'"
                                        class="p-4 rounded-2xl border transition-all duration-300 flex items-center justify-between gap-4 cursor-pointer"
                                        :class="selectedWallet === 'shopeepay' ? 'border-[#EE4D2D] bg-[#EE4D2D]/5 shadow-[0_0_20px_-5px_rgba(238,77,45,0.3)]' : 'border-border/50 bg-muted/10 hover:bg-muted/30'">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-xl bg-[#EE4D2D] flex items-center justify-center font-bold text-white text-xs shrink-0 shadow-sm">S</div>
                                        <div class="text-left">
                                            <p class="text-xs font-black text-foreground leading-none">ShopeePay</p>
                                            <p class="text-[9px] text-muted-foreground mt-1">Xu Shopee & Quét mã</p>
                                        </div>
                                    </div>
                                    <div class="h-4.5 w-4.5 rounded-full border flex items-center justify-center" :class="selectedWallet === 'shopeepay' ? 'border-[#EE4D2D] bg-[#EE4D2D]' : 'border-border'">
                                        <div class="h-2 w-2 rounded-full bg-white" x-show="selectedWallet === 'shopeepay'"></div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONFIRM AREA (Only shown when not bank transfer or standard card/wallet check) -->
                <div class="space-y-4 pt-4 border-t border-border/30" x-show="paymentMethod !== 'bank'">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="termsAccepted" 
                               class="h-4.5 w-4.5 rounded border-border bg-background focus:ring-primary text-primary transition-all mt-0.5 cursor-pointer"
                               :disabled="isSubmitting || isSuccess">
                        <span class="text-xs text-muted-foreground group-hover:text-foreground transition-colors font-medium">
                            Tôi đồng ý với điều khoản sử dụng và chính sách thanh toán của HealthAI.
                        </span>
                    </label>

                    <button @click="submitPayment()" 
                            class="w-full h-12 rounded-2xl gradient-primary font-bold text-sm text-white shadow-glow hover:scale-[1.02] active:scale-95 disabled:opacity-40 disabled:scale-100 disabled:shadow-none transition-all flex items-center justify-center gap-2 cursor-pointer"
                            :disabled="!termsAccepted || isSubmitting || isSuccess">
                        Thanh toán ngay
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>

            <!-- AI PROCESSING OVERLAY SCREEN -->
            <div x-show="isSubmitting" 
                 class="absolute inset-0 bg-slate-950/95 backdrop-blur-xl flex flex-col items-center justify-center text-center p-8 z-30 animate-in fade-in duration-300"
                 style="display: none;">
                <div class="space-y-8 max-w-sm">
                    <!-- Spinning AI network indicator -->
                    <div class="relative w-24 h-24 mx-auto flex items-center justify-center">
                        <div class="absolute inset-0 rounded-full border-4 border-primary/20"></div>
                        <div class="absolute inset-0 rounded-full border-4 border-t-primary border-r-emerald-400 animate-spin"></div>
                        <div class="h-10 w-10 rounded-full gradient-primary shadow-glow flex items-center justify-center animate-pulse">
                            <i data-lucide="bot" class="h-5 w-5 text-white"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-lg font-bold text-white tracking-tight">Xử lý thanh toán thông minh</h4>
                        <p class="text-xs text-primary font-bold animate-pulse tracking-wide uppercase" x-text="currentLoadingMessage"></p>
                    </div>
                    
                    <p class="text-[10px] text-muted-foreground">Giao dịch của bạn được mã hóa hoàn toàn. Vui lòng không đóng trình duyệt hoặc tải lại trang.</p>
                </div>
            </div>

            <!-- SUCCESS CONFIRMATION SCREEN OVERLAY -->
            <div x-show="isSuccess" 
                 id="success-panel"
                 class="absolute inset-0 bg-slate-950/98 backdrop-blur-2xl flex flex-col items-center justify-center text-center p-8 z-30 animate-in fade-in zoom-in-95 duration-500"
                 style="display: none;">
                
                <!-- Floating confetti particles -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none" id="confetti-container"></div>

                <div class="space-y-8 max-w-md relative z-10">
                    <!-- Scaling Checkmark badge -->
                    <div class="h-20 w-20 rounded-full bg-emerald-500/10 border-2 border-emerald-500 flex items-center justify-center mx-auto text-emerald-400 shadow-[0_0_30px_rgba(16,185,129,0.3)] animate-bounce">
                        <i data-lucide="party-popper" class="h-9 w-9"></i>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-2xl font-black bg-gradient-to-r from-emerald-400 via-teal-400 to-emerald-500 bg-clip-text text-transparent tracking-tight">Thanh toán thành công!</h3>
                        <p class="text-xs text-muted-foreground">Cảm ơn bạn! Giao dịch của bạn đã được ghi nhận và kích hoạt ngay lập tức.</p>
                    </div>

                    <!-- Receipt summary card -->
                    <div class="rounded-3xl bg-slate-900/50 border border-white/5 p-6 text-left space-y-3.5 text-xs font-semibold text-muted-foreground shadow-inner">
                        <div class="flex justify-between">
                            <span>Mã giao dịch:</span>
                            <span class="text-white select-all" x-text="txnId"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Gói dịch vụ đã mua:</span>
                            <span class="text-white" x-text="selectedPlan.name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ngày kích hoạt:</span>
                            <span class="text-white" x-text="activationDate"></span>
                        </div>
                        <div class="flex justify-between flex-wrap">
                            <span>Thời hạn hết hạn gói:</span>
                            <span class="text-primary font-bold" x-text="expiryDate"></span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showModal = false; isSuccess = false;" 
                                class="h-11 rounded-xl bg-card border border-border/50 hover:bg-muted text-foreground text-xs font-bold transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-1.5 shadow-sm cursor-pointer">
                            <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Vào Dashboard
                        </button>
                        <button @click="downloadInvoice()" 
                                class="h-11 rounded-xl gradient-primary text-white text-xs font-bold transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-1.5 shadow-glow cursor-pointer">
                            <i data-lucide="download" class="h-4 w-4"></i> Hóa đơn PDF
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Alpine data initializer -->
<script>
    function checkoutData() {
        return {
            showModal: false,
            selectedPlan: { name: '', price: 0, priceFormatted: '' },
            taxRate: 0.1,
            couponCode: '',
            discountPercent: 0,
            couponStatus: '',
            paymentMethod: 'card', // 'card', 'bank', 'wallet'
            selectedWallet: 'momo',
            termsAccepted: false,
            isSubmitting: false,
            isSuccess: false,
            loadingStep: 0,
            loadingMessages: [
                'Đang khởi tạo kết nối SSL 256-bit an toàn...',
                'Mã hóa thông tin và chữ ký số bằng AI...',
                'Đang kiểm tra giao dịch tài khoản...',
                'Xác thực thành công và kích hoạt gói...'
            ],
            currentLoadingMessage: '',
            // Card input fields
            cardName: '',
            cardNumber: '',
            cardExpiry: '',
            cardCvv: '',
            // Success details
            txnId: '',
            activationDate: '',
            expiryDate: '',

            openCheckout(name, price) {
                this.selectedPlan = {
                    name: 'Gói ' + name + ' AI Health',
                    price: price,
                    priceFormatted: new Intl.NumberFormat('vi-VN').format(price) + ' VNĐ'
                };
                this.couponCode = '';
                this.discountPercent = 0;
                this.couponStatus = '';
                this.paymentMethod = 'card';
                this.termsAccepted = false;
                this.isSubmitting = false;
                this.isSuccess = false;
                this.cardName = '';
                this.cardNumber = '';
                this.cardExpiry = '';
                this.cardCvv = '';
                this.showModal = true;
                
                // Re-render Lucide icons inside modal
                setTimeout(() => {
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                }, 100);
            },

            applyCoupon() {
                if (this.couponCode.trim().toUpperCase() === 'HEALTHAI50') {
                    this.discountPercent = 0.5;
                    this.couponStatus = 'success';
                } else if (this.couponCode.trim() !== '') {
                    this.discountPercent = 0;
                    this.couponStatus = 'error';
                } else {
                    this.discountPercent = 0;
                    this.couponStatus = '';
                }
            },

            get taxAmount() {
                return Math.round((this.selectedPlan.price * (1 - this.discountPercent)) * this.taxRate);
            },

            get discountAmount() {
                return Math.round(this.selectedPlan.price * this.discountPercent);
            },

            get totalAmount() {
                const subtotal = this.selectedPlan.price - this.discountAmount;
                return subtotal + this.taxAmount;
            },

            get formattedTax() {
                return new Intl.NumberFormat('vi-VN').format(this.taxAmount) + ' VNĐ';
            },

            get formattedDiscount() {
                return new Intl.NumberFormat('vi-VN').format(this.discountAmount) + ' VNĐ';
            },

            get formattedTotal() {
                return new Intl.NumberFormat('vi-VN').format(this.totalAmount) + ' VNĐ';
            },

            get cardBrand() {
                const cleanNum = this.cardNumber.replace(/\s+/g, '');
                if (cleanNum.startsWith('4')) return 'visa';
                if (/^5[1-5]/.test(cleanNum)) return 'mastercard';
                if (/^3[47]/.test(cleanNum)) return 'amex';
                if (/^(?:2131|1800|35)/.test(cleanNum)) return 'jcb';
                return 'unknown';
            },

            formatCardNumber() {
                let value = this.cardNumber.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                let formatted = '';
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formatted += ' ';
                    }
                    formatted += value[i];
                }
                this.cardNumber = formatted.substring(0, 19);
            },

            formatExpiry() {
                let value = this.cardExpiry.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                if (value.length > 2) {
                    this.cardExpiry = value.substring(0, 2) + '/' + value.substring(2, 4);
                } else {
                    this.cardExpiry = value;
                }
            },

            submitPayment() {
                this.isSubmitting = true;
                this.loadingStep = 0;
                this.currentLoadingMessage = this.loadingMessages[0];

                const interval = setInterval(() => {
                    this.loadingStep++;
                    if (this.loadingStep < this.loadingMessages.length) {
                        this.currentLoadingMessage = this.loadingMessages[this.loadingStep];
                    } else {
                        clearInterval(interval);
                        this.isSubmitting = false;
                        this.isSuccess = true;
                        
                        // Generate mock transaction details
                        this.txnId = 'HTAI-' + Math.floor(10000000 + Math.random() * 90000000);
                        const now = new Date();
                        this.activationDate = now.toLocaleDateString('vi-VN');
                        const nextMonth = new Date(now.setMonth(now.getMonth() + 1));
                        this.expiryDate = nextMonth.toLocaleDateString('vi-VN');

                        // Trigger CSS Confetti
                        this.triggerConfetti();
                        
                        // Re-render Lucide icons inside success screen
                        setTimeout(() => {
                            if (window.lucide) {
                                window.lucide.createIcons();
                            }
                        }, 100);
                    }
                }, 900);
            },

            triggerConfetti() {
                this.$nextTick(() => {
                    const container = document.getElementById('confetti-container');
                    if (!container) return;
                    container.innerHTML = '';
                    
                    const colors = ['bg-[#6366f1]', 'bg-[#10b981]', 'bg-[#3b82f6]', 'bg-[#fbbf24]', 'bg-[#ec4899]'];
                    
                    for (let i = 0; i < 40; i++) {
                        const confetti = document.createElement('div');
                        const color = colors[Math.floor(Math.random() * colors.length)];
                        
                        confetti.className = `absolute h-2 w-2 rounded-sm ${color} opacity-80`;
                        confetti.style.left = Math.random() * 100 + '%';
                        confetti.style.top = '-20px';
                        confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                        
                        // CSS keyframe simulation
                        const duration = 2 + Math.random() * 3;
                        const delay = Math.random() * 2;
                        
                        confetti.style.animation = `confettiFall ${duration}s linear ${delay}s infinite`;
                        container.appendChild(confetti);
                    }
                });
            },

            downloadInvoice() {
                const text = `
=============================================
             HOÁ ĐƠN THANH TOÁN
                   HealthAI
=============================================
Mã giao dịch: ${this.txnId}
Gói dịch vụ: ${this.selectedPlan.name}
Thời hạn: 1 tháng
Ngày kích hoạt: ${this.activationDate}
Ngày hết hạn: ${this.expiryDate}
---------------------------------------------
Giá gốc: ${this.selectedPlan.priceFormatted}
Giảm giá: ${this.formattedDiscount}
Thuế (10%): ${this.formattedTax}
---------------------------------------------
TỔNG THANH TOÁN: ${this.formattedTotal}
=============================================
Cảm ơn bạn đã đồng hành cùng HealthAI!
🔒 SSL Secured | 24/7 Premium Support
`;
                const blob = new Blob([text], { type: 'text/plain; charset=utf-8' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `HoaDon-${this.txnId}.txt`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }
        };
    }

    function toggleReplyForm(id) {
        const el = document.getElementById('reply-form-' + id);
        if (el) {
            el.classList.toggle('hidden');
        }
    }

    function reactFeedback(id, type) {
        fetch(`/feedback/${id}/react`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.likes !== undefined && data.dislikes !== undefined) {
                const lEl = document.getElementById('like-count-' + id);
                const dEl = document.getElementById('dislike-count-' + id);
                if (lEl) lEl.innerText = data.likes;
                if (dEl) dEl.innerText = data.dislikes;
            }
        })
        .catch(error => console.error('Error reacting to feedback:', error));
    }
</script>

<style>
    @keyframes confettiFall {
        0% {
            top: -20px;
            transform: translateX(0) rotate(0deg);
        }
        50% {
            transform: translateX(30px) rotate(180deg);
        }
        100% {
            top: 100%;
            transform: translateX(-30px) rotate(360deg);
        }
    }
</style>
@endsection
