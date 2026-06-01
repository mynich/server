<?php

echo "<h1>=== Задание 1: Абстрактный класс Human ===</h1>";

// ========== ЗАДАНИЕ 1: АБСТРАКТНЫЙ КЛАСС HUMAN ==========
abstract class HumanAbstract
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    abstract public function getGreetings(): string;
    abstract public function getMyNameIs(): string;

    public function introduceYourself(): string
    {
        return $this->getGreetings() . '! ' . $this->getMyNameIs() . ' ' . $this->getName() . '.';
    }
}

class RussianHuman extends HumanAbstract
{
    public function getGreetings(): string
    {
        return "Привет";
    }

    public function getMyNameIs(): string
    {
        return "Меня зовут";
    }
}

class EnglishHuman extends HumanAbstract
{
    public function getGreetings(): string
    {
        return "Hello";
    }

    public function getMyNameIs(): string
    {
        return "My name is";
    }
}

$russian = new RussianHuman("Иван");
$english = new EnglishHuman("John");

echo "<b>Русский:</b> " . $russian->introduceYourself() . "<br>";
echo "<b>Англичанин:</b> " . $english->introduceYourself() . "<br>";

// =====================================================

echo "<hr>";
echo "<h1>=== Задание 2: Кошки (инкапсуляция) ===</h1>";

// ========== ЗАДАНИЕ 2: КОШКИ ==========
class Cat
{
    private string $name;
    private string $color;

    public function __construct(string $name, string $color)
    {
        $this->name = $name;
        $this->color = $color;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function sayHello(): string
    {
        return "Меня зовут " . $this->name . ", я " . $this->color . " цвета.";
    }
}

$cat1 = new Cat("Мурка", "белого");
$cat2 = new Cat("Барсик", "чёрного");
$cat3 = new Cat("Рыжик", "рыжего");

echo $cat1->sayHello() . "<br>";
echo $cat2->sayHello() . "<br>";
echo $cat3->sayHello() . "<br>";

echo "<br><b>Цвет кошки через геттер:</b> " . $cat1->getName() . " — " . $cat1->getColor() . "<br>";

// =====================================================

echo "<hr>";
echo "<h1>=== Задание 3: Интерфейсы (площадь фигур) ===</h1>";

// ========== ЗАДАНИЕ 3: ИНТЕРФЕЙСЫ ==========
interface CalculateSquare
{
    public function calculateSquare(): float;
}

class Circle implements CalculateSquare
{
    private float $radius;

    public function __construct(float $radius)
    {
        $this->radius = $radius;
    }

    public function calculateSquare(): float
    {
        return pi() * pow($this->radius, 2);
    }
}

class Rectangle implements CalculateSquare
{
    private float $width;
    private float $height;

    public function __construct(float $width, float $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function calculateSquare(): float
    {
        return $this->width * $this->height;
    }
}

class Square implements CalculateSquare
{
    private float $side;

    public function __construct(float $side)
    {
        $this->side = $side;
    }

    public function calculateSquare(): float
    {
        return pow($this->side, 2);
    }
}

class Triangle
{
    private float $base;
    private float $height;

    public function __construct(float $base, float $height)
    {
        $this->base = $base;
        $this->height = $height;
    }

    public function getArea(): float
    {
        return 0.5 * $this->base * $this->height;
    }
}

function printSquare($object)
{
    $className = get_class($object);
    
    if ($object instanceof CalculateSquare) {
        $square = $object->calculateSquare();
        echo "Объект класса {$className} имеет площадь: {$square}<br>";
    } else {
        echo "Объект класса {$className} не реализует интерфейс CalculateSquare<br>";
    }
}

$circle = new Circle(5);
$rectangle = new Rectangle(4, 6);
$square = new Square(4);
$triangle = new Triangle(3, 4);

printSquare($circle);
printSquare($rectangle);
printSquare($square);
printSquare($triangle);

// =====================================================

echo "<hr>";
echo "<h1>=== Задание 4: Наследование (PaidLesson) ===</h1>";

// ========== ЗАДАНИЕ 4: НАСЛЕДОВАНИЕ ==========
class Lesson
{
    private string $title;
    private string $text;
    private string $homework;

    public function __construct(string $title, string $text, string $homework)
    {
        $this->title = $title;
        $this->text = $text;
        $this->homework = $homework;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getHomework(): string
    {
        return $this->homework;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function setHomework(string $homework): void
    {
        $this->homework = $homework;
    }
}

class PaidLesson extends Lesson
{
    private float $price;

    public function __construct(string $title, string $text, string $homework, float $price)
    {
        parent::__construct($title, $text, $homework);
        $this->price = $price;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}

$paidLesson = new PaidLesson(
    "Урок о наследовании в PHP",
    "Лол, кек, чебурек",
    "Ложитесь спать, утро вечера мудренее",
    99.90
);

echo "<h3>Вывод через var_dump():</h3>";
echo "<pre>";
var_dump($paidLesson);
echo "</pre>";

echo "<h3>Доступ к свойствам через геттеры:</h3>";
echo "<b>Название урока:</b> " . $paidLesson->getTitle() . "<br>";
echo "<b>Текст урока:</b> " . $paidLesson->getText() . "<br>";
echo "<b>Домашнее задание:</b> " . $paidLesson->getHomework() . "<br>";
echo "<b>Цена урока:</b> " . $paidLesson->getPrice() . " руб.<br>";

?>