<?php
// Подключаем необходимые файлы и классы
require_once 'repositories/CartRepository.php';
require_once 'services/CartService.php';
require_once 'repositories/ProductRepository.php';
require_once 'services/ProductService.php';

use repositories\CartRepository;
use services\CartService;

// Подключаемся к базе данных
include_once 'config/db_connection.php';
session_start();
// Получаем userId из сессии
$userId = $_SESSION['userId'];

// Проверяем, что userId получен из сессии
if (!$userId) {
    echo json_encode(['error' => 'User ID is missing']);
    header('Location: index.php');
    exit;
}
global $conn;
// Создаем экземпляр сервиса для работы с корзиной
$cartRepository = new CartRepository($conn);
$cartService = new CartService($cartRepository);

// Проверяем, что запрос является POST-запросом
    // Получаем данные о товаре и количестве из POST-запроса
    $productId = $_POST['productId'];
    $count = $_POST['count'];
error_log('Product ID: ' . $productId);
error_log('Count: ' . $count);

    // Проверяем, что productId и count переданы
    if (!$productId || !$count) {
        echo json_encode(['error' => 'Product ID or count is missing']);
        exit;
    }

    // Добавляем товар в корзину
    $cartService->addToCart($userId, $productId, $count);
error_log("good add to Cart");

    // Возвращаем успешный JSON-ответ
echo json_encode(['success' => true]);
exit;
?>
