<?php
// ============================================
// МОДУЛЬ ADD (add.php)
// Форма добавления новой записи
// ============================================

/**
 * Возвращает HTML-код формы добавления и обрабатывает POST-запрос
 * @param PDO $pdo - подключение к БД
 * @return string
 */
function renderAddForm($pdo) {
    $message = '';
    $messageType = '';
    
    // Обработка отправки формы
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button']) && $_POST['button'] === 'Добавить') {
        $surname = trim($_POST['surname'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $gender = $_POST['gender'] ?? 'мужской';
        $birth_date = $_POST['date'] ?? null;
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['location'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $comment = trim($_POST['comment'] ?? '');
        
        // Простая валидация
        if (empty($surname) || empty($name)) {
            $message = 'Ошибка: запись не добавлена. Фамилия и имя обязательны.';
            $messageType = 'error';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO contacts (surname, name, lastname, gender, birth_date, phone, address, email, comment) 
                    VALUES (:surname, :name, :lastname, :gender, :birth_date, :phone, :address, :email, :comment)
                ");
                
                $stmt->execute([
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
                
                $message = 'Запись добавлена';
                $messageType = 'success';
            } catch (PDOException $e) {
                $message = 'Ошибка: запись не добавлена. ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    }
    
    // Форма добавления
    $html = '<h2>Добавление новой записи</h2>';
    
    if ($message) {
        $html .= '<div class="' . $messageType . '">' . htmlspecialchars($message) . '</div>';
    }
    
    $html .= '<form name="form_add" method="post">
        <div class="column">
            <div class="add">
                <label>Фамилия *</label> 
                <input type="text" name="surname" placeholder="Фамилия" required>
            </div>
            <div class="add">
                <label>Имя *</label> 
                <input type="text" name="name" placeholder="Имя" required>
            </div>
            <div class="add">
                <label>Отчество</label> 
                <input type="text" name="lastname" placeholder="Отчество">
            </div>
            <div class="add">
                <label>Пол</label> 
                <select name="gender">
                    <option value="мужской">мужской</option>
                    <option value="женский">женский</option>
                </select>
            </div>
            <div class="add">
                <label>Дата рождения</label> 
                <input type="date" name="date">
            </div>
            <div class="add">
                <label>Телефон</label> 
                <input type="text" name="phone" placeholder="Телефон">
            </div>
            <div class="add">
                <label>Адрес</label> 
                <input type="text" name="location" placeholder="Адрес">
            </div>
            <div class="add">
                <label>Email</label> 
                <input type="email" name="email" placeholder="Email">
            </div>
            <div class="add">
                <label>Комментарий</label> 
                <textarea name="comment" placeholder="Краткий комментарий"></textarea>
            </div>
            <button type="submit" value="Добавить" name="button" class="form-btn">Добавить</button>
        </div>
    </form>';
    
    return $html;
}
?>