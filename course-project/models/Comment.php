<?php
// models/Comment.php
class Comment
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Получить все комментарии для статьи
    public function getByArticleId($articleId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM comments 
            WHERE article_id = :article_id 
            ORDER BY created_at DESC
        ");
        $stmt->execute(['article_id' => $articleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Добавить комментарий
    public function create($articleId, $username, $content)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO comments (article_id, username, content) 
            VALUES (:article_id, :username, :content)
        ");
        return $stmt->execute([
            'article_id' => $articleId,
            'username' => htmlspecialchars($username),
            'content' => htmlspecialchars($content)
        ]);
    }

    // Удалить комментарий (опционально)
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM comments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}