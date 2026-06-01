<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование статьи</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        textarea { height: 150px; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .back { margin-top: 20px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Редактирование статьи</h1>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Заголовок статьи:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>" required>
        </div>

        <div class="form-group">
            <label>Содержание статьи:</label>
            <textarea name="content" required><?= htmlspecialchars($article['content']) ?></textarea>
        </div>

        <button type="submit" name="save" value="1">Сохранить изменения</button>
    </form>

    <div class="back">
        <a href="/lab10/article/<?= $article['id'] ?>">← Вернуться к статье</a>
    </div>
</body>
</html>