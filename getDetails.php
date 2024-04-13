<?php
// Подключаем необходимые файлы и классы
require_once 'repositories/ProductRepository.php';
require_once 'services/ProductService.php';

use repositories\ProductRepository;
use services\ProductService;

// Подключаемся к базе данных
include_once 'config/db_connection.php';
include_once 'getOrderHistory.php';
global $conn;

function getProductDetails($productId)
{
    try {
        global $conn;
        // Создаем экземпляры репозитория и сервиса для работы с продуктами
        $productService=getProductService();
        // Получаем детали товара по его ID
        return $productService->getById($productId);
    } catch (Exception $exception) {
        // Если произошла ошибка, выводим сообщение об ошибке
        return null;
    }
}
function getAddToCartDiv($productId)
{
    $output='';
    $output.='<div class="d-flex align-items-center mb-4 pt-2">';
    $output.='<div class="input-group quantity mr-3" style="width: 130px;">';
    $output.='<div class="input-group-btn">';
    $output.='<button class="btn btn-primary btn-minus">';
    $output.='<i class="fa fa-minus"></i>';
    $output.='</button>';
    $output.='</div>';
    $output.='<input id="input-count" type="text" class="form-control bg-secondary text-center input-quantity" value="1">';
    $output.='<div class="input-group-btn">';
    $output.='<button class="btn btn-primary btn-plus">';
    $output.='<i class="fa fa-plus"></i>';
    $output.='</button>';
    $output.='</div>';
    $output.='</div>';
    $output.='<button id="add-to-card" class="btn btn-primary px-3 add-to-card" data-product-id="'.$productId.'">';
    $output.='<i class="fa fa-shopping-cart mr-1"></i>Добавить в корзину';
    $output.='</button>';
    $output.='</div>';
    return $output;
}
?>
