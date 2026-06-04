<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет - #сортер</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>🏷️ #сортер</h1>
        
        <div class="welcome">
            <p>Добро пожаловать, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
            <a href="index.php?action=logout" class="logout">🚪 Выйти</a>
        </div>
        
        <div class="main-content">
            <h2>Ваши сообщения</h2>
            <p>Здесь будет отображаться список сообщений с сортировкой по хэштегам</p>
        </div>
    </div>
</body>
</html>