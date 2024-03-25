<?php
session_start();

// Подключаем необходимые файлы и классы
require_once 'services/UserService.php';
require_once 'repositories/UserRepository.php';

use repositories\UserRepository;
use services\UserService;

// Подключаемся к базе данных
include_once 'config/db_connection.php';
global $conn;

$userRepository=new UserRepository($conn);
// Создаем экземпляр UserService
$userService = new UserService($userRepository);

// Проверяем, отправлена ли форма регистрации
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['login']) && isset($_POST['email']) && isset($_POST['password'])) {
// Получаем данные из формы
        $name = $_POST['name'];
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];

// Пытаемся зарегистрировать пользователя
        $result = $userService->createUser($login, $password, $name, $email);

        if ($result) {
            // Если регистрация прошла успешно, устанавливаем сессию для нового пользователя
            $_SESSION['is_authenticated'] = true;
            $_SESSION['userId']=$result['id'];
            $_SESSION['name']=$result['name'];
            $_SESSION['email']=$result['email'];
            $_SESSION['login']=$result['login'];
            $_SESSION['coupon_code']='';

            // Перенаправляем пользователя на главную страницу или другую страницу после регистрации
            header('Location: index.php');
            exit;
        } else {
            // Если регистрация не удалась, отобразим сообщение об ошибке или выполним другие действия
            echo "Ошибка регистрации пользователя";
        }
    }
}
?>