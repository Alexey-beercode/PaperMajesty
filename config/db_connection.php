<?php
$host = "localhost";
$dbname = "papermajesty";
$user = "root";
$password = "CHEATS145";
//1452144admin44445
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    // Установка режима ошибок PDO для выброса исключений
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
