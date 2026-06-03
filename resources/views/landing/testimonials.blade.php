<section id="reviews" class="mx-auto max-w-7xl px-4 py-20 md:px-6 md:py-28">
    <div class="mx-auto max-w-2xl text-center mb-14">
        <p class="text-xs font-semibold uppercase tracking-widest text-primary">
            Người dùng nói gì
        </p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight font-display md:text-4xl text-foreground">
            Được yêu thích bởi <span class="gradient-text">hàng nghìn người</span>
        </h2>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        @php
            $reviews = [
                [
                    'name' => 'Mai Linh', 
                    'role' => 'Nhân viên văn phòng', 
                    'text' => 'Mình giảm 5kg trong 2 tháng nhờ thực đơn AI gợi ý. Giao diện đẹp, dùng cực dễ!',
                    'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=150&h=150'
                ],
                [
                    'name' => 'Trần Hùng', 
                    'role' => 'Vận động viên', 
                    'text' => 'AI Chatbot trả lời mọi câu hỏi về dinh dưỡng và tập luyện. Tuyệt vời!',
                    'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=150&h=150'
                ],
                [
                    'name' => 'Bs. Phương', 
                    'role' => 'Bác sĩ nội tổng quát', 
                    'text' => 'Tôi giới thiệu HealthAI cho bệnh nhân để theo dõi chỉ số tại nhà. Rất tiện.',
                    'avatar' => 'https://nguoinoitieng.tv/images/nnt/107/0/bjur.jpg'
                ],
            ];
        @endphp

        @foreach($reviews as $r)
        <div class="rounded-3xl border border-border/50 bg-card/70 p-6 shadow-soft backdrop-blur-xl transition-all hover:border-primary/30">
            <div class="flex items-center gap-0.5 text-amber-400 mb-4">
                @for($i=0; $i<5; $i++)
                    <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                @endfor
            </div>
            <p class="text-sm leading-relaxed text-foreground/90 italic">"{{ $r['text'] }}"</p>
            <div class="mt-6 flex items-center gap-3">
                <img src="{{ $r['avatar'] }}" alt="{{ $r['name'] }}" class="h-10 w-10 rounded-full object-cover border-2 border-primary/20 shadow-glow">
                <div>
                    <p class="text-sm font-semibold text-foreground">{{ $r['name'] }}</p>
                    <p class="text-xs text-muted-foreground">{{ $r['role'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
