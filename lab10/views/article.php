<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['title']) ?></title>
    <style>
        body { font-family: Arial; margin: 40px; line-height: 1.6; }
        .article { max-width: 800px; margin: 0 auto; }
        .author { color: #666; font-style: italic; margin-bottom: 20px; }
        .content { font-size: 18px; }
        .back, .edit { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="article">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        
        <div class="author">
            ✍️ Автор: <strong><?= htmlspecialchars($author['nickname']) ?></strong>
            (<?= htmlspecialchars($author['email']) ?>)
        </div>
        
        <div class="content">
            <?= nl2br(htmlspecialchars($article['content'])) ?>
        </div>
        
        <div class="edit">
            <a href="/lab10/article/<?= $article['id'] ?>/edit">✏️ Редактировать статью</a>
        </div>
        
        <div class="back">
            <a href="/lab10/">← На главную</a>
        </div>
    </div>
</body>
</html>