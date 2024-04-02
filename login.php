<?php
session_start();
// Подключаем файл конфигурации базы данных и UserService
require_once 'services/UserService.php';
require_once 'repositories/UserRepository.php';
require_once 'repositories/UserRoleRepository.php';
require_once 'repositories/RoleRepository.php';
include_once 'config/db_connection.php';

use repositories\RoleRepository;
use repositories\UserRepository;
use repositories\UserRoleRepository;
use services\UserService;
global $conn;
$userRepository=new UserRepository($conn);
$roleRepository=new RoleRepository($conn);
$userRoleRepository=new UserRoleRepository($conn);
// Создаем экземпляр UserService
$userService = new UserService($userRepository,$roleRepository,$userRoleRepository);


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
            $_SESSION['coupon_code']='';
            $roles=$userRoleRepository->findRolesByUserId($user['id']);
            foreach ($roles as $role)
            {
                $_SESSION['roles'].=" ";
                $_SESSION['roles'].=$role['name'];
            }

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
