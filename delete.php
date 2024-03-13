<!DOCTYPE html>
<html>
<head>
    <title>Удаление пользователя</title>
    <meta charset="utf-8">
</head>
<body>
<h2>Введите ID пользователя:</h2>
<form action='delete.php' method="POST">
    <p><input type="text" name="id" /></p>
    <input type="submit" value="Отправить">
</form>

<?php

require_once 'repositories/UserRepository.php';
require_once 'repositories/RoleRepository.php';

use repositories\UserRepository;

include_once 'config/db_connection.php';
global $conn;
$userRepository=new UserRepository($conn);

// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Получение ID пользователя
    $id = $_POST['id'];

    // Проверка ID
    if (!$id) {
        echo "<p>Неверный ID пользователя.</p>";
        exit;
    }

    $userRepository->delete($id);

    // Вывод сообщения
    echo "<p>Пользователь с ID $id успешно удален.</p>";
}
include 'getProducts.php'
?>
</body>
</html>
