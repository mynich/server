<?php
// controllers/ArticleController.php
require_once 'models/Article.php';
require_once 'models/Comment.php';
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
    
    // Получаем комментарии к статье
    $commentModel = new Comment();
    $comments = $commentModel->getByArticleId($id);
    
    // Обработка добавления комментария
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
        $username = trim($_POST['username']);
        $content = trim($_POST['content']);
        
        if (!empty($username) && !empty($content)) {
            $commentModel->create($id, $username, $content);
            // Перенаправляем, чтобы избежать повторной отправки формы
            header("Location: /course-project/article/$id");
            exit;
        }
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
        
        <!-- БЛОК КОММЕНТАРИЕВ -->
        <section class='comments-section'>
            <h2>💬 Комментарии (" . count($comments) . ")</h2>
            
            <div class='comment-list'>
    ";
    
    if (empty($comments)) {
        $html .= "<p class='no-comments'>Пока нет комментариев. Будьте первым!</p>";
    } else {
        foreach ($comments as $comment) {
            $html .= "
                <div class='comment'>
                    <div class='comment-header'>
                        <span class='comment-author'>👤 " . htmlspecialchars($comment['username']) . "</span>
                        <span class='comment-date'>" . date('d.m.Y H:i', strtotime($comment['created_at'])) . "</span>
                    </div>
                    <div class='comment-content'>
                        " . nl2br(htmlspecialchars($comment['content'])) . "
                    </div>
                </div>
            ";
        }
    }
    
    $html .= "
            </div>
            
            <div class='comment-form'>
                <h3>✏️ Добавить комментарий</h3>
                <form method='POST'>
                    <div class='form-group'>
                        <input type='text' name='username' placeholder='Ваше имя' required>
                    </div>
                    <div class='form-group'>
                        <textarea name='content' rows='4' placeholder='Ваш комментарий...' required></textarea>
                    </div>
                    <button type='submit' name='add_comment' class='btn'>Отправить</button>
                </form>
            </div>
        </section>
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