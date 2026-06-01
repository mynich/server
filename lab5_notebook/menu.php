<?php
// ============================================
// МОДУЛЬ MENU (menu.php)
// Возвращает HTML код основного меню
// ============================================

/**
 * Возвращает HTML-код основного меню сайта
 * @param string $active - активный пункт меню (view, add, edit, delete)
 * @return string
 */
function renderMenu($active) {
    // Основные пункты меню
    $items = [
        'view' => 'Просмотр',
        'add' => 'Добавление записи',
        'edit' => 'Редактирование записи',
        'delete' => 'Удаление записи'
    ];
    
    $html = '<div class="main-menu">';
    foreach ($items as $key => $label) {
        $activeClass = ($active === $key) ? 'select' : '';
        $html .= '<a href="index.php?menu=' . $key . '" class="' . $activeClass . '">' . $label . '</a>';
    }
    $html .= '</div>';
    
    // Дополнительное подменю для просмотра (сортировка)
    if ($active === 'view') {
        $sort = $_GET['sort'] ?? 'date_desc';
        
        $sortItems = [
            'date_desc' => 'По дате добавления (новые сначала)',
            'date_asc' => 'По дате добавления (старые сначала)',
            'surname_asc' => 'По фамилии (А-Я)',
            'birth_asc' => 'По дате рождения (молодые сначала)'
        ];
        
        $html .= '<div class="submenu">';
        foreach ($sortItems as $key => $label) {
            $activeClass = ($sort === $key) ? 'select' : '';
            $html .= '<a href="index.php?menu=view&sort=' . $key . '" class="' . $activeClass . '">' . $label . '</a>';
        }
        $html .= '</div>';
    }
    
    return $html;
}
?>