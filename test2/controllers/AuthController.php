<?php
require_once 'models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }
    
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    public function validatePassword($password) {
        if (strlen($password) < 8) return false;
        if (!preg_match('/[A-Z]/', $password)) return false;
        if (!preg_match('/[0-9]/', $password)) return false;
        if (!preg_match('/[!?]/', $password)) return false;
        return true;
    }
    
    public function validateName($name) {
        return !empty(trim($name));
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            
            $errors = [];
            
            if (!$this->validateName($name)) {
                $errors[] = "Имя обязательно";
            }
            
            if (!$this->validateEmail($email)) {
                $errors[] = "Введите корректный email";
            }
            
            if (!$this->validatePassword($password)) {
                $errors[] = "Пароль должен быть не менее 8 символов, содержать заглавную букву, цифру и символ ! или ?";
            }
            
            if ($password !== $confirm) {
                $errors[] = "Пароли не совпадают";
            }
            
            if (empty($errors)) {
                if ($this->userModel->register($name, $email, $password)) {
                    header('Location: index.php?action=login&registered=1');
                    exit;
                } else {
                    $errors[] = "Пользователь с таким email уже существует";
                }
            }
            
            include 'views/register.php';
        } else {
            include 'views/register.php';
        }
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $errors = [];
            
            if (!$this->validateEmail($email)) {
                $errors[] = "Введите корректный email";
            }
            
            if (empty($errors)) {
                $user = $this->userModel->login($email, $password);
                if ($user) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    header('Location: index.php?action=dashboard');
                    exit;
                } else {
                    $errors[] = "Неверный email или пароль";
                }
            }
            
            include 'views/login.php';
        } else {
            include 'views/login.php';
        }
    }
    
    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
    
    public function dashboard() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        include 'views/dashboard.php';
    }
}
?>