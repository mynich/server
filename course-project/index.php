<?php
// index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once 'models/Database.php';
require_once 'controllers/PageController.php';
require_once 'controllers/ArticleController.php';

function render($content, $title = "Мой блог")
{
    ob_start();
    include 'views/layout.php';
    return ob_get_clean();
}

// РОУТИНГ
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
$requestUri = str_replace('/course-project', '', $requestUri);

$pageController = new PageController();
$articleController = new ArticleController();

// Главная
if ($requestUri === '/' || $requestUri === '') {
    echo $pageController->index();
}
// О проекте
elseif ($requestUri === '/about') {
    echo $pageController->about();
}
// Персонажи
elseif ($requestUri === '/characters') {
    echo $pageController->characters();
}
// Скачать
elseif ($requestUri === '/downloads') {
    echo $pageController->downloads();
}
// Авторы
elseif ($requestUri === '/credits') {
    echo $pageController->credits();
}
// Статьи (все)
elseif ($requestUri === '/articles') {
    echo $articleController->index();
}
// Статья по ID
elseif (preg_match('#^/article/(\d+)$#', $requestUri, $matches)) {
    $id = (int)$matches[1];
    echo $articleController->show($id);
}
// Админ: добавить статью (дополнительно)
elseif ($requestUri === '/admin/article/create') {
    echo $articleController->create();
}
else {
    http_response_code(404);
    echo render("<h1>404</h1><p>Страница не найдена</p>", "404");
}