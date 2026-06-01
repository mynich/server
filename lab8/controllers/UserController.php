<?php

class UserController
{
    // Экшн для приветствия
    public function sayHello($name)
    {
        return "Привет, " . htmlspecialchars($name);
    }

    // Экшн для прощания
    public function sayBye($name)
    {
        return "Пока, " . htmlspecialchars($name);
    }
}