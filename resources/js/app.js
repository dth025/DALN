import './bootstrap';
import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;

// Chỉ khởi động Alpine sau khi DOM đã sẵn sàng —
// đảm bảo các script inline (ví dụ: `healthTracker()` trong view)
// đã được định nghĩa trước khi Alpine truy xuất chúng.
document.addEventListener('DOMContentLoaded', () => {
    // Render Lucide icons
    try { createIcons({ icons }); } catch (e) { /* ignore */ }

    // Bắt đầu Alpine sau khi DOM và các script inline đã load
    try { Alpine.start(); } catch (e) { console.warn('Alpine start failed', e); }
});

// Fallback: nếu DOMContentLoaded đã qua, khởi động ngay
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    try { createIcons({ icons }); } catch (e) {}
    try { Alpine.start(); } catch (e) {}
}
