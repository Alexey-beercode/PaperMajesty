<?php
session_start();
// Подключаем файл конфигурации базы данных и UserService
use repositories\UserRepository;
use services\UserService;

include_once 'config/db_connection.php';
include_once 'services/UserService.php';
include_once 'repositories/UserRepository.php';
global $conn;
$userRepository=new UserRepository($conn);
// Создаем экземпляр UserService
$userService = new UserService($userRepository);


// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, существуют ли данные в полях формы
    if (isset($_POST['login']) && isset($_POST['password'])) {
        // Получаем данные из формы
        $login = $_POST['login'];
        $password = $_POST['password'];

        // Пытаемся аутентифицировать пользователя
        $user = $userService->authenticate($login, $password);

        // Проверяем, удалось ли аутентифицировать пользователя
        if ($user) {
            // Пользователь найден, устанавливаем сессию и перенаправляем на главную страницу

            $_SESSION['is_authenticated'] = true;
            $_SESSION['userId']=$user['id'];
            $_SESSION['name']=$user['name'];
            $_SESSION['email']=$user['email'];
            $_SESSION['login']=$user['login'];

            header('Location: index.php'); // Перенаправляем на главную страницу
            exit();
        } else {
            // Пользователь не найден, выведем сообщение об ошибке
            $error = "Неверное имя пользователя или пароль";
            echo $error;
        }
    }
}
?>
