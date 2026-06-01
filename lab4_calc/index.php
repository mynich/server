<?php

// Если пришел POST-запрос с выражением
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['expression'])) {
    header('Content-Type: application/json');
    
    $expression = $_POST['expression'];
    
    // Валидация: проверяем, что выражение не пустое
    if (empty(trim($expression))) {
        echo json_encode(['error' => 'Введите выражение']);
        exit;
    }
    
    try {
        // Вычисляем выражение
        $result = evaluateExpression($expression);
        // Округляем до 10 знаков для красоты
        $result = round($result, 10);
        echo json_encode(['result' => $result]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// ============================================
// Рекурсивный парсер выражений
// ============================================

function evaluateExpression($expr) {
    // Убираем все пробелы
    $expr = str_replace(' ', '', $expr);
    
    // Заменяем константы на их значения
    $expr = str_replace('π', M_PI, $expr);      // pi = 3.14159...
    $expr = str_replace('e', M_E, $expr);       // e = 2.71828...
    
    // Заменяем функции на удобный формат: sqrt(4) -> √(4)
    // но оставляем для внутреннего использования
    
    $pos = 0;
    $len = strlen($expr);
    
    // Основной парсер
    $result = parseExpression($expr, $pos, $len);
    
    // Проверяем, что разобрали всё выражение
    if ($pos < $len) {
        throw new Exception("Лишние символы в выражении");
    }
    
    return $result;
}

/**
 * Парсит сложение и вычитание
 * Пример: 2+3-1
 */
function parseExpression($expr, &$pos, $len) {
    $value = parseTerm($expr, $pos, $len);
    
    while ($pos < $len) {
        $char = $expr[$pos];
        
        if ($char === '+') {
            $pos++;
            $value += parseTerm($expr, $pos, $len);
        } 
        elseif ($char === '-') {
            $pos++;
            $value -= parseTerm($expr, $pos, $len);
        } 
        else {
            break;
        }
    }
    
    return $value;
}

/**
 * Парсит умножение, деление и степень
 * Пример: 2*3/4^2
 */
function parseTerm($expr, &$pos, $len) {
    $value = parseFactor($expr, $pos, $len);
    
    while ($pos < $len) {
        $char = $expr[$pos];
        
        if ($char === '*') {
            $pos++;
            $value *= parseFactor($expr, $pos, $len);
        } 
        elseif ($char === '/') {
            $pos++;
            $divisor = parseFactor($expr, $pos, $len);
            if ($divisor == 0) {
                throw new Exception("Деление на ноль");
            }
            $value /= $divisor;
        } 
        elseif ($char === '^') {
            $pos++;
            $value = pow($value, parseFactor($expr, $pos, $len));
        } 
        else {
            break;
        }
    }
    
    return $value;
}

/**
 * Парсит числа, скобки, функции и унарный минус
 * Пример: (2+3), sqrt(16), -5
 */
function parseFactor($expr, &$pos, $len) {
    // Пропускаем возможные пробелы (хотя мы уже убрали)
    while ($pos < $len && $expr[$pos] === ' ') $pos++;
    
    if ($pos >= $len) {
        throw new Exception("Неожиданный конец выражения");
    }
    
    $char = $expr[$pos];
    
    // ===== УНАРНЫЙ МИНУС (отрицательные числа) =====
    // Пример: -5 + 3   или   -(2+3)
    if ($char === '-') {
        $pos++;
        $factor = parseFactor($expr, $pos, $len);
        return -$factor;
    }
    
    // ===== СКОБКИ =====
    if ($char === '(') {
        $pos++;
        $value = parseExpression($expr, $pos, $len);
        
        // Ожидаем закрывающую скобку
        if ($pos >= $len || $expr[$pos] !== ')') {
            throw new Exception("Закрывающая скобка не найдена");
        }
        $pos++;
        return $value;
    }
    
    // ===== ФУНКЦИИ =====
    // Проверяем, начинается ли с буквы (функция)
    if (ctype_alpha($char)) {
        return parseFunction($expr, $pos, $len);
    }
    
    // ===== ЧИСЛА (целые и дробные) =====
    return parseNumber($expr, $pos, $len);
}

/**
 * Парсит математические функции
 * Поддерживает: sqrt, ln, log, факториал (через постфиксный !)
 */
function parseFunction($expr, &$pos, $len) {
    $start = $pos;
    
    // Считываем название функции
    while ($pos < $len && ctype_alpha($expr[$pos])) {
        $pos++;
    }
    
    $funcName = substr($expr, $start, $pos - $start);
    $funcName = strtolower($funcName);
    
    // ===== ФАКТОРИАЛ (особый случай, может быть без скобок) =====
    // Пример: 5!  или  fact(5)
    if ($funcName === 'fact') {
        // Ожидаем открывающую скобку
        if ($pos >= $len || $expr[$pos] !== '(') {
            throw new Exception("Ожидается ( после fact");
        }
        $pos++; // пропускаем (
        
        $arg = parseExpression($expr, $pos, $len);
        
        // Ожидаем закрывающую скобку
        if ($pos >= $len || $expr[$pos] !== ')') {
            throw new Exception("Закрывающая скобка не найдена");
        }
        $pos++;
        
        return factorial($arg);
    }
    
    // Для остальных функций ожидаем скобку
    if ($pos >= $len || $expr[$pos] !== '(') {
        throw new Exception("Ожидается ( после $funcName");
    }
    $pos++; // пропускаем (
    
    $arg = parseExpression($expr, $pos, $len);
    
    // Ожидаем закрывающую скобку
    if ($pos >= $len || $expr[$pos] !== ')') {
        throw new Exception("Закрывающая скобка не найдена");
    }
    $pos++;
    
    // Вычисляем соответствующую функцию
    switch ($funcName) {
        case 'sqrt':
            if ($arg < 0) throw new Exception("Корень из отрицательного числа");
            return sqrt($arg);
        case 'ln':
            if ($arg <= 0) throw new Exception("ln(x) определен только для x > 0");
            return log($arg);  // log() в PHP - натуральный
        case 'log':
            if ($arg <= 0) throw new Exception("log(x) определен только для x > 0");
            return log10($arg); // десятичный логарифм
        default:
            throw new Exception("Неизвестная функция: $funcName");
    }
}

/**
 * Парсит число (целое или дробное)
 */
function parseNumber($expr, &$pos, $len) {
    $start = $pos;
    $hasDot = false;
    
    while ($pos < $len) {
        $char = $expr[$pos];
        
        if (ctype_digit($char)) {
            $pos++;
        } 
        elseif ($char === '.' && !$hasDot) {
            $hasDot = true;
            $pos++;
        } 
        else {
            break;
        }
    }
    
    if ($start === $pos) {
        throw new Exception("Ожидалось число, найдено: " . ($pos < $len ? $expr[$pos] : 'конец'));
    }
    
    $number = (float)substr($expr, $start, $pos - $start);
    
    // ===== ПОСТФИКСНЫЙ ФАКТОРИАЛ =====
    // Пример: 5!  (восклицательный знак ПОСЛЕ числа)
    if ($pos < $len && $expr[$pos] === '!') {
        $pos++;
        // Проверяем, что число целое
        if ($number != (int)$number) {
            throw new Exception("Факториал определен только для целых чисел");
        }
        return factorial($number);
    }
    
    return $number;
}

/**
 * Вычисляет факториал числа
 */
function factorial($n) {
    if ($n < 0) {
        throw new Exception("Факториал не определен для отрицательных чисел");
    }
    if ($n != (int)$n) {
        throw new Exception("Факториал определен только для целых чисел");
    }
    
    $result = 1;
    for ($i = 2; $i <= $n; $i++) {
        $result *= $i;
    }
    return $result;
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Калькулятор с поддержкой функций | PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="calculator">
        <h1>Калькулятор</h1>
        
        <!-- Поле ввода -->
        <div class="display">
            <input type="text" id="expression" placeholder="0" readonly>
            <div class="result" id="result"></div>
        </div>
        
        <!-- Кнопки калькулятора -->
        <div class="buttons">
            <!-- Цифры -->
            <button class="btn num" data-value="7">7</button>
            <button class="btn num" data-value="8">8</button>
            <button class="btn num" data-value="9">9</button>
            
            <!-- Операторы -->
            <button class="btn op" data-value="/">÷</button>
            
            <button class="btn num" data-value="4">4</button>
            <button class="btn num" data-value="5">5</button>
            <button class="btn num" data-value="6">6</button>
            <button class="btn op" data-value="*">×</button>
            
            <button class="btn num" data-value="1">1</button>
            <button class="btn num" data-value="2">2</button>
            <button class="btn num" data-value="3">3</button>
            <button class="btn op" data-value="-">-</button>
            
            <button class="btn num" data-value="0">0</button>
            <button class="btn num" data-value=".">.</button>
            <button class="btn func" id="clear">C</button>
            <button class="btn op" data-value="+">+</button>
            
            <!-- Скобки -->
            <button class="btn func" id="open">(</button>
            <button class="btn func" id="close">)</button>
            
            <!-- Бонусные функции (1 балл) -->
            <button class="btn func" id="pow">xʸ</button>
            <button class="btn func" id="sqrt">√</button>
            
            <button class="btn func" id="ln">ln</button>
            <button class="btn func" id="log">log</button>
            <button class="btn func" id="fact">n!</button>
            
            <!-- Константы (1 балл) -->
            <button class="btn const" id="pi">π</button>
            <button class="btn const" id="e">e</button>
            
            <!-- Расчет -->
            <button class="btn equals" id="equals">=</button>
        </div>
        
        <!-- Сообщения об ошибках -->
        <div class="error" id="error"></div>
        
        <!-- Подсказка по клавишам (2 балла) -->
        <div class="keyboard-hint">
            ⌨️ Клавиатура: цифры, + - * / ( ), Enter, Backspace, ^, s (√), l (ln), g (log), f (fact), p (π), E (e)
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>