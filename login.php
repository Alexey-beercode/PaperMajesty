<?php
// Подключаем файл конфигурации базы данных и UserService
include_once 'config/db_connection.php';
include_once 'services/UserService.php';

// Создаем экземпляр UserService
$userService = new UserService($conn);

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, существуют ли данные в полях формы
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Получаем данные из формы
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Пытаемся аутентифицировать пользователя
        $user = $userService->authenticate($username, $password);

        // Проверяем, удалось ли аутентифицировать пользователя
        if ($user) {
            // Пользователь найден, устанавливаем сессию и перенаправляем на главную страницу
            session_start();
            $_SESSION['is_authenticated'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header('Location: index.php'); // Перенаправляем на главную страницу
            exit();
        } else {
            // Пользователь не найден, выведем сообщение об ошибке
            $error = "Неверное имя пользователя или пароль";
        }
    }
}
?>


