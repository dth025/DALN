<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Đồng bộ chỉ số sức khỏe · HealthAI</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0d0d14;
            color: #f0f0f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .card {
            background: #16161f;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 32px;
            padding: 36px 28px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.5);
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }
        .logo-icon {
            width: 48px; height: 48px;
            border-radius: 16px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            box-shadow: 0 0 20px rgba(99,102,241,0.4);
        }
        .logo-text { font-size: 20px; font-weight: 900; }
        .logo-text span { background: linear-gradient(135deg, #6366f1, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        h2 { font-size: 22px; font-weight: 900; margin-bottom: 6px; }
        .sub { font-size: 12px; color: rgba(255,255,255,0.45); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 28px; }
        .section-label {
            font-size: 10px; font-weight: 900; text-transform: uppercase;
            letter-spacing: 0.15em; color: #6366f1; margin-bottom: 12px;
        }
        .fields { display: grid; gap: 14px; margin-bottom: 24px; }
        .field label {
            display: block; font-size: 10px; font-weight: 900; text-transform: uppercase;
            letter-spacing: 0.1em; color: rgba(255,255,255,0.5); margin-bottom: 6px;
        }
        .input-wrap { position: relative; }
        .input-wrap .icon {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            font-size: 16px;
        }
        input[type="number"] {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 16px 16px 16px 48px;
            font-size: 18px;
            font-weight: 900;
            font-family: inherit;
            color: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            -webkit-appearance: none;
        }
        input[type="number"]:focus {
            border-color: rgba(99,102,241,0.5);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .divider {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 20px 0;
        }
        button[type="submit"] {
            width: 100%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 20px;
            padding: 18px;
            font-size: 13px;
            font-weight: 900;
            font-family: inherit;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            cursor: pointer;
            box-shadow: 0 0 30px rgba(99,102,241,0.4);
            transition: transform 0.15s, box-shadow 0.15s;
        }
        button[type="submit"]:active { transform: scale(0.97); }
        button[type="submit"]:disabled { opacity: 0.6; cursor: not-allowed; }
        .success {
            display: none;
            text-align: center;
            padding: 32px 0;
        }
        .success .check {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 36px;
            margin: 0 auto 20px;
            box-shadow: 0 0 40px rgba(34,197,94,0.4);
            animation: pop 0.4s cubic-bezier(0.34,1.56,0.64,1);
        }
        @keyframes pop {
            from { transform: scale(0); }
            to   { transform: scale(1); }
        }
        .success h3 { font-size: 22px; font-weight: 900; margin-bottom: 8px; }
        .success p  { font-size: 13px; color: rgba(255,255,255,0.5); }
        .timer { font-size: 10px; color: rgba(255,255,255,0.3); text-align: center; margin-top: 20px; font-weight: 700; }
        #countdown { color: #6366f1; font-weight: 900; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <div class="logo-icon">❤️</div>
        <div class="logo-text">Health<span>AI</span></div>
    </div>

    <div id="form-section">
        <h2>Đồng bộ chỉ số</h2>
        <p class="sub">Nhập chỉ số từ thiết bị của bạn</p>

        <form id="pair-form">
            <p class="section-label">Chỉ số sinh tồn</p>
            <div class="fields">
                <div class="field">
                    <label>Nhịp tim (BPM)</label>
                    <div class="input-wrap">
                        <span class="icon">❤️</span>
                        <input type="number" name="heart_rate" placeholder="72" min="30" max="250">
                    </div>
                </div>
                <div class="field">
                    <label>SpO₂ (%)</label>
                    <div class="input-wrap">
                        <span class="icon">💨</span>
                        <input type="number" name="spo2" placeholder="98" min="50" max="100">
                    </div>
                </div>
            </div>

            <div class="divider"></div>
            <p class="section-label">Chỉ số cơ thể</p>
            <div class="fields">
                <div class="grid-2">
                    <div class="field">
                        <label>Cân nặng (kg)</label>
                        <div class="input-wrap">
                            <span class="icon">⚖️</span>
                            <input type="number" name="weight" step="0.1" placeholder="65" min="1" max="500">
                        </div>
                    </div>
                    <div class="field">
                        <label>Chiều cao (cm)</label>
                        <div class="input-wrap">
                            <span class="icon">📏</span>
                            <input type="number" name="height" placeholder="170" min="50" max="300">
                        </div>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="field">
                        <label>Nước uống (L)</label>
                        <div class="input-wrap">
                            <span class="icon">💧</span>
                            <input type="number" name="water_intake" step="0.1" placeholder="2.0" min="0" max="20">
                        </div>
                    </div>
                    <div class="field">
                        <label>Giấc ngủ (Giờ)</label>
                        <div class="input-wrap">
                            <span class="icon">🌙</span>
                            <input type="number" name="sleep_hours" step="0.5" placeholder="7.5" min="0" max="24">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" id="submit-btn">
                ⚡ Đồng bộ ngay
            </button>
        </form>

        <p class="timer">Mã QR hết hạn sau <span id="countdown">6:00</span></p>
    </div>

    <div class="success" id="success-section">
        <div class="check">✓</div>
        <h3>Đồng bộ thành công!</h3>
        <p>Chỉ số đã được cập nhật trên HealthAI.<br>Bạn có thể đóng trang này.</p>
    </div>
</div>

<script>
    // Countdown timer
    let seconds = 360;
    const cd = document.getElementById('countdown');
    const iv = setInterval(() => {
        seconds--;
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        cd.textContent = `${m}:${s.toString().padStart(2,'0')}`;
        if (seconds <= 0) {
            clearInterval(iv);
            cd.textContent = 'Hết hạn';
        }
    }, 1000);

    // Submit form
    document.getElementById('pair-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.textContent = 'Đang đồng bộ...';

        const formData = new FormData(e.target);

        try {
            const res = await fetch('/pair/{{ $token }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            if (res.ok) {
                clearInterval(iv);
                document.getElementById('form-section').style.display = 'none';
                document.getElementById('success-section').style.display = 'block';
            } else {
                btn.disabled = false;
                btn.textContent = '⚡ Đồng bộ ngay';
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            }
        } catch(err) {
            btn.disabled = false;
            btn.textContent = '⚡ Đồng bộ ngay';
        }
    });
</script>
</body>
</html>
