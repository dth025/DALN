# HealthSync AI - Smart AI Health Hub 🚀

**HealthSync AI** là một nền tảng quản lý sức khỏe toàn diện và thông minh được xây dựng trên nền tảng Laravel. Dự án hỗ trợ người dùng theo dõi các chỉ số sinh tồn, lập kế hoạch tập luyện, nhận thực đơn dinh dưỡng cá nhân hóa và tư vấn trực tiếp với bác sĩ cũng như trợ lý sức khỏe AI (Generative AI).

---

## ✨ Tính năng chính

### 🧑‍💼 Dành cho Người dùng (Bệnh nhân)
- 📊 **Dashboard Thông Minh**: Theo dõi trực quan các chỉ số sức khỏe, calo tiêu thụ, giấc ngủ và hoạt động hàng ngày dưới dạng biểu đồ sinh động.
- 📉 **Theo Dõi Chỉ Số (Health Tracking)**: Ghi lại cân nặng, chiều cao, nhịp tim, SpO2, lượng nước uống, giấc ngủ và số bước chân.
- 🏋️ **Kế Hoạch Tập Luyện (Workouts)**: Ghi nhận và theo dõi các bài tập cá nhân hóa cùng lượng calo tiêu hao tương ứng.
- 📅 **Đặt Lịch Khám (Appointments)**: Đặt lịch hẹn khám bệnh trực tiếp hoặc trực tuyến với các bác sĩ chuyên khoa, hỗ trợ tính năng đề xuất đổi lịch từ bác sĩ.
- 🤖 **Trợ Lý AI (AI Chatbot)**: Nhận tư vấn về sức khỏe, dinh dưỡng và lối sống thông qua trợ lý ảo AI (sử dụng Gemini/OpenAI API).
- 🥗 **Thực Đơn Dinh Dưỡng (Meal Plans)**: Nhận thực đơn ăn uống khoa học được gợi ý bởi AI hoặc do bác sĩ trực tiếp chỉ định.
- 💬 **Tư Vấn Bác Sĩ (Consultations)**: Nhắn tin trao đổi trực tiếp và gửi tệp đính kèm với bác sĩ phụ trách.
- 📱 **Kết Nối Di Động (Mobile QR Pairing)**: Quét mã QR để đồng bộ hoặc đăng nhập nhanh trên thiết bị di động.
- 💬 **Đóng Góp Phản Hồi (Feedback)**: Đánh giá, thích/không thích và thảo luận về các tính năng của hệ thống.

### 🩺 Dành cho Bác sĩ (Doctor Portal)
- 📊 **Dashboard Bác Sĩ**: Quản lý danh sách bệnh nhân, xem biểu đồ lịch sử sức khỏe chi tiết của từng người.
- 📝 **Hồ Sơ Y Tế (Medical Records)**: Lập bệnh án, ghi nhận triệu chứng, chẩn đoán, kết quả khám và kê đơn thuốc cho bệnh nhân.
- 🥗 **Kế Hoạch Thực Đơn**: Tạo, lưu và gán các thực đơn dinh dưỡng mẫu (kiểm soát cân nặng, tiểu đường, tim mạch, chay...) cho từng bệnh nhân dựa trên tình trạng sức khỏe.
- 💬 **Phòng Tư Vấn**: Nhắn tin trực tiếp với bệnh nhân để hỗ trợ điều trị từ xa.

### 🔑 Dành cho Quản trị viên (Admin Dashboard)
- 👥 **Quản Lý Người Dùng & Bác Sĩ**: Kích hoạt, khóa tài khoản, thay đổi vai trò hoặc cập nhật gói thành viên (Free, Pro, Premium).
- 💼 **Quản Lý Bác Sĩ**: Thêm mới bác sĩ, chỉnh sửa thông tin phòng khám và chuyên khoa.
- 📈 **Thống Kê Doanh Thu**: Xem biểu đồ tăng trưởng doanh thu từ các gói dịch vụ và các giao dịch nâng cấp tài khoản của người dùng.
- 💬 **Quản Lý Phản Hồi**: Phản hồi ý kiến đóng góp của người dùng hệ thống.

