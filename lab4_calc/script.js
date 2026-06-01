let currentExpression = '';     // текущее выражение
let lastResult = null;           // последний результат

// DOM элементы
const exprInput = document.getElementById('expression');
const resultDiv = document.getElementById('result');
const errorDiv = document.getElementById('error');

// ========== ИНИЦИАЛИЗАЦИЯ ==========
document.addEventListener('DOMContentLoaded', () => {
    initButtons();
    initKeyboard();
});

// ========== ОБРАБОТКА КНОПОК ==========
function initButtons() {
    // Цифры и точка
    document.querySelectorAll('.num').forEach(btn => {
        btn.addEventListener('click', () => {
            addToExpression(btn.dataset.value);
        });
    });
    
    // Операторы (+, -, *, /)
    document.querySelectorAll('.op').forEach(btn => {
        btn.addEventListener('click', () => {
            addToExpression(btn.dataset.value);
        });
    });
    
    // Специальные кнопки
    document.getElementById('open').addEventListener('click', () => addToExpression('('));
    document.getElementById('close').addEventListener('click', () => addToExpression(')'));
    document.getElementById('pow').addEventListener('click', () => addToExpression('^'));
    document.getElementById('sqrt').addEventListener('click', () => addToExpression('sqrt('));
    document.getElementById('ln').addEventListener('click', () => addToExpression('ln('));
    document.getElementById('log').addEventListener('click', () => addToExpression('log('));
    document.getElementById('fact').addEventListener('click', () => addToExpression('fact('));
    document.getElementById('pi').addEventListener('click', () => addToExpression('π'));
    document.getElementById('e').addEventListener('click', () => addToExpression('e'));
    
    // Очистка
    document.getElementById('clear').addEventListener('click', () => {
        currentExpression = '';
        updateDisplay();
        clearResult();
        clearError();
    });
    
    // Вычисление
    document.getElementById('equals').addEventListener('click', () => {
        calculate();
    });
}

// ========== ПОДДЕРЖКА КЛАВИАТУРЫ (2 бонусных балла) ==========
function initKeyboard() {
    document.addEventListener('keydown', (e) => {
        const key = e.key;
        
        // Предотвращаем стандартное поведение для наших клавиш
        const handledKeys = ['0','1','2','3','4','5','6','7','8','9','.', 
                             '+','-','*','/','(',')','^','Enter','Backspace','Escape',
                             's','S','l','L','g','G','f','F','p','P','e','E'];
        
        if (handledKeys.includes(key)) {
            e.preventDefault();
        }
        
        // Цифры и точка
        if (/[\d\.]/.test(key)) {
            addToExpression(key);
        }
        // Операторы
        else if (key === '+') addToExpression('+');
        else if (key === '-') addToExpression('-');
        else if (key === '*') addToExpression('*');
        else if (key === '/') addToExpression('/');
        else if (key === '^') addToExpression('^');
        // Скобки
        else if (key === '(') addToExpression('(');
        else if (key === ')') addToExpression(')');
        // Функции (клавиши-подсказки)
        else if (key === 's' || key === 'S') addToExpression('sqrt(');
        else if (key === 'l' || key === 'L') addToExpression('ln(');
        else if (key === 'g' || key === 'G') addToExpression('log(');
        else if (key === 'f' || key === 'F') addToExpression('fact(');
        // Константы
        else if (key === 'p' || key === 'P') addToExpression('π');
        else if (key === 'e' || key === 'E') addToExpression('e');
        // Очистка
        else if (key === 'Escape') {
            currentExpression = '';
            updateDisplay();
            clearResult();
            clearError();
        }
        // Backspace (удаление последнего символа)
        else if (key === 'Backspace') {
            currentExpression = currentExpression.slice(0, -1);
            updateDisplay();
            clearError();
        }
        // Enter = вычисление
        else if (key === 'Enter') {
            calculate();
        }
    });
}

// ========== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ==========

// Добавить символ в выражение
function addToExpression(value) {
    currentExpression += value;
    updateDisplay();
    clearError();
}

// Обновить поле ввода
function updateDisplay() {
    exprInput.value = currentExpression || '0';
}

// Очистить результат
function clearResult() {
    resultDiv.textContent = '';
    lastResult = null;
}

// Очистить ошибку
function clearError() {
    errorDiv.textContent = '';
}

// Показать ошибку
function showError(message) {
    errorDiv.textContent = message;
    resultDiv.textContent = '';
}

// ========== ОТПРАВКА НА СЕРВЕР (POST) ==========
async function calculate() {
    if (!currentExpression.trim()) {
        showError('Введите выражение');
        return;
    }
    
    try {
        // Создаем FormData для POST-запроса
        const formData = new FormData();
        formData.append('expression', currentExpression);
        
        // Отправляем на сервер
        const response = await fetch('index.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.error) {
            showError(data.error);
        } else {
            // Показываем результат
            resultDiv.textContent = `= ${data.result}`;
            // Сохраняем результат как новое выражение (можно продолжать считать)
            currentExpression = String(data.result);
            updateDisplay();
            clearError();
        }
    } catch (err) {
        showError('Ошибка соединения с сервером');
        console.error(err);
    }
}