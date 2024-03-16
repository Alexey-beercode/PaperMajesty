<?php
session_start();

// Подключаем необходимые файлы и классы
require_once 'services/UserService.php';

use services\UserService;

// Подключаемся к базе данных
include_once 'config/db_connection.php';
global $conn;

// Создаем экземпляр UserService
$userService = new UserService($conn);

// Проверяем, отправлена ли форма регистрации
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Проверяем, существуют ли данные в полях формы
    if (isset($_POST['name']) && isset($_POST['login']) && isset($_POST['email']) && isset($_POST['password'])) {
// Получаем данные из формы
        $name = $_POST['name'];
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password = $_POST['password'];

// Пытаемся зарегистрировать пользователя
        $result = $userService->createUser($login,$password,$name,$email);

        if ($result) {
            // Если регистрация прошла успешно, устанавливаем сессию для нового пользователя
            $_SESSION['is_authenticated'] = true;

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