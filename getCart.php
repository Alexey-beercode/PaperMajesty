<?php

// Подключаем необходимые файлы и классы
require_once 'repositories/CartRepository.php';
require_once 'services/CartService.php';

use repositories\CartRepository;
use services\CartService;

// Подключаемся к базе данных
include_once 'config/db_connection.php';

// Получаем userId из запроса
function getCartByUserid($userId)
{
    global $conn;
    if ($userId !== null) {
            // Если запрос метода GET, получаем данные о корзине по userId
            $cartRepository = new CartRepository($conn);
            $cartService = new CartService($cartRepository);
            try {
                $cartData = $cartService->getCartByUserId($userId);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            if ($cartData==null)
                echo "No products";
            // Отправляем данные о корзине в формате JSON
            return $cartData;

    } else {
        // Если userId не передан, возвращаем ошибку
        echo json_encode(['error' => 'User ID is missing']);
    }

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = isset($_GET['userId']) ? $_GET['userId'] : null;
    session_start();
    if ($userId !== null) {
        $cartRepository = new CartRepository($conn);
        $cartService = new CartService($cartRepository);
        try {
            $cartData = $cartService->getCartByUserId($userId);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        if ($cartData==null)
        {
            echo "No products";
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode($cartData);
    }
}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Если запрос метода POST, обрабатываем данные о корзине
    global $conn;
    $userId=$_SESSION['userId'];
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



?>
