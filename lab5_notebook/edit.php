<?php
// ============================================
// МОДУЛЬ EDIT (edit.php)
// Форма редактирования записи со списком контактов
// ============================================

/**
 * Возвращает HTML-код для редактирования записи
 * @param PDO $pdo - подключение к БД
 * @param int|null $selectedId - ID выбранной записи
 * @return string
 */
function renderEditForm($pdo, $selectedId = null) {
    $message = '';
    $messageType = '';
    
    // Получаем список всех контактов (сортировка по фамилии, затем по имени)
    $stmt = $pdo->query("SELECT id, surname, name, lastname FROM contacts ORDER BY surname ASC, name ASC");
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Если нет записей
    if (empty($contacts)) {
        return '<h2>Редактирование записи</h2><div class="error">Нет записей для редактирования</div>';
    }
    
    // Определяем текущую выбранную запись
    if ($selectedId === null && !empty($contacts)) {
        $selectedId = $contacts[0]['id'];
    }
    
    // Получаем данные выбранной записи
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->execute([':id' => $selectedId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Обработка отправки формы
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button']) && $_POST['button'] === 'Сохранить') {
        $id = $_POST['id'] ?? 0;
        $surname = trim($_POST['surname'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $gender = $_POST['gender'] ?? 'мужской';
        $birth_date = $_POST['date'] ?? null;
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['location'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $comment = trim($_POST['comment'] ?? '');
        
        if (empty($surname) || empty($name)) {
            $message = 'Ошибка: запись не сохранена. Фамилия и имя обязательны.';
            $messageType = 'error';
        } else {
            try {
                $updateStmt = $pdo->prepare("
                    UPDATE contacts SET 
                        surname = :surname, name = :name, lastname = :lastname,
                        gender = :gender, birth_date = :birth_date, phone = :phone,
                        address = :address, email = :email, comment = :comment
                    WHERE id = :id
                ");
                
                $updateStmt->execute([
                    ':id' => $id,
                    ':surname' => $surname,
                    ':name' => $name,
                    ':lastname' => $lastname,
                    ':gender' => $gender,
                    ':birth_date' => $birth_date ?: null,
                    ':phone' => $phone,
                    ':address' => $address,
                    ':email' => $email,
                    ':comment' => $comment
                ]);
                
                $message = 'Запись сохранена';
                $messageType = 'success';
                
                // Обновляем данные в $row
                $row['surname'] = $surname;
                $row['name'] = $name;
                $row['lastname'] = $lastname;
                $row['gender'] = $gender;
                $row['birth_date'] = $birth_date;
                $row['phone'] = $phone;
                $row['address'] = $address;
                $row['email'] = $email;
                $row['comment'] = $comment;
                
            } catch (PDOException $e) {
                $message = 'Ошибка: запись не сохранена. ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    }
    
    // Генерация HTML
    $html = '<h2>Редактирование записи</h2>';
    
    if ($message) {
        $html .= '<div class="' . $messageType . '">' . htmlspecialchars($message) . '</div>';
    }
    
    // Список контактов для выбора
    $html .= '<div class="contacts-list">';
    $html .= '<h3>Выберите запись для редактирования:</h3>';
    foreach ($contacts as $contact) {
        $activeClass = ($contact['id'] == $selectedId) ? 'currentRow' : '';
        $fullName = htmlspecialchars($contact['surname'] . ' ' . $contact['name'] . ' ' . $contact['lastname']);
        $html .= '<div class="div-edit">';
        $html .= '<a href="index.php?menu=edit&edit_id=' . $contact['id'] . '" class="' . $activeClass . '">' . $fullName . '</a>';
        $html .= '</div>';
    }
    $html .= '</div>';
    
    // Форма редактирования
    $html .= '<form name="form_edit" method="post">
        <div class="column">
            <input type="hidden" name="id" value="' . $row['id'] . '">
            <div class="add">
                <label>Фамилия *</label> 
                <input type="text" name="surname" placeholder="Фамилия" value="' . htmlspecialchars($row['surname']) . '" required>
            </div>
            <div class="add">
                <label>Имя *</label> 
                <input type="text" name="name" placeholder="Имя" value="' . htmlspecialchars($row['name']) . '" required>
            </div>
            <div class="add">
                <label>Отчество</label> 
                <input type="text" name="lastname" placeholder="Отчество" value="' . htmlspecialchars($row['lastname']) . '">
            </div>
            <div class="add">
                <label>Пол</label> 
                <select name="gender">
                    <option value="мужской" ' . ($row['gender'] == 'мужской' ? 'selected' : '') . '>мужской</option>
                    <option value="женский" ' . ($row['gender'] == 'женский' ? 'selected' : '') . '>женский</option>
                </select>
            </div>
            <div class="add">
                <label>Дата рождения</label> 
                <input type="date" name="date" value="' . htmlspecialchars($row['birth_date']) . '">
            </div>
            <div class="add">
                <label>Телефон</label> 
                <input type="text" name="phone" placeholder="Телефон" value="' . htmlspecialchars($row['phone']) . '">
            </div>
            <div class="add">
                <label>Адрес</label> 
                <input type="text" name="location" placeholder="Адрес" value="' . htmlspecialchars($row['address']) . '">
            </div>
            <div class="add">
                <label>Email</label> 
                <input type="email" name="email" placeholder="Email" value="' . htmlspecialchars($row['email']) . '">
            </div>
            <div class="add">
                <label>Комментарий</label> 
                <textarea name="comment" placeholder="Краткий комментарий">' . htmlspecialchars($row['comment']) . '</textarea>
            </div>
            <button type="submit" value="Сохранить" name="button" class="form-btn">Сохранить</button>
        </div>
    </form>';
    
    return $html;
}
?>