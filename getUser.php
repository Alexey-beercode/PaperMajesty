<!DOCTYPE html>
<html>
<head>
    <title>Получение пользователя</title>
    <meta charset="utf-8">
</head>
<body>
<h2>Введите ID пользователя:</h2>
<form action='getUser.php' method="POST">
    <p><input type="text" name="id" /></p>
    <input type="submit" value="Отправить">
</form>

<?php
require_once 'config/container.php';
use repositories\UserRoleRepository;

$container = new Container();

$userService = $container->get(UserService::class);
$userRoleRepository=$container->get(UserRoleRepository::class);

include_once 'config/db_connection.php';
global $conn;
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Получение ID пользователя
        $id = $_POST['id'];

        // Проверка ID
        if (!$id) {
            echo "<p>Неверный ID пользователя.</p>";
            exit;
        }

        $user=$userService->getUser($id);
        $roleByUser=$userRoleRepository->findRolesByUserId($id);

        // Вывод сообщения
        echo "Имя : ".$user["name"]."<br>Роль :".$roleByUser[0]["name"]."";
    }
}
catch (Exception $e){
    echo $e->getMessage();
}



