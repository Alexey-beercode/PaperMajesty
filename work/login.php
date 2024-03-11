<?php
// Подключаем файл, содержащий UserRepository
require_once 'repositories/UserRepository.php';
require_once 'repositories/RoleRepository.php';

use repositories\RoleRepository;
use repositories\UserRepository;

include_once 'config/db_connection.php';
global $conn;
$user = [
    'login' => 'alexey11',
    'name' => 'Алексей', // Assuming name is added
    'email' => 'alsemkovbn@gmail.com',
    'password' => 'CHEATS145', // Plain text password
];

try {

    $userRepository = new UserRepository($conn);
    $roleRepository=new RoleRepository($conn);
    $roles = $roleRepository->getAll();
    $users=$userRepository->getAll();
    $userById=$userRepository->find("8909FECE-5A94-4671-872D-EB0AF4D23318");


    echo "Roles<br>";
    foreach ($roles as $role) {
        echo "ID: " . $role["id"] . "<br>";
        echo "Имя: " . $role["name"] . "<br>";
        echo "<br>";
    }
    echo "Users<br>";
    foreach ($users as $user){
        echo "ID: ".$user["id"]."<br>";
        echo "Имя: ".$user["name"]."<br>";
        echo "<br>";
    }
    echo "User by id<br>";
    echo "ID: ".$userById["id"]."<br>";
    echo "Имя: ".$userById["name"]."<br>";
    echo "<br>";

} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
