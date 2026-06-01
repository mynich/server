<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результат get_headers</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; min-height: 100vh; display: flex; flex-direction: column; background: #f5f5f5; }
        .header { display: flex; align-items: center; justify-content: space-between; padding: 15px 40px; background: white; border-bottom: 3px solid #0033a0; }
        .logo { display: flex; align-items: center; gap: 15px; }
        .logo img { height: 50px; }
        .title { font-size: 22px; color: #333; font-weight: bold; }
        .main { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px; }
        .container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); max-width: 800px; width: 100%; }
        .container h2 { text-align: center; color: #0033a0; margin-bottom: 30px; }
        textarea { width: 100%; padding: 15px; font-family: monospace; font-size: 13px; border: 1px solid #ddd; border-radius: 5px; resize: vertical; background: #f8f9fa; }
        .btn-link { display: block; text-align: center; background: #6c757d; color: white; text-decoration: none; padding: 12px; border-radius: 5px; margin-top: 20px; }
        .footer { text-align: center; padding: 15px; background: #2c3e50; color: white; }
        .warning { background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            <img src="logo.jpg" alt="МосПолитех" onerror="this.src='https://mospolytech.ru/upload/iblock/289/289e61c4af5b5965e3dafb5e14ecad5c.png'">
            <span>Московский Политех</span>
        </div>
        <div class="title">Результат get_headers</div>
    </div>

    <div class="main">
        <div class="container">
            <h2>📡 Заголовки страницы https://httpbin.org</h2>
            
            <?php
            // Проверяем, доступен ли HTTPS
            if (!in_array('https', stream_get_wrappers())) {
                echo '<div class="warning">⚠️ Внимание: HTTPS не поддерживается. Нужно включить OpenSSL в php.ini</div>';
            }
            ?>
            
            <textarea rows="18" readonly><?php
                // ИСПРАВЛЕНО: https вместо http
                $headers = @get_headers('https://httpbin.org', 1);
                
                if ($headers !== false) {
                    echo "=== ЗАГОЛОВКИ HTTP ОТВЕТА (HTTPS) ===\n\n";
                    foreach ($headers as $key => $value) {
                        if (is_array($value)) {
                            echo "$key:\n";
                            foreach ($value as $val) {
                                echo "  ├── $val\n";
                            }
                        } else {
                            echo "$key: $value\n";
                        }
                    }
                    echo "\n✅ Получено " . date('Y-m-d H:i:s');
                    echo "\n🔒 Протокол: HTTPS";
                } else {
                    echo "❌ Не удалось получить заголовки по HTTPS\n";
                    echo "\nВозможные причины:\n";
                    echo "1. OpenSSL не включён в php.ini\n";
                    echo "2. Нет подключения к интернету\n";
                    echo "\n💡 Решение: раскомментируйте extension=openssl в php.ini";
                }
            ?></textarea>
            <a href="index.php" class="btn-link">← Назад на 1 страницу</a>
        </div>
    </div>

    <div class="footer">
        задание для самостоятельной работы
    </div>

</body>
</html>