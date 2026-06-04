<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - #сортер</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>📝 Регистрация</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p class="error">❌ <?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Имя:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" required>
                <small>Минимум 8 символов, заглавная буква, цифра, символ ! или ?</small>
            </div>
            
            <div class="form-group">
                <label>Подтверждение пароля:</label>
                <input type="password" name="confirm_password" required>
            </div>
            
            <button type="submit">Зарегистрироваться</button>
        </form>
        
        <p>Уже есть аккаунт? <a href="index.php?action=login">Войти</a></p>
    </div>
</body>
</html>