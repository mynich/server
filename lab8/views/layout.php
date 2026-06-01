<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
</head>
<body>
    <div class="container">
        <div class="menu">
            <a href="/lab8/">Главная</a>
            <a href="/lab8/hello/Иван">Поздороваться</a>
            <a href="/lab8/bye/Иван">Попрощаться</a>
        </div>
        <hr>
        <?php echo $content; ?>
    </div>
</body>
</html>