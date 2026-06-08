<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký Bác sĩ — HealthAI</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: #0b1329;
            color: #f8fafc;
        }
        .glass {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .gradient-primary {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .shadow-glow {
            box-shadow: 0 0 25px -5px rgba(2, 132, 199, 0.25);
        }
    </style>
</head>
<body class="antialiased min-h-screen relative flex items-center justify-center p-4 md:p-8 overflow-x-hidden">
    
    <!-- Background Glow Patterns -->
    <div class="pointer-events-none fixed inset-0 -z-10" style="background: radial-gradient(circle at 50% 50%, #0f172a 0%, #020617 100%);"></div>
    <div class="pointer-events-none fixed -top-40 -right-40 -z-10 h-96 w-96 rounded-full bg-sky-500/10 blur-[120px]"></div>
    <div class="pointer-events-none fixed -bottom-40 -left-40 -z-10 h-96 w-96 rounded-full bg-indigo-500/10 blur-[120px]"></div>

    <div class="w-full max-w-2xl glass rounded-3xl p-6 md:p-10 shadow-glow relative overflow-hidden my-8">
        <div class="absolute -right-20 -top-20 h-52 w-52 rounded-full bg-gradient-to-br from-sky-500 to-sky-700 opacity-10 blur-3xl pointer-events-none"></div>

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl gradient-primary text-white shadow-glow mb-4">
                <i data-lucide="stethoscope" class="h-6 w-6"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Đăng ký tài khoản <span class="gradient-text">Bác sĩ</span></h1>
            <p class="text-slate-400 text-xs mt-2">Tham gia mạng lưới y tế thông minh HealthAI để đồng hành cùng bệnh nhân</p>
        </div>

        @if ($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4 mb-6 text-rose-400 text-xs font-semibold">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('doctor.register.submit') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="space-y-1.5">
                    <label for="name" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Họ và tên bác sĩ</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="VD: BS. Nguyễn Văn Minh" required class="h-11 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-10 pr-4 text-xs font-semibold outline-none transition-all focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10">
                    </div>
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Địa chỉ Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="VD: nguyenvanminh@healthai.vn" required class="h-11 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-10 pr-4 text-xs font-semibold outline-none transition-all focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Phone -->
                <div class="space-y-1.5">
                    <label for="phone" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Số điện thoại liên hệ</label>
                    <div class="relative">
                        <i data-lucide="phone" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="VD: 0912111222" required class="h-11 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-10 pr-4 text-xs font-semibold outline-none transition-all focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10">
                    </div>
                </div>

                <!-- Specialty -->
                <div class="space-y-1.5">
                    <label for="specialty" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Chuyên khoa khám</label>
                    <div class="relative">
                        <i data-lucide="activity" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input id="specialty" name="specialty" type="text" value="{{ old('specialty') }}" placeholder="VD: Tim mạch, Dinh dưỡng, Da liễu..." required class="h-11 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-10 pr-4 text-xs font-semibold outline-none transition-all focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Place/Hospital -->
                <div class="space-y-1.5">
                    <label for="place" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Nơi công tác / Bệnh viện</label>
                    <div class="relative">
                        <i data-lucide="building" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input id="place" name="place" type="text" value="{{ old('place') }}" placeholder="VD: Vinmec, Bệnh viện Bạch Mai..." required class="h-11 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-10 pr-4 text-xs font-semibold outline-none transition-all focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10">
                    </div>
                </div>

                <!-- Avatar Link -->
                <div class="space-y-1.5">
                    <label for="avatar" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Ảnh đại diện (Upload)</label>
                    <div class="relative">
                        <i data-lucide="image" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input id="avatar" name="avatar" type="file" accept="image/*" class="h-11 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-10 pr-4 text-xs font-semibold outline-none transition-all focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10" style="padding-top: 0.6rem;">
                    </div>
                </div>
            </div>

            <!-- Clinic Address -->
            <div class="space-y-1.5">
                <label for="address" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Địa chỉ chi tiết / Phòng khám riêng</label>
                <div class="relative">
                    <i data-lucide="map-pin" class="absolute left-3.5 top-3 h-4 w-4 text-slate-400"></i>
                    <textarea id="address" name="address" rows="2" placeholder="Nhập địa chỉ chi tiết phòng khám..." class="w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-10 pr-4 py-2 text-xs font-semibold outline-none transition-all focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Mật khẩu</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input id="password" name="password" type="password" required class="h-11 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-10 pr-4 text-xs font-semibold outline-none transition-all focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10">
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Nhập lại mật khẩu</label>
                    <div class="relative">
                        <i data-lucide="shield-check" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="h-11 w-full rounded-xl border border-slate-700 bg-slate-900/50 pl-10 pr-4 text-xs font-semibold outline-none transition-all focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full h-11 rounded-xl gradient-primary font-bold text-xs uppercase tracking-wider text-white shadow-glow hover:opacity-90 active:scale-[0.98] transition-all">
                Đăng ký tài khoản
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 text-center border-t border-slate-800 pt-6 text-xs">
            <span class="text-slate-400">Đã có tài khoản bác sĩ?</span>
            <a href="{{ route('doctor.login') }}" class="text-sky-400 hover:text-sky-300 font-bold ml-1 transition-colors">Đăng nhập ngay</a>
        </div>
    </div>

    <script>
        // Init Lucide
        lucide.createIcons();
    </script>
</body>
</html>
