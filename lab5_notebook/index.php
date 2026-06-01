<?php
// ============================================
// ГЛАВНЫЙ МОДУЛЬ (index.php)
// Единственный файл, доступный в браузере
// ============================================

session_start();

// Подключение к базе данных
$host = 'localhost';
$dbname = 'lab5_notebook';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Определяем активный пункт меню
$active_menu = $_GET['menu'] ?? 'view';
$sort_type = $_GET['sort'] ?? 'date_desc';
$page = $_GET['page'] ?? 1;
$edit_id = $_GET['edit_id'] ?? null;

// Подключаем модули
require_once 'menu.php';
require_once 'viewer.php';
require_once 'add.php';
require_once 'edit.php';
require_once 'delete.php';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Записная книжка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <img src="logo.jpg" alt="Лого">
    <nav>
        <?= renderMenu($active_menu) ?>
    </nav>
</header>

<main>
    <?php
    // Рендерим нужный модуль в зависимости от активного меню
    switch($active_menu) {
        case 'view':
            echo renderViewer($pdo, $sort_type, $page);
            break;
        case 'add':
            echo renderAddForm($pdo);
            break;
        case 'edit':
            echo renderEditForm($pdo, $edit_id);
            break;
        case 'delete':
            echo renderDeletePage($pdo);
            break;
        default:
            echo renderViewer($pdo, $sort_type, $page);
    }
    ?>
</main>

<footer>
    &copy; <?= date('Y') ?> Записная книжка
</footer>

</body>
</html>