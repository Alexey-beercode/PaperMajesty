<?php
// Подключаем необходимые файлы и классы
require_once 'repositories/ProductRepository.php';
require_once 'services/ProductService.php';

use repositories\ProductRepository;
use services\ProductService;

// Подключаемся к базе данных
include_once 'config/db_connection.php';
global $conn;

function getProductDetails($productId)
{
    try {
        global $conn;
        // Создаем экземпляры репозитория и сервиса для работы с продуктами
        $productRepository = new ProductRepository($conn);
        $productService = new ProductService($productRepository);
        // Получаем детали товара по его ID
        return $productService->getById($productId);
    } catch (Exception $exception) {
        // Если произошла ошибка, выводим сообщение об ошибке
        return null;
    }
}
?>
