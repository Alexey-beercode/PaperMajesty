<?php


use DI\ContainerBuilder;
require 'C:\Users\Алексей\vendor\autoload.php';


$containerBuilder = new ContainerBuilder();
// Register dependencies
$containerBuilder->set(PDO::class, function ()  {

    $host = "localhost";
    $dbname = "papermajesty";
    $user = "root";
    $password = "CHEATS145";
//1452144admin44445
    return new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
});
$containerBuilder->set(UserRepository::class, function (Container $container) {
    return new UserRepository($container->get(PDO::class));
});
$containerBuilder->set(UserRoleRepository::class, function (Container $container) {
    return new UserRoleRepository($container->get(PDO::class));
});
$containerBuilder->set(UserService::class, function (Container $container) {
    return new UserService($container->get(UserRepository::class));
});

$container = $containerBuilder->build();

