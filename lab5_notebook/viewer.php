<?php
// ============================================
// МОДУЛЬ VIEWER (viewer.php)
// Выводит таблицу с контактами и пагинацию
// ============================================

/**
 * Возвращает HTML-код таблицы с контактами и пагинацией
 * @param PDO $pdo - подключение к БД
 * @param string $sort - тип сортировки
 * @param int $page - номер страницы
 * @return string
 */
function renderViewer($pdo, $sort, $page) {
    $records_per_page = 10;
    $offset = ($page - 1) * $records_per_page;
    
    // Формируем ORDER BY в зависимости от сортировки
    switch($sort) {
        case 'surname_asc':
            $orderBy = "ORDER BY surname ASC, name ASC";
            break;
        case 'birth_asc':
            $orderBy = "ORDER BY birth_date ASC";
            break;
        case 'date_asc':
            $orderBy = "ORDER BY created_at ASC";
            break;
        case 'date_desc':
        default:
            $orderBy = "ORDER BY created_at DESC";
            break;
    }
    
    // Получаем общее количество записей
    $totalStmt = $pdo->query("SELECT COUNT(*) FROM contacts");
    $totalRecords = $totalStmt->fetchColumn();
    $totalPages = ceil($totalRecords / $records_per_page);
    
    // Получаем записи для текущей страницы
    $stmt = $pdo->prepare("SELECT * FROM contacts $orderBy LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Формируем таблицу
    $html = '<h2>Список контактов</h2>';
    $html .= '<table border="1" cellpadding="8" cellspacing="0">';
    $html .= '<tr>';
    $html .= '<th>ID</th><th>Фамилия</th><th>Имя</th><th>Отчество</th>';
    $html .= '<th>Пол</th><th>Дата рождения</th><th>Телефон</th>';
    $html .= '<th>Адрес</th><th>Email</th><th>Комментарий</th>';
    $html .= '</tr>';
    
    if (empty($contacts)) {
        $html .= '<tr><td colspan="10" style="text-align:center">Нет записей</td></tr>';
    } else {
        foreach ($contacts as $row) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['surname']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['lastname']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['gender']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['birth_date']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['address']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
            $html .= '</tr>';
        }
    }
    $html .= '</table>';
    
    // Пагинация
    if ($totalPages > 1) {
        $html .= '<div class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($i == $page) ? 'current-page' : '';
            $html .= '<a href="index.php?menu=view&sort=' . $sort . '&page=' . $i . '" class="' . $activeClass . '">' . $i . '</a>';
        }
        $html .= '</div>';
    }
    
    return $html;
}
?>