@extends('layouts.dashboard')

@section('title', 'Lịch khám — HealthAI')

@section('content')
<!-- Page Header -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl gradient-primary text-primary-foreground shadow-glow">
            <i data-lucide="calendar-days" class="h-5 w-5"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-tight md:text-3xl font-display">Lịch khám</h1>
            <p class="mt-1 text-sm text-muted-foreground">Quản lý các cuộc hẹn y tế của bạn</p>
        </div>
    </div>
    <button id="openBookingBtn" class="flex items-center gap-2 rounded-xl gradient-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground shadow-glow hover:scale-[1.02] transition-transform">
        <i data-lucide="plus" class="h-4 w-4"></i> Đặt lịch khám
    </button>
</div>

<!-- Booking Modal -->
<div id="bookingModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity">
    <div class="relative w-full max-w-md rounded-2xl bg-card border border-border p-6 shadow-elevated text-foreground">
        <button onclick="closeBookingModal()" class="absolute right-4 top-4 text-muted-foreground hover:text-foreground transition-colors">✕</button>
        <h3 class="text-lg font-bold mb-4 font-display">Đặt lịch khám</h3>

        <form id="bookingForm" method="POST" action="{{ route('appointments.book') }}">
            @csrf
            <input type="hidden" name="doctor_id" id="booking_doctor_id" value="{{ old('doctor_id') }}" />

            <div id="bookingDoctorInfo" class="mb-4 rounded-2xl border border-border bg-muted/40 p-4" style="display: none;">
                <div class="flex items-center gap-3">
                    <img id="booking_doctor_avatar" src="https://ui-avatars.com/api/?name=Doctor&background=94a3b8&color=fff" alt="Doctor Avatar" class="h-14 w-14 rounded-2xl object-cover ring-2 ring-border" />
                    <div class="min-w-0">
                        <p id="booking_doctor_title" class="text-sm font-semibold text-foreground">Chưa chọn bác sĩ</p>
                        <p id="booking_doctor_specialty" class="text-xs text-muted-foreground">Chuyên ngành</p>
                    </div>
                </div>
                <div class="mt-3 grid gap-2 text-xs text-muted-foreground">
                    <div id="booking_doctor_place" class="flex items-center gap-2"><i data-lucide="map-pin" class="h-4 w-4 text-primary"></i><span>Địa điểm</span></div>
                    <div id="booking_doctor_phone" class="flex items-center gap-2"><i data-lucide="phone" class="h-4 w-4 text-primary"></i><span>Điện thoại</span></div>
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-xs text-muted-foreground mb-1">Bác sĩ đã chọn</label>
                <input type="text" id="booking_doctor_name" class="w-full rounded-xl border border-border px-3 py-2 bg-muted/20 text-sm text-foreground outline-none cursor-not-allowed" readonly placeholder="Chọn bác sĩ từ danh sách bên dưới" value="{{ $selectedDoctor ? $selectedDoctor->name . ' - ' . $selectedDoctor->specialty : '' }}" />
            </div>

            <div class="mb-3">
                <label class="block text-xs text-muted-foreground mb-1">Hoặc chọn bác sĩ</label>
                <select id="booking_doctor_select" class="w-full rounded-xl border border-border bg-card text-foreground px-3 py-2 text-sm outline-none focus:border-primary/40 focus:ring-4 focus:ring-primary/10 transition-all" onchange="syncSelectedDoctor(this)">
                    <option value="" class="bg-card text-foreground">-- Chọn bác sĩ --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" data-name="{{ $doctor->name }}" data-specialty="{{ $doctor->specialty }}" data-place="{{ $doctor->place }}" data-phone="{{ $doctor->phone }}" data-avatar="{{ $doctor->avatar }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }} class="bg-card text-foreground">{{ $doctor->name }} - {{ $doctor->specialty }}</option>
                    @endforeach
                </select>
                @error('doctor_id')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs text-muted-foreground mb-1">Ngày khám</label>
                <input type="date" name="appointment_date" id="booking_appointment_date" class="w-full rounded-xl border border-border bg-card text-foreground px-3 py-2 text-sm outline-none focus:border-primary/40 focus:ring-4 focus:ring-primary/10 transition-all" value="{{ old('appointment_date') ? \Carbon\Carbon::parse(old('appointment_date'))->toDateString() : '' }}" required />
                @error('appointment_date')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-xs text-muted-foreground mb-1">Giờ khám</label>
                <input type="time" name="appointment_time" id="booking_appointment_time" class="w-full rounded-xl border border-border bg-card text-foreground px-3 py-2 text-sm outline-none focus:border-primary/40 focus:ring-4 focus:ring-primary/10 transition-all" value="{{ old('appointment_time') }}" required />
                @error('appointment_time')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeBookingModal()" class="rounded-xl border border-border bg-card hover:bg-muted/50 text-foreground px-4 py-2 text-sm font-medium transition-colors">Hủy</button>
                <button type="submit" class="rounded-xl gradient-primary px-4 py-2 text-sm font-bold text-white shadow-glow hover:scale-[1.02] transition-transform">Xác nhận</button>
            </div>
        </form>
    </div>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity">
    <div class="relative w-full max-w-md rounded-2xl bg-card border border-border p-6 shadow-elevated text-foreground">
        <button onclick="closeRescheduleModal()" class="absolute right-4 top-4 text-muted-foreground hover:text-foreground transition-colors">✕</button>
        <h3 class="text-lg font-bold mb-4 font-display">Đổi lịch khám</h3>

        <form id="rescheduleForm" method="POST" action="">
            @csrf
            <div class="mb-3">
                <label class="block text-xs text-muted-foreground mb-1">Bác sĩ</label>
                <input type="text" id="reschedule_doctor_name" class="w-full rounded-xl border border-border px-3 py-2 bg-muted/20 text-sm text-foreground outline-none cursor-not-allowed" readonly />
            </div>

            <div class="mb-4">
                <label class="block text-xs text-muted-foreground mb-1">Ngày & giờ mới</label>
                <input type="datetime-local" name="appointment_date" id="reschedule_appointment_date" class="w-full rounded-xl border border-border bg-card text-foreground px-3 py-2 text-sm outline-none focus:border-primary/40 focus:ring-4 focus:ring-primary/10 transition-all" required />
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeRescheduleModal()" class="rounded-xl border border-border bg-card hover:bg-muted/50 text-foreground px-4 py-2 text-sm font-medium transition-colors">Hủy</button>
                <button type="submit" class="rounded-xl gradient-primary px-4 py-2 text-sm font-bold text-white shadow-glow hover:scale-[1.02] transition-transform">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
