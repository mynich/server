<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Hello, World!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            background: #f0f0f0;
            border-bottom: 2px solid #0033a0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo img {
            height: 50px;
        }

        .title {
            font-size: 20px;
            color: #0033a0;
        }

        .main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .content {
            background: white;
            padding: 60px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .content h1 {
            font-size: 56px;
            color: #333;
            margin-bottom: 20px;
        }

        .dynamic-text {
            margin-top: 30px;
            padding: 15px;
            background: #e8f4f8;
            border-radius: 10px;
            color: #004085;
            font-size: 18px;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background: #2c3e50;
            color: white;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            <img src="logo.jpg" alt="МосПолитех">
        </div>
        <div class="title">Лабораторная работа: Hello, World!</div>
    </div>

    <div class="main">
        <div class="content">
            <h1>Hello, World!</h1>
            <div class="dynamic-text">
                <?php
                echo "Сегодня: " . date("d.m.Y");
                echo "<br>Время: " . date("H:i:s");
                ?>
            </div>
        </div>
    </div>

    <div class="footer">
        задание для самостоятельной работы
    </div>

</body>
</html>