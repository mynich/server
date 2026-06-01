<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$serverResponse = null;
$formSent = false;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'username' => $_POST['username'] ?? '',
        'email' => $_POST['email'] ?? '',
        'type' => $_POST['type'] ?? '',
        'message' => $_POST['message'] ?? '',
        'response' => $_POST['response'] ?? []
    ];
    
    // Отправка на https://httpbin.org/post
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $response = file_get_contents('https://httpbin.org/post', false, $context);
    
    if ($response !== false) {
        $formSent = true;
        $serverResponse = json_decode($response, true);
    } else {
        $errorMessage = 'Ошибка отправки. Проверьте подключение к интернету.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма обратной связи</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="header">
        <div class="logo">
            <img src="logo.jpg" alt="МосПолитех" onerror="this.src='https://mospolytech.ru/upload/iblock/289/289e61c4af5b5965e3dafb5e14ecad5c.png'">
            <span>Московский Политех</span>
        </div>
        <div class="title">Форма обратной связи</div>
    </div>

    <div class="main">
        <div class="form-container">
            <h2>📝 Обратная связь</h2>
            
            <?php if ($formSent): ?>
                <div class="success">
                    ✅ Форма успешно отправлена на https://httpbin.org/post!
                </div>
                
                <div class="response-box">
                    <h3>📨 Ответ сервера (JSON):</h3>
                    <pre><?php 
                        if ($serverResponse) {
                            echo json_encode($serverResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        } else {
                            echo "Нет данных от сервера";
                        }
                    ?></pre>
                </div>
            <?php endif; ?>
            
            <?php if ($errorMessage): ?>
                <div class="error">❌ <?php echo $errorMessage; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Имя пользователя:</label>
                    <input type="text" name="username" required>
                </div>
                
                <div class="form-group">
                    <label>E-mail пользователя:</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Тип обращения:</label>
                    <select name="type" required>
                        <option value="">Выберите</option>
                        <option value="complaint">Жалоба</option>
                        <option value="suggestion">Предложение</option>
                        <option value="thanks">Благодарность</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Текст обращения:</label>
                    <textarea name="message" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Вариант ответа:</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="response[]" value="sms"> SMS</label>
                        <label><input type="checkbox" name="response[]" value="email"> E-mail</label>
                    </div>
                </div>
                
                <button type="submit" class="btn">📤 Отправить</button>
                <a href="headers.php" class="btn-link">📋 Перейти на 2 страницу →</a>
            </form>
        </div>
    </div>

    <div class="footer">
        задание для самостоятельной работы
    </div>

</body>
</html>