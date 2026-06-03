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
                    'full_title' => 'Theo dõi Sức khỏe Chủ động',
                    'desc' => 'Đo lường cân nặng, huyết áp, đường huyết, giấc ngủ và phân tích xu hướng theo thời gian.',
                    'detailed_desc' => 'Hệ thống tự động ghi nhận và phân tích các chỉ số sinh tồn quan trọng của bạn. Chỉ cần nhập các số đo hàng ngày hoặc kết nối thiết bị đeo thông minh, AI sẽ tự động lập biểu đồ trực quan, phát hiện các bất thường sớm và đưa ra lời khuyên y tế hữu ích.',
                    'color' => 'from-rose-500 to-pink-500',
                    'list' => [
                        'Tự động vẽ biểu đồ trực quan xu hướng huyết áp, đường huyết',
                        'Cảnh báo sớm các chỉ số vượt ngưỡng an toàn',
                        'Theo dõi giấc ngủ sâu và nhịp tim sinh học',
                        'Xuất báo cáo PDF gửi trực tiếp cho bác sĩ của bạn'
                    ]
                ],
                [
                    'icon' => 'bot',
                    'title' => 'AI Chatbot',
                    'full_title' => 'Trợ lý Sức khỏe AI 24/7',
                    'desc' => 'Trợ lý ảo trả lời 24/7 về triệu chứng, dinh dưỡng và thói quen sức khỏe.',
                    'detailed_desc' => 'Trò chuyện với AI Chatbot được huấn luyện trên hàng triệu tài liệu y khoa chính thống. Bất cứ khi nào bạn cảm thấy không khỏe, cần tra cứu thông tin thuốc hay lên thực đơn lành mạnh, trợ lý ảo đều sẵn sàng phản hồi tức thì với độ chính xác cao.',
                    'color' => 'from-primary to-accent',
                    'list' => [
                        'Tư vấn triệu chứng lâm sàng và gợi ý hướng xử lý phù hợp',
                        'Giải đáp thắc mắc về liều lượng, tương tác thuốc',
                        'Phân tích chế độ ăn uống lành mạnh cá nhân hóa',
                        'Hoạt động 24/7, tuyệt đối bảo mật thông tin cuộc hội thoại'
                    ]
                ],
                [
                    'icon' => 'salad',
                    'title' => 'Thực đơn AI',
                    'full_title' => 'Thực đơn Cá nhân hóa AI',
                    'desc' => 'Gợi ý thực đơn cá nhân hóa theo BMI, mục tiêu và sở thích ăn uống.',
                    'detailed_desc' => 'Không còn nỗi lo "Hôm nay ăn gì?". AI của HealthAI sẽ tự động tính toán lượng Calorie cần thiết dựa trên chiều cao, cân nặng, tỷ lệ mỡ và mục tiêu của bạn (giảm cân, tăng cơ, giữ dáng) để thiết kế thực đơn chi tiết từng ngày.',
                    'color' => 'from-emerald-500 to-green-500',
                    'list' => [
                        'Tự động tính toán TDEE và Macros (Protein, Carb, Fat) lý tưởng',
                        'Thực đơn đa dạng theo sở thích (Eat Clean, Keto, Thuần chay, Truyền thống)',
                        'Gợi ý công thức chế biến chi tiết và danh sách nguyên liệu',
                        'Theo dõi lượng nước uống và calo nạp vào hàng ngày'
                    ]
                ],
                [
                    'icon' => 'dumbbell',
                    'title' => 'Luyện tập AI',
                    'full_title' => 'Kế hoạch Luyện tập Cá nhân',
                    'desc' => 'Bài tập được thiết kế riêng theo mức độ và lịch trình của bạn.',
                    'detailed_desc' => 'Tập luyện khoa học tại nhà hoặc phòng gym mà không cần PT đắt đỏ. AI thiết kế lộ trình tập luyện thích ứng thông minh: độ khó tự động điều chỉnh dựa trên phản hồi thể lực sau mỗi buổi tập của bạn.',
                    'color' => 'from-amber-500 to-orange-500',
                    'list' => [
                        'Lộ trình tập thích ứng theo mục tiêu (Tăng sức bền, giảm mỡ, săn chắc)',
                        'Hướng dẫn động tác trực quan bằng video chất lượng cao',
                        'Không cần dụng cụ phức tạp, tối ưu hóa không gian tập tại nhà',
                        'Thống kê lượng calo tiêu hao và thời gian tập chi tiết'
                    ]
                ],
                [
                    'icon' => 'calendar-days',
                    'title' => 'Lịch khám thông minh',
                    'full_title' => 'Lịch nhắc Y tế Thông minh',
                    'desc' => 'Đặt lịch với bác sĩ, nhắc nhở uống thuốc và theo dõi tái khám.',
                    'detailed_desc' => 'Quản lý toàn bộ lịch trình chăm sóc sức khỏe của cả gia đình. Nhận thông báo nhắc nhở uống thuốc đúng giờ, nhắc nhở lịch tiêm chủng định kỳ và dễ dàng kết nối đặt lịch hẹn khám trực tiếp với các bác sĩ chuyên khoa hàng đầu.',
                    'color' => 'from-violet-500 to-purple-500',
                    'list' => [
                        'Nhắc lịch uống thuốc tự động với hình ảnh nhận diện thuốc',
                        'Đặt lịch khám và tư vấn trực tuyến từ xa với bác sĩ',
                        'Nhắc nhở lịch tiêm phòng và kiểm tra sức khỏe định kỳ',
                        'Lưu trữ hồ sơ bệnh án, đơn thuốc số hóa trọn đời'
                    ]
                ],
                [
                    'icon' => 'shield-check',
                    'title' => 'Bảo mật tuyệt đối',
                    'full_title' => 'Bảo mật Y tế Đạt chuẩn Quốc tế',
                    'desc' => 'Dữ liệu sức khỏe được mã hoá end-to-end và tuân thủ chuẩn HIPAA.',
                    'detailed_desc' => 'Quyền riêng tư của bạn là ưu tiên hàng đầu của chúng tôi. Toàn bộ thông tin cá nhân, chỉ số sinh học và nội dung tư vấn sức khỏe đều được mã hóa bằng thuật toán quân sự AES-256 trước khi lưu trữ, đảm bảo chỉ có bạn mới có quyền truy cập.',
                    'color' => 'from-cyan-500 to-blue-500',
                    'list' => [
                        'Mã hóa đầu cuối (End-to-End Encryption) tuyệt đối an toàn',
                        'Tuân thủ nghiêm ngặt chuẩn bảo mật y tế quốc tế HIPAA & GDPR',
                        'Xác thực 2 lớp (2FA) bảo vệ tài khoản',
                        'Quyền kiểm soát và xóa dữ liệu vĩnh viễn bất cứ lúc nào'
                    ]
                ],
            ];
        @endphp

        @foreach($features as $index => $f)
        <div onclick="openFeatureModal({{ $index }})" class="group relative overflow-hidden rounded-3xl border border-border/50 bg-card/70 p-6 shadow-soft backdrop-blur-xl transition-all hover:-translate-y-1 hover:shadow-elevated cursor-pointer">
            <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-gradient-to-br {{ $f['color'] }} opacity-10 blur-2xl transition-opacity group-hover:opacity-25"></div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br {{ $f['color'] }} text-white shadow-glow">
                <i data-lucide="{{ $f['icon'] }}" class="h-5 w-5"></i>
            </div>
            <h3 class="mt-5 text-lg font-bold tracking-tight text-foreground">{{ $f['title'] }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $f['desc'] }}</p>
            <div class="mt-4 flex items-center gap-1 text-xs font-semibold text-primary transition-all group-hover:gap-2">
                Tìm hiểu thêm
                <i data-lucide="arrow-right" class="h-3 w-3"></i>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Beautiful Feature Detail Modal -->
    <div id="featureModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-background/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div id="modalOverlay" class="absolute inset-0 cursor-pointer" onclick="closeFeatureModal()"></div>
        <div id="modalContent" class="relative w-full max-w-2xl overflow-hidden rounded-3xl border border-border/50 bg-card/95 p-6 md:p-8 shadow-elevated backdrop-blur-2xl transition-all duration-300 transform scale-95 opacity-0">
            
            <!-- Glowing background effect -->
            <div id="modalGlow" class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-gradient-to-br opacity-10 blur-3xl pointer-events-none"></div>

            <!-- Close button -->
            <button onclick="closeFeatureModal()" class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full border border-border/40 bg-background/50 text-muted-foreground backdrop-blur-sm transition-all hover:bg-accent hover:text-foreground hover:scale-105">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>

            <!-- Icon & Header -->
            <div class="flex items-start gap-4 md:gap-5">
                <div id="modalIconContainer" class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br text-white shadow-glow">
                    <i id="modalIcon" data-lucide="activity" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 id="modalTitle" class="text-xl font-bold tracking-tight text-foreground md:text-2xl">Theo dõi Sức khỏe Chủ động</h3>
                    <p id="modalTagline" class="mt-1 text-xs font-medium text-primary uppercase tracking-wider">Tính năng hệ thống</p>
                </div>
            </div>

            <!-- Detailed Description -->
            <p id="modalDesc" class="mt-6 text-sm leading-relaxed text-muted-foreground md:text-base">
                Hệ thống tự động ghi nhận và phân tích các chỉ số sinh tồn quan trọng của bạn. Chỉ cần nhập các số đo hàng ngày hoặc kết nối thiết bị đeo thông minh, AI sẽ tự động lập biểu đồ trực quan, phát hiện các bất thường sớm và đưa ra lời khuyên y tế hữu ích.
            </p>

            <!-- Key Features Bullet List -->
            <div class="mt-6 border-t border-border/40 pt-6">
                <h4 class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-foreground">
                    <i data-lucide="sparkles" class="h-4 w-4 text-primary animate-pulse"></i>
                    Đặc điểm nổi bật
                </h4>
                <ul id="modalList" class="mt-4 grid gap-3 text-sm text-muted-foreground sm:grid-cols-2">
                    <!-- Dynamic bullet items will be appended here -->
                </ul>
            </div>

            <!-- Action Footer -->
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button onclick="closeFeatureModal()" class="rounded-2xl border border-border/60 bg-background/50 px-5 py-3 text-sm font-semibold text-foreground backdrop-blur-md transition-colors hover:bg-accent">
                    Đóng
                </button>
                <a id="modalActionBtn" href="{{ Auth::check() ? route('dashboard') : route('login') }}" class="group inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r px-6 py-3 text-sm font-semibold text-white shadow-glow transition-transform hover:scale-[1.02]">
                    Bắt đầu trải nghiệm
                    <i data-lucide="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle modal state -->
    <script>
        const featuresData = @json($features);

        function openFeatureModal(index) {
            const data = featuresData[index];
            if (!data) return;

            // Set content
            document.getElementById('modalTitle').innerText = data.full_title || data.title;
            document.getElementById('modalDesc').innerText = data.detailed_desc;
            
            // Set icon
            const modalIcon = document.getElementById('modalIcon');
            modalIcon.setAttribute('data-lucide', data.icon);
            
            // Set class colors
            const iconContainer = document.getElementById('modalIconContainer');
            // Remove previous gradient classes
            iconContainer.className = `flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br text-white shadow-glow ${data.color}`;
            
            const actionBtn = document.getElementById('modalActionBtn');
            actionBtn.className = `group inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r px-6 py-3 text-sm font-semibold text-white shadow-glow transition-transform hover:scale-[1.02] ${data.color}`;

            // Set modal glow color
            const modalGlow = document.getElementById('modalGlow');
            modalGlow.className = `absolute -right-20 -top-20 h-64 w-64 rounded-full bg-gradient-to-br opacity-10 blur-3xl pointer-events-none ${data.color}`;

            // Set bullets list
            const listEl = document.getElementById('modalList');
            listEl.innerHTML = '';
            data.list.forEach(item => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-2.5';
                
                // Beautiful checkmark icon
                li.innerHTML = `
                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>${item}</span>
                `;
                listEl.appendChild(li);
            });

            // Re-render Lucide icons
            if (window.lucide) {
                window.lucide.createIcons();
            }

            // Show modal with animation
            const modal = document.getElementById('featureModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100', 'pointer-events-auto');
            
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');

            // Prevent scrolling on body
            document.body.style.overflow = 'hidden';
        }

        function closeFeatureModal() {
            const modal = document.getElementById('featureModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            
            content.classList.add('scale-95', 'opacity-0');
            content.classList.remove('scale-100', 'opacity-100');

            // Restore scrolling on body
            document.body.style.overflow = '';
        }

        // Close on Esc key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFeatureModal();
            }
        });
    </script>
</section>