function openBookingModal(doctorId, doctorName, specialty, place, phone, avatar) {
    const hiddenDoctor = document.getElementById('booking_doctor_id');
    const doctorNameInput = document.getElementById('booking_doctor_name');
    const doctorSelect = document.getElementById('booking_doctor_select');
    const doctorInfo = document.getElementById('bookingDoctorInfo');
    const doctorTitle = document.getElementById('booking_doctor_title');
    const doctorSpecialty = document.getElementById('booking_doctor_specialty');
    const doctorPlace = document.getElementById('booking_doctor_place');
    const doctorPhone = document.getElementById('booking_doctor_phone');
    const doctorAvatar = document.getElementById('booking_doctor_avatar');

    if (hiddenDoctor) {
        hiddenDoctor.value = doctorId || '';
    }
    if (doctorNameInput) {
        doctorNameInput.value = doctorName || '';
    }
    if (doctorSelect) {
        doctorSelect.value = doctorId || '';
    }
    if (doctorInfo) {
        doctorInfo.style.display = doctorId ? 'block' : 'none';
    }
    if (doctorTitle) {
        doctorTitle.textContent = doctorName || 'Chưa chọn bác sĩ';
    }
    if (doctorSpecialty) {
        doctorSpecialty.textContent = specialty || 'Chuyên ngành';
    }
    if (doctorPlace) {
        doctorPlace.querySelector('span').textContent = place || 'Địa điểm';
    }
    if (doctorPhone) {
        doctorPhone.querySelector('span').textContent = phone || 'Điện thoại';
    }
    if (doctorAvatar && avatar) {
        doctorAvatar.src = avatar;
    }

    document.getElementById('bookingModal').style.display = 'flex';
}

