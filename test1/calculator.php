<?php
require_once 'functions.php';

$filePath = 'expression.txt';

if (!file_exists($filePath)) {
    echo "Ошибка: файл expression.txt не найден";
} else {
    $expression = file_get_contents($filePath);
    $result = evaluateExpression($expression);
    
    if ($result !== null) {
        echo "Выражение: $expression\n";
        echo "Результат: " . round($result, 4);
    } else {
        echo "Не удалось разобрать выражение";
    }
}
?>