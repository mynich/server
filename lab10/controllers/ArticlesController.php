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
        $stmt = $this->pdo->prepare("
            SELECT articles.*, users.nickname, users.email 
            FROM articles 
            JOIN users ON articles.user_id = users.id 
            WHERE articles.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return "Статья не найдена";
        }

        $article = $result;
        $author = ['nickname' => $result['nickname'], 'email' => $result['email']];

        return renderArticle($article, $author);
    }

    // Экшн для редактирования статьи
    public function edit($id)
    {
        // Получаем статью из БД
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            return "Статья не найдена";
        }

        // Если форма отправлена — сохраняем изменения
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);

            if (empty($title) || empty($content)) {
                $error = "Заполните все поля";
                return renderEditForm($article, $error);
            }

            // Обновляем статью в БД
            $stmt = $this->pdo->prepare("UPDATE articles SET title = :title, content = :content WHERE id = :id");
            $stmt->execute([
                'title' => $title,
                'content' => $content,
                'id' => $id
            ]);

            // Обновляем переменную $article новыми данными
            $article['title'] = $title;
            $article['content'] = $content;

            $success = "Статья успешно обновлена!";

            // Показываем форму с обновлёнными данными и сообщением об успехе
            return renderEditForm($article, null, $success);
        }

        // Показываем форму редактирования
        return renderEditForm($article);
    }
}

// ========== ФУНКЦИИ ДЛЯ РЕНДЕРИНГА (каждая только ОДИН раз!) ==========

function renderArticle($article, $author)
{
    ob_start();
    include 'views/article.php';
    return ob_get_clean();
}

function renderEditForm($article, $error = null, $success = null)
{
    ob_start();
    include 'views/edit.php';
    return ob_get_clean();
}