@extends('layouts.dashboard')

@section('title', 'AI Chatbot — HealthAI')

@section('content')
<!-- Page Header -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl gradient-primary text-primary-foreground shadow-glow">
            <i data-lucide="bot" class="h-5 w-5"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-tight md:text-3xl font-display">AI Health Chatbot</h1>
            <p class="mt-1 text-sm text-muted-foreground">Trợ lý sức khỏe AI thông minh, sẵn sàng 24/7</p>
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-1">
        <!-- History -->
        <div class="glass rounded-2xl p-5 shadow-soft">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold">Lịch sử trò chuyện</h3>
                <i data-lucide="message-square" class="h-4 w-4 text-muted-foreground"></i>
            </div>
            <div class="mt-3 space-y-1.5">
                @php
                    $history = [
                        ['title' => 'Tư vấn dinh dưỡng buổi sáng', 'time' => 'Hôm nay, 08:24'],
                        ['title' => 'Phân tích nhịp tim tuần', 'time' => 'Hôm qua, 21:10'],
                        ['title' => 'Kế hoạch giảm cân 30 ngày', 'time' => '2 ngày trước'],
                        ['title' => 'Triệu chứng đau đầu', 'time' => '5 ngày trước'],
                    ];
                @endphp
                @foreach($history as $h)
                <button class="group flex w-full items-start gap-2 rounded-xl p-2.5 text-left transition-colors hover:bg-sidebar-accent">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <i data-lucide="message-square" class="h-3.5 w-3.5"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-medium">{{ $h['title'] }}</p>
                        <p class="text-[10px] text-muted-foreground">{{ $h['time'] }}</p>
                    </div>
                    <i data-lucide="trash-2" class="h-3.5 w-3.5 text-muted-foreground opacity-0 transition-opacity group-hover:opacity-100"></i>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Prompts -->
        <div class="glass rounded-2xl p-5 shadow-soft">
            <div class="flex items-center gap-2">
                <i data-lucide="sparkles" class="h-4 w-4 text-primary"></i>
                <h3 class="text-sm font-semibold">Gợi ý câu hỏi</h3>
            </div>
            <div class="mt-3 space-y-2">
                @php
                    $prompts = [
                        "Phân tích chỉ số sức khỏe tổng thể của tôi",
                        "Gợi ý thực đơn 1.800 kcal tăng cơ",
                        "Bài tập 20 phút giảm mỡ bụng tại nhà",
                        "Tôi nên ngủ mấy giờ để hồi phục tốt?",
                    ];
                @endphp
                @foreach($prompts as $p)
                <button class="w-full rounded-xl border border-border bg-card/40 p-3 text-left text-xs transition-all hover:border-primary hover:shadow-soft">
                    {{ $p }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="lg:col-span-2">
        <div class="glass flex h-[600px] flex-col overflow-hidden rounded-2xl shadow-elevated">
            <!-- Chat Header -->
            <div class="flex items-center justify-between border-b border-border/50 bg-card/50 px-5 py-4 backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <div class="relative flex h-10 w-10 items-center justify-center rounded-xl gradient-primary text-white shadow-glow">
                        <i data-lucide="bot" class="h-5 w-5"></i>
                        <span class="absolute -right-1 -top-1 flex h-3 w-3">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success opacity-75"></span>
                            <span class="relative inline-flex h-3 w-3 rounded-full bg-success ring-2 ring-card"></span>
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold">HealthAI Assistant</h3>
                        <p class="text-[10px] text-success font-medium">Trực tuyến</p>
                    </div>
                </div>
            </div>

            <!-- Chat Messages (Placeholder) -->
            <div class="flex-1 overflow-y-auto p-5 space-y-6">
                <!-- AI Welcome Message -->
                <div class="flex items-start gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg gradient-primary text-white shadow-soft">
                        <i data-lucide="bot" class="h-4 w-4"></i>
                    </div>
                    <div class="max-w-[85%] rounded-2xl rounded-tl-none border border-border/50 bg-card/50 p-4 shadow-sm backdrop-blur-sm">
                        <div class="prose prose-sm dark:prose-invert">
                            <p>Xin chào! Tôi là HealthAI Assistant. Hôm nay tôi có thể giúp gì cho sức khỏe của bạn?</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="border-t border-border/50 bg-card/50 p-4 backdrop-blur-md">
                <div class="relative flex items-end gap-2 rounded-2xl border border-border bg-background/50 p-2 shadow-sm transition-colors focus-within:border-primary/50 focus-within:bg-background">
                    <button class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-muted-foreground hover:bg-accent hover:text-foreground">
                        <i data-lucide="paperclip" class="h-4 w-4"></i>
                    </button>
                    <textarea
                        placeholder="Hỏi AI về sức khỏe, thực đơn..."
                        class="max-h-32 min-h-10 w-full resize-none bg-transparent py-2.5 text-sm outline-none placeholder:text-muted-foreground"
                        rows="1"
                    ></textarea>
                    <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl gradient-primary text-white shadow-soft transition-transform hover:scale-105 active:scale-95">
                        <i data-lucide="send" class="h-4 w-4"></i>
                    </button>
                </div>
                <div class="mt-2 text-center text-[10px] text-muted-foreground">
                    AI có thể cung cấp thông tin y tế không chính xác. Hãy luôn tham khảo ý kiến bác sĩ.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
