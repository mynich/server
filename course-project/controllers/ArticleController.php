<?php
// controllers/ArticleController.php
require_once 'models/Article.php';

class ArticleController
{
    private $articleModel;

    public function __construct()
    {
        $this->articleModel = new Article();
    }

    public function index()
    {
        $articles = $this->articleModel->getAll();
        $title = "Новости и статьи";
        
        $html = "<h1>Новости и статьи</h1>";
        foreach ($articles as $article) {
            $html .= "
                <div class='article-preview'>
                    <h2><a href='/course-project/article/{$article['id']}'>{$article['title']}</a></h2>
                    <p class='date'>" . date('d.m.Y', strtotime($article['created_at'])) . "</p>
                    <p>" . mb_substr(strip_tags($article['content']), 0, 200) . "...</p>
                    <a href='/course-project/article/{$article['id']}' class='read-more'>Читать далее →</a>
                </div>
            ";
        }
        return render($html, $title);
    }

    public function show($id)
    {
        $article = $this->articleModel->getById($id);
        if (!$article) {
            http_response_code(404);
            return render("<h1>404</h1><p>Статья не найдена</p>", "404");
        }
        
        $html = "
            <article>
                <h1>{$article['title']}</h1>
                <p class='date'>" . date('d.m.Y', strtotime($article['created_at'])) . "</p>
                <div class='content'>
                    " . nl2br(htmlspecialchars($article['content'])) . "
                </div>
                <a href='/course-project/articles' class='back-link'>← Все статьи</a>
            </article>
        ";
        return render($html, $article['title']);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $this->articleModel->create($title, $content);
            header("Location: /course-project/articles");
            exit;
        }
        
        $html = "
            <h1>Добавить статью</h1>
            <form method='POST' class='admin-form'>
                <input type='text' name='title' placeholder='Заголовок' required>
                <textarea name='content' placeholder='Содержание' required></textarea>
                <button type='submit'>Сохранить</button>
            </form>
        ";
        return render($html, "Добавить статью");
    }
}