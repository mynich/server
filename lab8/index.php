<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>1. Скрипт запустился</h1>";

require_once 'controllers/UserController.php';
echo "<h1>2. Контроллер подключен</h1>";

function render($content, $title = "Мой блог")
{
    ob_start();
    include 'views/layout.php';
    return ob_get_clean();
}

$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
$requestUri = str_replace('/lab8', '', $requestUri);

echo "<p>URI: " . $requestUri . "</p>";

$controller = new UserController();

if ($requestUri === '/' || $requestUri === '') {
    $content = "<h1>Добро пожаловать в мой блог!</h1>";
    echo render($content);
}
elseif (preg_match('#^/hello/(.+)$#', $requestUri, $matches)) {
    $name = urldecode($matches[1]);
    $content = $controller->sayHello($name);
    echo render($content, "Страница приветствия");
}
elseif (preg_match('#^/bye/(.+)$#', $requestUri, $matches)) {
    $name = urldecode($matches[1]);
    $content = $controller->sayBye($name);
    echo render($content);
}
else {
    echo "<h1>404 - Страница не найдена</h1>";
    echo "<p>URI: " . htmlspecialchars($requestUri) . "</p>";
}