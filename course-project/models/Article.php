<?php
// models/Article.php
class Article
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $content)
    {
        $stmt = $this->pdo->prepare("INSERT INTO articles (title, content) VALUES (:title, :content)");
        return $stmt->execute(['title' => $title, 'content' => $content]);
    }

    public function update($id, $title, $content)
    {
        $stmt = $this->pdo->prepare("UPDATE articles SET title = :title, content = :content WHERE id = :id");
        return $stmt->execute(['id' => $id, 'title' => $title, 'content' => $content]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}