function syncSelectedDoctor(select) {
    const hiddenDoctor = document.getElementById('booking_doctor_id');
    const doctorNameInput = document.getElementById('booking_doctor_name');
    const doctorInfo = document.getElementById('bookingDoctorInfo');
    const doctorTitle = document.getElementById('booking_doctor_title');
    const doctorSpecialty = document.getElementById('booking_doctor_specialty');
    const doctorPlace = document.getElementById('booking_doctor_place');
    const doctorPhone = document.getElementById('booking_doctor_phone');
    const doctorAvatar = document.getElementById('booking_doctor_avatar');

    const selectedOption = select.options[select.selectedIndex];
    const selectedText = selectedOption?.text || '';
    const specialty = selectedOption?.dataset.specialty || '';
    const place = selectedOption?.dataset.place || '';
    const phone = selectedOption?.dataset.phone || '';
    const avatar = selectedOption?.dataset.avatar || '';

    if (hiddenDoctor) {
        hiddenDoctor.value = select.value;
    }
    if (doctorNameInput) {
        doctorNameInput.value = selectedText;
    }
    if (doctorInfo) {
        doctorInfo.style.display = select.value ? 'block' : 'none';
    }
    if (doctorTitle) {
        doctorTitle.textContent = selectedText || 'Chưa chọn bác sĩ';
    }
    if (doctorSpecialty) {
        doctorSpecialty.textContent = specialty || 'Chuyên ngành';
    }
    if (doctorPlace) {
        doctorPlace.querySelector('span').textContent = place || 'Địa điểm';
    }
    if (doctorPhone) {
        doctorPhone.querySelector('span').textContent = phone || 'Điện thoại';
    }
    if (doctorAvatar && avatar) {
        doctorAvatar.src = avatar;
    }
}

function closeBookingModal() {
    document.getElementById('bookingModal').style.display = 'none';
}

function openRescheduleModal(appointmentId, doctorName, currentDateTime) {
    document.getElementById('reschedule_doctor_name').value = doctorName || '';
    document.getElementById('reschedule_appointment_date').value = currentDateTime || '';
    document.getElementById('rescheduleForm').action = '/appointments/' + appointmentId + '/reschedule';
    document.getElementById('rescheduleModal').style.display = 'flex';
}

function closeRescheduleModal() {
    document.getElementById('rescheduleModal').style.display = 'none';
}

// Attach click handlers to doctor choose buttons
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.choose-doctor-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            const id = this.getAttribute('data-doctor-id');
            const name = this.getAttribute('data-doctor-name');
            const specialty = this.getAttribute('data-doctor-specialty');
            const place = this.getAttribute('data-doctor-place');
            const phone = this.getAttribute('data-doctor-phone');
            const avatar = this.getAttribute('data-doctor-avatar');
            openBookingModal(id, name, specialty, place, phone, avatar);
        });
    });
    document.querySelectorAll('.reschedule-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const appointmentId = this.getAttribute('data-appointment-id');
            const doctorName = this.getAttribute('data-doctor-name');
            const currentDateTime = this.getAttribute('data-appointment-date');
            openRescheduleModal(appointmentId, doctorName, currentDateTime);
        });
    });

    // Main "Đặt lịch khám" button opens modal without preselecting doctor
    const mainBtn = document.getElementById('openBookingBtn');
    if (mainBtn) mainBtn.addEventListener('click', function(){ openBookingModal('', ''); });
});
</script>

