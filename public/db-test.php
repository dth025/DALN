<?php
/**
 * HealthSync AI - Database Connection Test Tool
 * Access via: http://localhost:8000/db-test.php
 */

header('Content-Type: text/html; charset=utf-8');

// 1. Đọc và parse file .env
$envFile = __DIR__ . '/../.env';
$config = [];
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Bỏ qua dòng comment
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);
            // Bỏ dấu nháy kép hoặc đơn nếu có bao quanh
            $val = trim($val, '"\'');
            $config[$key] = $val;
        }
    }
}

$connection = $config['DB_CONNECTION'] ?? 'mysql';
$host = $config['DB_HOST'] ?? '127.0.0.1';
$port = $config['DB_PORT'] ?? '3306';
$database = $config['DB_DATABASE'] ?? '';
$username = $config['DB_USERNAME'] ?? '';
$password = $config['DB_PASSWORD'] ?? '';

$connected = false;
$errorMsg = '';
$tables = [];

// 2. Thử kết nối Database
if ($connection === 'mysql') {
    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5, // Timeout 5s
        ];
        
        $pdo = new PDO($dsn, $username, $password, $options);
        $connected = true;
        
        // Truy vấn danh sách bảng và trạng thái
        $stmt = $pdo->query("SHOW TABLE STATUS");
        $tables = $stmt->fetchAll();
    } catch (PDOException $e) {
        $connected = false;
        $errorMsg = $e->getMessage();
    }
} else {
    $errorMsg = "Công cụ này chỉ hỗ trợ kiểm tra kết nối MySQL. DB_CONNECTION hiện tại là: " . htmlspecialchars($connection);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiểm tra kết nối Database - HealthSync AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-color: #f8fafc;
            --text-muted: #94a3b8;
            --success: #10b981;
            --danger: #ef4444;
            --primary: #3b82f6;
            --border: #334155;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }
        .container {
            max-width: 900px;
            width: 100%;
        }
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
        }
        h1 {
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .status-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 16px;
            font-weight: 600;
        }
        .error-box {
            background-color: rgba(239, 68, 68, 0.05);
            border-left: 4px solid var(--danger);
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
            color: #fca5a5;
            margin-top: 15px;
            word-break: break-all;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
        }
        th {
            background-color: rgba(255, 255, 255, 0.02);
            color: var(--text-muted);
            font-size: 13px;
            text-transform: uppercase;
            font-weight: 600;
        }
        tr:hover td {
            background-color: rgba(255, 255, 255, 0.01);
        }
        .badge-engine {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--primary);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .btn {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-top: 15px;
            cursor: pointer;
            border: none;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>
                Kiểm tra Kết nối Cơ sở dữ liệu
                <?php if ($connected): ?>
                    <span class="status-badge status-success">Thành công</span>
                <?php else: ?>
                    <span class="status-badge status-danger">Thất bại</span>
                <?php endif; ?>
            </h1>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Host</span>
                    <span class="info-value"><?php echo htmlspecialchars($host); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Port</span>
                    <span class="info-value"><?php echo htmlspecialchars($port); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Database</span>
                    <span class="info-value"><?php echo htmlspecialchars($database); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Username</span>
                    <span class="info-value"><?php echo htmlspecialchars($username); ?></span>
                </div>
            </div>

            <?php if (!$connected): ?>
                <div class="error-box">
                    <strong>Lỗi kết nối PDO:</strong><br>
                    <?php echo htmlspecialchars($errorMsg); ?>
                </div>
                <div style="margin-top: 20px;">
                    <p style="color: var(--text-muted); font-size: 14px;">
                        💡 <strong>Gợi ý khắc phục:</strong><br>
                        1. Kiểm tra lại thông số trong file <code>.env</code> ở thư mục gốc dự án.<br>
                        2. Đảm bảo MySQL Server cục bộ của bạn đang chạy (nếu sử dụng localhost).<br>
                        3. Đảm bảo thông tin Username, Password, và tên Database là chính xác.<br>
                        4. Nếu dùng MySQL máy khách (Aiven Cloud), kiểm tra kết nối mạng Internet.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($connected): ?>
            <div class="card">
                <h2>Danh sách các bảng trong Database (<?php echo count($tables); ?> bảng)</h2>
                <p style="color: var(--text-muted); font-size: 14px; margin-top: -10px;">
                    Để vẽ sơ đồ quan hệ thành công, hãy chắc chắn tất cả các bảng chính đều sử dụng Storage Engine là <strong>InnoDB</strong>.
                </p>

                <table>
                    <thead>
                        <tr>
                            <th>Tên Bảng</th>
                            <th>Storage Engine</th>
                            <th>Số Dòng (Dự kiến)</th>
                            <th>Collation</th>
                            <th>Thời gian tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tables as $table): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($table['Name']); ?></strong></td>
                                <td>
                                    <span class="badge-engine">
                                        <?php echo htmlspecialchars($table['Engine'] ?? 'N/A'); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($table['Rows'] ?? 0); ?></td>
                                <td><span style="font-size:12px; color:var(--text-muted);"><?php echo htmlspecialchars($table['Collation'] ?? 'N/A'); ?></span></td>
                                <td><span style="font-size:12px; color:var(--text-muted);"><?php echo htmlspecialchars($table['Create_time'] ?? 'N/A'); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
