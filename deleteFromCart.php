<?php
global $conn;
require_once 'repositories/CartRepository.php';
require_once 'services/CartService.php';
require_once 'repositories/ProductRepository.php';
require_once 'services/ProductService.php';

use repositories\CartRepository;
use repositories\ProductRepository;
use services\CartService;
use services\ProductService as ProductService;

// Подключаемся к базе данных
include_once 'config/db_connection.php';
session_start();

if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true) {
    // Если пользователь не авторизован, отправляем ошибку
    echo json_encode(['error' => 'User is not authenticated']);
    exit;
}

// Проверяем, что productId был передан
if (!isset($_POST['productId'])) {
    echo json_encode(['error' => 'Product ID is missing']);
    exit;
}

// Получаем productId из POST запроса и userId из сессии
$productId = $_POST['productId'];
$userId = $_SESSION['userId'];
error_log('Product ID: ' . $productId);
// Ваш код для удаления товара из корзины по userId и productId

// Примерный код для удаления товара из корзины
$cartRepository = new CartRepository($conn);
$cartService = new CartService($cartRepository);
$cartService->deleteFromUserCartByProductId($userId, $productId);

// Возвращаем успешный ответ
echo json_encode(['success' => true]);
?>
