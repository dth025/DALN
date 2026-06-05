# HealthAI - Smart AI Health Hub 🚀

**HealthAI** là một nền tảng quản lý sức khỏe toàn diện được xây dựng trên nền tảng Laravel, giúp người dùng theo dõi chỉ số cơ thể, lập kế hoạch tập luyện và tương tác với trợ lý sức khỏe AI.

---

## ✨ Tính năng chính

- 📊 **Dashboard Thông Minh**: Theo dõi tổng quan các chỉ số sức khỏe, calo tiêu thụ và hoạt động trong ngày.
- 📉 **Theo Dõi Chỉ Số (Health Tracking)**: Ghi lại cân nặng, nhịp tim, số bước chân và các chỉ số sinh tồn khác.
- 🏋️ **Kế Hoạch Tập Luyện (Workouts)**: Quản lý các bài tập cá nhân hóa và lịch trình vận động.
- 📅 **Đặt Lịch Hẹn (Appointments)**: Quản lý lịch khám bệnh hoặc tư vấn với chuyên gia.
- 🤖 **Trợ Lý AI (AI Chatbot)**: Tư vấn sức khỏe, dinh dưỡng và giải đáp thắc mắc thông qua trí tuệ nhân tạo.
- 🥗 **Thực Đơn (Menu/Meal Plans)**: Lên kế hoạch ăn uống khoa học và theo dõi dinh dưỡng.
- 🥗 **Thực Đơn (Menu/Meal Plans)**: Lên kế hoạch ăn uống khoa học và theo dõi dinh dưỡng.

### Thực Đơn nâng cao

- Đa dạng hơn: thư viện mẫu thực đơn theo chế độ (kiểm soát cân nặng, tiểu đường, tim mạch, chay, cao protein, low-carb), cùng thông tin dinh dưỡng (calories, macro, micro).
- Templates & tuần mẫu: tạo thực đơn theo ngày/tuần, lưu làm mẫu và tái sử dụng.
- Tùy biến bác sĩ: `Doctor` có thể tạo và gán thực đơn cho bệnh nhân dựa trên `MedicalRecord` và `HealthMetric` (ví dụ: chế độ giảm đường cho bệnh nhân tiểu đường).
- AI đề xuất: hệ thống có thể gợi ý thực đơn tự động dựa trên hồ sơ sức khỏe, dị ứng, và mục tiêu người dùng.
- Xuất/Chia sẻ: cho phép xuất PDF/CSV để lưu hoặc in cho bệnh nhân.

### Hướng dẫn nhanh cho bác sĩ

1. Đăng nhập bằng tài khoản bác sĩ.
2. Chọn `Thực Đơn > Tạo mới` và điền tiêu đề, mô tả, các bữa trong ngày, và tag dinh dưỡng.
3. Gán thực đơn cho bệnh nhân cụ thể hoặc lưu làm mẫu cho các bệnh nhân tương tự.
4. Hệ thống sẽ lưu `doctor_id` để truy xuất lịch sử và kiểm toán.

Gợi ý triển khai: thêm model `MealPlan`, migration, controller và route bảo vệ qua middleware `auth` + kiểm tra vai trò bác sĩ. Tạo seeder mẫu chứa nhiều thực đơn đa dạng để dễ thử nghiệm.

---

## 🛠 Công nghệ sử dụng

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS, Vite
- **Database**: SQLite (Mặc định)
- **Authentication**: Laravel Breeze

---

## 🚀 Hướng dẫn cài đặt

1. **Clone dự án và vào thư mục dự án:**
   ```bash
   cd healthai-laravel
   ```

2. **Cài đặt các thư viện PHP:**
   ```bash
   composer install
   ```

3. **Cài đặt các thư viện Frontend:**
   ```bash
   npm install
   ```

4. **Cấu hình môi trường:**
   - Copy file `.env.example` thành `.env`
   - Chạy lệnh tạo key: `php artisan key:generate`

5. **Khởi tạo Database:**
   ```bash
   php artisan migrate --seed
   ```

6. **Chạy ứng dụng:**
   - Chạy server backend: `php artisan serve`
   - Chạy server frontend: `npm run dev`

---

## 🔑 Tài khoản đăng nhập mặc định

- **Email**: `admin@gmail.com`
- **Mật khẩu**: `123456`

