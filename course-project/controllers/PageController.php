<?php
// controllers/PageController.php
class PageController
{
    public function index()
    {
        $title = "Главная страница";
        $content = "
            <section class='hero'>
                <h1>🌸 I became a princess</h1>
                <p class='subtitle'>Визуальная новелла в мире магии и интриг</p>
                <a href='/course-project/downloads' class='btn-primary'>Скачать игру</a>
            </section>

            <!-- ЗАСТАВКА -->
            <div class='splash'>
                <img src='/course-project/assets/images/6.png' alt='Заставка игры' class='splash-image'>
            </div>

            <div class='home-gallery'>
                <div class='home-card'>
                    <img src='/course-project/assets/images/1.png' alt='Скриншот игры'>
                    <p class='caption'>🔮 Встреча с судьбой</p>
                </div>
                <div class='home-card'>
                    <img src='/course-project/assets/images/2.png' alt='Скриншот игры'>
                    <p class='caption'>💎 Тайны прошлого</p>
                </div>
            </div>

            <section class='features'>
                <div class='feature-card'>
                    <h3>📖 Увлекательный сюжет</h3>
                    <p>Окунитесь в историю девушки Мейдлин, которая оказалась в теле злодейки.</p>
                </div>
                <div class='feature-card'>
                    <h3>🎨 Уникальная графика</h3>
                    <p>Более 5 спрайтов и фонов, созданных специально для новеллы.</p>
                </div>
                <div class='feature-card'>
                    <h3>🎭 Несколько концовок</h3>
                    <p>Ваши решения влияют на судьбу героини — 5 уникальные концовки.</p>
                </div>
            </section>
        ";
        return render($content, $title);
    }

    public function about()
    {
        $title = "О проекте";
        $content = "
            <section>
                <h1>О проекте</h1>
                <p>«I became a princess» — интерактивная визуальная новелла, созданная в рамках проектной практики. Игрок погружается в историю девушки Мейдлин, которая после прочтения романа неожиданно оказывается в теле злодейки.</p>
                <p>Сможете ли вы изменить судьбу и избежать трагической концовки? Исследуйте мир магии, заводите союзников и принимайте решения, которые приведут к одной из двух уникальных концовок.</p>
                <h2>⚙️ Характеристики</h2>
                <ul>
                    <li>Движок: Ren'Py 8.0+</li>
                    <li>Язык: Python</li>
                    <li>Жанр: Визуальная новелла, сёдзё</li>
                    <li>Количество концовок: 2</li>
                    <li>Время прохождения: ~10–15 минут</li>
                </ul>
            </section>
        ";
        return render($content, $title);
    }

    public function characters()
    {
        $title = "Персонажи";
        $content = "
            <section>
                <h1>Персонажи</h1>
                <div class='characters-grid'>
                    <div class='character-card'>
                        <img src='/course-project/assets/images/1.png' alt='Мейдлин'>
                        <h3>🌸 Мейдлин</h3>
                        <p>Главная героиня. Обычная девушка из нашего мира, попавшая в тело злодейки.</p>
                    </div>
                    <div class='character-card'>
                        <img src='/course-project/assets/images/2.png' alt='Эрнард'>
                        <h3>⚔️ Эрнард</h3>
                        <p>Младший брат Мейдлин. Заботливый и преданный, всегда готов прийти на помощь.</p>
                    </div>
                    <div class='character-card'>
                        <img src='/course-project/assets/images/3.png' alt='Маг'>
                        <h3>🔮 Маг</h3>
                        <p><em>В разработке</em> — Таинственный персонаж, владеющий древней магией.</p>
                    </div>
                    <div class='character-card'>
                        <img src='/course-project/assets/images/4.png' alt='Рыцарь'>
                        <h3>🛡️ Рыцарь</h3>
                        <p><em>В разработке</em> — Благородный воин, хранящий тайну.</p>
                    </div>
                </div>
            </section>
        ";
        return render($content, $title);
    }

    public function downloads()
    {
        $title = "Скачать игру";
        $content = "
            <section>
                <h1>Скачать игру</h1>
                <div class='downloads'>
                    <div class='download-card'>
                        <h3>🪟 Windows</h3>
                        <p>Версия для Windows 10/11</p>
                        <a href='#' class='btn'>Скачать (exe)</a>
                    </div>
                </div>
                <h2>🎬 Демонстрация геймплея</h2>
                <div class='gif-demo'>
                    <video controls width='100%' poster='/course-project/assets/images/poster.png'>
                        <source src='/course-project/assets/video/новелла.mp4' type='video/mp4'>
                        Ваш браузер не поддерживает видео.
                    </video>
                </div>
                <h2>📸 Персонажи</h2>
                <div class='screenshot-gallery'>
                    <img src='/course-project/assets/images/1.png' alt='Скриншот 1'>
                    <img src='/course-project/assets/images/2.png' alt='Скриншот 2'>
                    <img src='/course-project/assets/images/3.png' alt='Скриншот 3'>
                    <img src='/course-project/assets/images/4.png' alt='Скриншот 4'>
                </div>
            </section>
        ";
        return render($content, $title);
    }

    public function credits()
    {
        $title = "Авторы";
        $content = "
            <section>
                <h1>Авторы проекта</h1>
                <div class='authors'>
                    <div class='author-card'>
                        <h3>🌸 Гусарова Ксения</h3>
                        <p>Сценарий, программирование, графика, тестирование</p>
                    </div>
                </div>
            </section>
        ";
        return render($content, $title);
    }
}