---

## 🛠 Công nghệ sử dụng

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS v4, Alpine.js, Vite
- **Database**: MySQL (hỗ trợ lưu trữ đám mây qua Aiven Cloud hoặc chạy local) / SQLite
- **Authentication**: Laravel Breeze
- **AI Integration**: Google Gemini API / OpenAI API

---

## 🚀 Hướng dẫn cài đặt & Khởi chạy dự án

### Bước 1: Clone dự án và vào thư mục
Mở terminal và di chuyển vào thư mục dự án:
```bash
cd DALN
```

### Bước 2: Cài đặt các thư viện PHP
Chạy composer để cài đặt các package backend:
```bash
composer install
```

### Bước 3: Cài đặt các thư viện Frontend
Cài đặt các package Node.js:
```bash
npm install
```

### Bước 4: Cấu hình môi trường (.env)
1. Tạo file `.env` từ file mẫu:
   ```bash
   copy .env.example .env
   ```
   *(Hoặc sử dụng `cp .env.example .env` trên môi trường Linux/macOS)*

2. Mở file `.env` và thiết lập kết nối Database (ví dụ MySQL):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=healthsync
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

3. Thêm API key cho dịch vụ AI (nếu dùng tính năng Chatbot AI và thực đơn AI gợi ý):
   ```env
   GEMINI_API_KEY=YOUR_GEMINI_KEY
   OPENAI_API_KEY=YOUR_OPENAI_KEY
   ```

4. Tạo khóa bảo mật cho ứng dụng Laravel:
   ```bash
   php artisan key:generate
   ```

### Bước 5: Khởi tạo cơ sở dữ liệu & Tạo dữ liệu mẫu (Seeding)
Chạy lệnh migrate để tạo các bảng cơ sở dữ liệu và seed tài khoản mặc định cùng dữ liệu sức khỏe mẫu:
```bash
php artisan migrate --seed
```
*(Nếu bạn muốn import thủ công cơ sở dữ liệu MySQL có sẵn, bạn có thể import từ file [database_schema.sql](file:///d:/Doan/DALN/database_schema.sql))*

### Bước 6: Khởi chạy dự án
Để chạy dự án đầy đủ, bạn cần khởi chạy cả server Laravel backend và Vite frontend:

1. **Khởi chạy Server Backend:**
   ```bash
   php artisan serve
   ```
   Ứng dụng sẽ chạy tại địa chỉ: [http://localhost:8000](http://localhost:8000)

2. **Khởi chạy Server Frontend (Vite):**
   Mở thêm một terminal mới và chạy:
   ```bash
   npm run dev
   ```

---

## 🔑 Danh sách tài khoản đăng nhập mặc định

Hệ thống đã được seeding sẵn các tài khoản thử nghiệm sau:

### 1. Tài khoản Quản trị viên (Admin Portal)
Dùng để quản lý người dùng, bác sĩ và thống kê doanh thu.
- **Trang đăng nhập**: [http://localhost:8000/admin](http://localhost:8000/admin)
- **Tài khoản (Email)**: `admin@healthai.vn`
- **Mật khẩu (Password)**: `admin123`

*(Lưu ý: Có thêm tài khoản role admin được seed trong database để đăng nhập ở trang login chung là `admin@gmail.com` với mật khẩu `123456`)*

### 2. Tài khoản Bác sĩ (Doctor Portal)
Dùng để lập bệnh án, tư vấn chat và gán thực đơn cho bệnh nhân.
- **Trang đăng nhập**: [http://localhost:8000/doctor/login](http://localhost:8000/doctor/login)
- **Tài khoản (Email)**: `doctor.demo@healthsync.vn`
- **Mật khẩu (Password)**: `123456`

### 3. Tài khoản Người dùng (Patient Portal)
Dùng để theo dõi sức khỏe, chat AI, đặt lịch hẹn và xem thực đơn.
- **Trang đăng nhập**: [http://localhost:8000/login](http://localhost:8000/login)
- **Tài khoản 1 (Email)**: `user1@example.com`
- **Tài khoản 2 (Email)**: `user2@example.com`
- **Mật khẩu chung**: `123456`

