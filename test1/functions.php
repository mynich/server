<?php
function calculateTrig($func, $param) {
    $functions = [
        'sin' => 'sin',
        'cos' => 'cos',
        'tan' => 'tan',
        'cot' => function($x) { return 1 / tan($x); }
    ];
    
    if (isset($functions[$func])) {
        $rad = deg2rad($param);
        if (is_callable($functions[$func])) {
            return $functions[$func]($rad);
        }
        return call_user_func($functions[$func], $rad);
    }
    return null;
}

function evaluateExpression($expr) {
    $expr = str_replace(' ', '', $expr);
    preg_match('/([0-9\/\.]+)\*([a-z]+)\(([0-9]+)\)/', $expr, $matches);
    if (count($matches) == 4) {
        $coeff = evaluateFraction($matches[1]);
        $func = $matches[2];
        $angle = $matches[3];
        $trigValue = calculateTrig($func, $angle);
        return $coeff * $trigValue;
    }
    return null;
}

function evaluateFraction($frac) {
    if (strpos($frac, '/') !== false) {
        $parts = explode('/', $frac);
        return $parts[0] / $parts[1];
    }
    return (float)$frac;
}
?>