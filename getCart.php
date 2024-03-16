<?php
// Подключаем необходимые файлы и классы
require_once 'repositories/CartRepository.php';
require_once 'services/CartService.php';

use repositories\CartRepository;
use services\CartService;

// Подключаемся к базе данных
include_once 'config/db_connection.php';
global $conn;

// Получаем userId из запроса
$userId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : null;

if ($userId !== null) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Если запрос метода GET, получаем данные о корзине по userId
        $cartRepository = new CartRepository($conn);
        $cartService = new CartService($cartRepository);
        try {
            $cartData = $cartService->getCartByUserId($userId);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // Отправляем данные о корзине в формате JSON
        header('Content-Type: application/json');
        echo json_encode($cartData);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Если запрос метода POST, обрабатываем данные о корзине
        $productId = isset($_POST['productId']) ? $_POST['productId'] : null;
        $count = isset($_POST['count']) ? $_POST['count'] : 0;

        if ($productId !== null && is_numeric($count)) {
            $cartRepository = new CartRepository($conn);
            $cartService = new CartService($cartRepository);
            $cartService->addToCart($userId, $productId, $count);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Invalid parameters']);
        }
    }
} else {
    // Если userId не передан, возвращаем ошибку
    echo json_encode(['error' => 'User ID is missing']);
}
?>