<!-- Available Doctors -->
<div class="glass rounded-2xl p-5 shadow-soft mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-base font-semibold">Bác sĩ sẵn sàng đặt lịch</h3>
            <p class="text-xs text-muted-foreground">Các bác sĩ vừa đăng ký sẽ xuất hiện tại đây</p>
        </div>
    </div>

    @if($doctors->isEmpty())
        <div class="rounded-2xl border border-border bg-card/50 p-6 text-sm text-muted-foreground">
            Hiện chưa có bác sĩ nào sẵn sàng đặt lịch. Vui lòng thử lại sau.
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($doctors as $doctor)
                <div class="glass rounded-3xl p-4 shadow-soft transition-transform hover:-translate-y-1">
                    <div class="flex items-center gap-3">
                        <img src="{{ $doctor->avatar }}" alt="{{ $doctor->name }}" class="h-14 w-14 rounded-2xl object-cover ring-2 ring-border" />
                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold truncate">{{ $doctor->name }}</h4>
                            <p class="text-xs text-muted-foreground truncate">{{ $doctor->specialty }}</p>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-muted-foreground space-y-2">
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="h-3.5 w-3.5"></i>
                            <span>{{ $doctor->place }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="phone" class="h-3.5 w-3.5"></i>
                            <span>{{ $doctor->phone }}</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between gap-2">
                        <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-[11px] font-semibold text-emerald-400">{{ ucfirst($doctor->status) }}</span>
                        <button
                            data-doctor-id="{{ $doctor->id }}"
                            data-doctor-name="{{ $doctor->name }}"
                            data-doctor-specialty="{{ $doctor->specialty }}"
                            data-doctor-place="{{ $doctor->place }}"
                            data-doctor-phone="{{ $doctor->phone }}"
                            data-doctor-avatar="{{ $doctor->avatar }}"
                            class="choose-doctor-btn rounded-xl border border-border px-3 py-2 text-xs font-medium hover:bg-accent"
                        >Chọn bác sĩ</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Calendar Mini -->
<div class="glass rounded-2xl p-5 shadow-soft mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold">Tháng 5, 2026</h3>
            <p class="text-xs text-muted-foreground">Tuần 19 · 3 cuộc hẹn</p>
        </div>
        <div class="flex gap-1">
            <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-border hover:bg-accent">
                <i data-lucide="chevron-left" class="h-4 w-4"></i>
            </button>
            <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-border hover:bg-accent">
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
            </button>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-7 gap-2">
        @php
            $days = ["CN", "T2", "T3", "T4", "T5", "T6", "T7"];
            $dates = [4, 5, 6, 7, 8, 9, 10];
            $events = [false, true, false, false, true, false, true];
        @endphp
        @foreach($days as $i => $d)
        <div class="text-center text-[10px] font-semibold uppercase text-muted-foreground">
            <p>{{ $d }}</p>
            <button class="mt-2 flex aspect-square w-full flex-col items-center justify-center rounded-xl border transition-all hover:scale-105 active:scale-95 {{ $i === 4 ? 'gradient-primary border-transparent text-primary-foreground shadow-glow' : 'border-border bg-card/40 text-foreground hover:border-primary/40' }}">
                <span class="text-base font-bold">{{ $dates[$i] }}</span>
                @if($events[$i])
                <span class="mt-0.5 h-1 w-1 rounded-full {{ $i === 4 ? 'bg-white' : 'bg-primary' }}"></span>
                @endif
            </button>
        </div>
        @endforeach
    </div>
</div>

<!-- Upcoming Appointments -->
<div class="space-y-3">
    <h3 class="text-sm font-semibold uppercase tracking-wider text-muted-foreground">Cuộc hẹn sắp tới</h3>
    
    @if($upcomingAppointments->isEmpty())
        <div class="rounded-2xl border border-border bg-card/50 p-6 text-sm text-muted-foreground">
            Bạn chưa có cuộc hẹn nào. Hãy chọn bác sĩ và đặt lịch khám ngay.
        </div>
    @else
        @foreach($upcomingAppointments as $appointment)
            <div class="glass flex flex-col gap-4 rounded-2xl p-4 shadow-soft md:flex-row md:items-center transition-transform hover:translate-x-1">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500 to-pink-400 text-white shadow-soft">
                    <i data-lucide="stethoscope" class="h-6 w-6"></i>
                </div>

                <div class="flex flex-1 items-center gap-3">
                    <img src="{{ $appointment->doctor?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($appointment->doctor_name) . '&background=0284c7&color=fff' }}" alt="{{ $appointment->doctor_name }}" class="h-12 w-12 rounded-xl object-cover ring-2 ring-border" />
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-semibold">{{ $appointment->doctor_name }}</h4>
                        <p class="text-xs text-muted-foreground">{{ $appointment->specialty }}</p>
                    </div>
                </div>

                @if($appointment->status === 'rescheduled_pending')
                    <div class="flex flex-col gap-1 flex-1 min-w-[200px] border border-indigo-500/20 bg-indigo-500/5 p-3 rounded-xl">
                        <p class="text-xs text-indigo-400 font-bold flex items-center gap-1">
                            <i data-lucide="info" class="h-3.5 w-3.5"></i> Bác sĩ đề xuất lịch khám mới
                        </p>
                        <div class="grid grid-cols-2 gap-2 text-xs mt-1 text-foreground">
                            <div class="flex items-center gap-1.5 font-semibold">
                                <i data-lucide="calendar" class="h-3.5 w-3.5 text-indigo-400"></i>
                                {{ \Carbon\Carbon::parse($appointment->proposed_date)->translatedFormat('d M, Y') }}
                            </div>
                            <div class="flex items-center gap-1.5 font-bold">
                                <i data-lucide="clock" class="h-3.5 w-3.5 text-indigo-400"></i>
                                {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-3 text-xs md:flex md:items-center md:gap-5">
                        <div class="flex items-center gap-1.5 text-muted-foreground">
                            <i data-lucide="calendar-days" class="h-3.5 w-3.5"></i> {{ \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('d M, Y') }}
                        </div>
                        <div class="flex items-center gap-1.5 text-muted-foreground">
                            <i data-lucide="clock" class="h-3.5 w-3.5"></i> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}
                        </div>
                        <div class="flex items-center gap-1.5 text-muted-foreground">
                            <i data-lucide="map-pin" class="h-3.5 w-3.5"></i>
                            {{ $appointment->doctor->place ?? 'Địa điểm chưa xác định' }}
                        </div>
                    </div>
                @endif

                <div class="flex gap-2">
                    @if($appointment->status === 'rescheduled_pending')
                        <form method="POST" action="{{ route('appointments.acceptReschedule', $appointment->id) }}">
                            @csrf
                            <button type="submit" class="rounded-lg gradient-primary px-3 py-1.5 text-xs font-semibold text-primary-foreground shadow-soft hover:scale-[1.02] transition-transform">
                                Đồng ý
                            </button>
                        </form>
                        <form method="POST" action="{{ route('appointments.declineReschedule', $appointment->id) }}">
                            @csrf
                            <button type="submit" class="rounded-lg border border-rose-500/30 bg-rose-500/5 hover:bg-rose-500 hover:text-white text-rose-400 px-3 py-1.5 text-xs font-medium transition-all">
                                Từ chối
                            </button>
                        </form>
                    @else
                        <button type="button" data-appointment-id="{{ $appointment->id }}" data-doctor-name="{{ $appointment->doctor_name }}" data-appointment-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d\TH:i') }}" class="reschedule-btn rounded-lg border border-border px-3 py-1.5 text-xs font-medium hover:bg-accent">
                            Đổi lịch
                        </button>
                        <button class="rounded-lg gradient-primary px-3 py-1.5 text-xs font-semibold text-primary-foreground shadow-soft">
                            Tham gia
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
