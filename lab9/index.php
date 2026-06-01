<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'controllers/ArticlesController.php';

// ========== РОУТЕР ==========
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
$requestUri = str_replace('/lab9', '', $requestUri);

// Главная страница (список статей)
if ($requestUri === '/' || $requestUri === '') {
    echo showHomePage();
}
// Роут /article/ID
elseif (preg_match('#^/article/(\d+)$#', $requestUri, $matches)) {
    $id = (int)$matches[1];
    $controller = new ArticlesController();
    echo $controller->show($id);
}
else {
    http_response_code(404);
    echo "404 - Страница не найдена";
}

function showHomePage()
{
    $pdo = Database::getInstance()->getConnection();
    $stmt = $pdo->query("SELECT id, title FROM articles ORDER BY id DESC");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = "<h1>Блог</h1><ul>";
    foreach ($articles as $article) {
        $html .= "<li><a href='/lab9/article/{$article['id']}'>" . htmlspecialchars($article['title']) . "</a></li>";
    }
    $html .= "</ul>";
    return $html;
}