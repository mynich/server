<?php

// Подключаем контроллер
require_once 'controllers/UserController.php';

// ========== РОУТЕР ==========

// Получаем URI
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');

// Убираем /lab7 из начала
$requestUri = str_replace('/lab7', '', $requestUri);

// Создаём объект контроллера
$controller = new UserController();

if ($requestUri === '/' || $requestUri === '') {
    echo "Главная страница лабораторной работы №7<br>";
    echo '<a href="/lab7/hello/Иван">Поздороваться</a><br>';
    echo '<a href="/lab7/bye/Иван">Попрощаться</a>';
}
// Роут /hello/Имя
elseif (preg_match('#^/hello/(.+)$#', $requestUri, $matches)) {
    $name = urldecode($matches[1]);
    echo $controller->sayHello($name);
}
// Роут /bye/Имя
elseif (preg_match('#^/bye/(.+)$#', $requestUri, $matches)) {
    $name = urldecode($matches[1]);
    echo $controller->sayBye($name);
}
else {
    http_response_code(404);
    echo "Страница не найдена";
}