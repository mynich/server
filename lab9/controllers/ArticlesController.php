<?php

require_once 'models/Database.php';

class ArticlesController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Экшн для показа одной статьи
    public function show($id)
    {
        // 1. Получаем статью по ID
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            return "Статья не найдена";
        }

        // 2. Получаем автора статьи (из таблицы users)
        $stmt = $this->pdo->prepare("SELECT id, nickname, email FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $article['user_id']]);
        $author = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. Рендерим шаблон
        return renderArticle($article, $author);
    }
}

// Функция для рендеринга шаблона
function renderArticle($article, $author)
{
    ob_start();
    include 'views/article.php';
    return ob_get_clean();
}