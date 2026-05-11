<section id="features" class="mx-auto max-w-7xl px-4 py-20 md:px-6 md:py-28">
    <div class="mx-auto max-w-2xl text-center">
        <p class="text-xs font-semibold uppercase tracking-widest text-primary">
            Tính năng nổi bật
        </p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight font-display md:text-4xl text-foreground">
            Mọi thứ bạn cần để <span class="gradient-text">sống khỏe hơn mỗi ngày</span>
        </h2>
        <p class="mt-4 text-base text-muted-foreground">
            Bộ công cụ toàn diện được hỗ trợ bởi AI để chăm sóc sức khỏe chủ động.
        </p>
    </div>

    <div class="mt-14 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @php
            $features = [
                [
                    'icon' => 'activity',
                    'title' => 'Theo dõi sức khỏe',
                    'desc' => 'Đo lường cân nặng, huyết áp, đường huyết, giấc ngủ và phân tích xu hướng theo thời gian.',
                    'color' => 'from-rose-500 to-pink-500',
                ],
                [
                    'icon' => 'bot',
                    'title' => 'AI Chatbot',
                    'desc' => 'Trợ lý ảo trả lời 24/7 về triệu chứng, dinh dưỡng và thói quen sức khỏe.',
                    'color' => 'from-primary to-accent',
                ],
                [
                    'icon' => 'salad',
                    'title' => 'Thực đơn AI',
                    'desc' => 'Gợi ý thực đơn cá nhân hóa theo BMI, mục tiêu và sở thích ăn uống.',
                    'color' => 'from-emerald-500 to-green-500',
                ],
                [
                    'icon' => 'dumbbell',
                    'title' => 'Luyện tập AI',
                    'desc' => 'Bài tập được thiết kế riêng theo mức độ và lịch trình của bạn.',
                    'color' => 'from-amber-500 to-orange-500',
                ],
                [
                    'icon' => 'calendar-days',
                    'title' => 'Lịch khám thông minh',
                    'desc' => 'Đặt lịch với bác sĩ, nhắc nhở uống thuốc và theo dõi tái khám.',
                    'color' => 'from-violet-500 to-purple-500',
                ],
                [
                    'icon' => 'shield-check',
                    'title' => 'Bảo mật tuyệt đối',
                    'desc' => 'Dữ liệu sức khỏe được mã hoá end-to-end và tuân thủ chuẩn HIPAA.',
                    'color' => 'from-cyan-500 to-blue-500',
                ],
            ];
        @endphp

        @foreach($features as $f)
        <div class="group relative overflow-hidden rounded-3xl border border-border/50 bg-card/70 p-6 shadow-soft backdrop-blur-xl transition-all hover:-translate-y-1 hover:shadow-elevated">
            <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-gradient-to-br {{ $f['color'] }} opacity-10 blur-2xl transition-opacity group-hover:opacity-25"></div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br {{ $f['color'] }} text-white shadow-glow">
                <i data-lucide="{{ $f['icon'] }}" class="h-5 w-5"></i>
            </div>
            <h3 class="mt-5 text-lg font-bold tracking-tight text-foreground">{{ $f['title'] }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $f['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>
