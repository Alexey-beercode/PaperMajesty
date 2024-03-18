<?php
require_once 'repositories/ProductRepository.php';
require_once 'repositories/ProductCategoryRepository.php';
require_once 'services/ProductService.php';

use repositories\ProductCategoryRepository;
use repositories\ProductRepository;
use services\ProductService;

include_once 'config/db_connection.php';
global $conn;
$productRepository = new ProductRepository($conn);
$productService = new ProductService($productRepository);

// Получаем поисковый запрос из POST параметра
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
$searchTerm=$_POST['searchTerm'];
error_log($searchTerm);

// Если поисковый запрос не пустой
if (!empty($searchTerm)) {
    try {
        error_log("starting search");
        // Ищем продукты по поисковому запросу
        $products = $productService->searchByName($searchTerm);
        foreach ($products as $product)
        {
            $productName=$product['name'];
            error_log($productName);
        }


        // Возвращаем результаты в формате JSON
        echo json_encode($products);
    } catch (Exception $exception) {
        // Если произошла ошибка, возвращаем сообщение об ошибке
        echo json_encode(['error' => $exception->getMessage()]);
    }
} else {
    // Если поисковый запрос пустой, возвращаем пустой JSON объект
    echo json_encode([]);
}
?>

