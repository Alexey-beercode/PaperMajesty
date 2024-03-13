<?php
use DI\ContainerBuilder;
use DI\Container;

require 'C:\Users\Алексей\vendor\autoload.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../repositories/UserRoleRepository.php';

use repositories\UserRepository;
use repositories\UserRoleRepository;
use services\UserService;

$containerBuilder = new ContainerBuilder();
// Register dependencies
$containerBuilder->addDefinitions([
    PDO::class => function () {
        $host = "localhost";
        $dbname = "papermajesty";
        $user = "root";
        $password = "CHEATS145";
        $dsn = "mysql:host=$host;dbname=$dbname"; // Определяем DSN
        return new PDO($dsn, $user, $password);
    },
    UserRepository::class => function (Container $container) {
        return new UserRepository($container->get(PDO::class));
    },
    UserRoleRepository::class => function (Container $container) {
        return new UserRoleRepository($container->get(PDO::class));
    },
    UserService::class => function (Container $container) {
        return new UserService($container->get(UserRepository::class));
    }
]);

$container = $containerBuilder->build();
