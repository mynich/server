<?php
// ============================================
// МОДУЛЬ DELETE (delete.php)
// Список контактов для удаления
// ============================================

/**
 * Возвращает HTML-код для удаления записей
 * @param PDO $pdo - подключение к БД
 * @return string
 */
function renderDeletePage($pdo) {
    $message = '';
    $messageType = '';
    
    // Обработка удаления
    if (isset($_GET['delete_id'])) {
        $deleteId = (int)$_GET['delete_id'];
        
        // Получаем фамилию для сообщения
        $stmt = $pdo->prepare("SELECT surname, name, lastname FROM contacts WHERE id = :id");
        $stmt->execute([':id' => $deleteId]);
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($contact) {
            $fullName = $contact['surname'];
            $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = :id");
            $stmt->execute([':id' => $deleteId]);
            
            $message = "Запись с фамилией \"{$fullName}\" удалена";
            $messageType = 'success';
        } else {
            $message = "Ошибка: запись не найдена";
            $messageType = 'error';
        }
    }
    
    // Получаем список всех контактов
    $stmt = $pdo->query("SELECT id, surname, name, lastname FROM contacts ORDER BY surname ASC, name ASC");
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Генерация HTML
    $html = '<h2>Удаление записи</h2>';
    
    if ($message) {
        $html .= '<div class="' . $messageType . '">' . htmlspecialchars($message) . '</div>';
    }
    
    if (empty($contacts)) {
        $html .= '<div class="error">Нет записей для удаления</div>';
    } else {
        $html .= '<h3>Выберите запись для удаления:</h3>';
        $html .= '<div class="delete-list">';
        foreach ($contacts as $contact) {
            $fullName = htmlspecialchars($contact['surname'] . ' ' . $contact['name'] . ' ' . $contact['lastname']);
            $html .= '<div class="div-delete">';
            $html .= '<a href="index.php?menu=delete&delete_id=' . $contact['id'] . '" onclick="return confirm(\'Удалить запись ' . $fullName . '?\')">' . $fullName . '</a>';
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    
    return $html;
}
?>