<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | I became a princess</title>
    <link rel="stylesheet" href="/course-project/assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <nav>
                <div class="logo">
                    <a href="/course-project/">🌸 I became a princess</a>
                </div>
                <ul>
                    <li><a href="/course-project/">Главная</a></li>
                    <li><a href="/course-project/about">О проекте</a></li>
                    <li><a href="/course-project/characters">Персонажи</a></li>
                    <li><a href="/course-project/downloads">Скачать</a></li>
                    <li><a href="/course-project/articles">Новости</a></li>
                    <li><a href="/course-project/credits">Авторы</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <div class="container">
                <?= $content ?>
            </div>
        </main>

        <footer>
            <p>© 2025, Московский Политех. Проектная практика.</p>
            <p>Визуальная новелла «I became a princess»</p>
        </footer>
    </div>
    <script src="/course-project/assets/js/main.js"></script>
</body>
</html>