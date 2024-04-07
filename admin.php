<?php
include_once 'config/db_connection.php';
include_once 'getAdminTables.php';
require_once 'repositories/CouponRepository.php';
require_once 'repositories/OrderProductRepository.php';
require_once 'repositories/OrderStatusRepository.php';
require_once 'services/ProductService.php';
require_once 'repositories/ProductRepository.php';
require_once 'repositories/OrderRepository.php';
require_once 'repositories/ProductCategoryRepository.php';
require_once 'services/OrderService.php';
use repositories\CouponRepository;
use repositories\OrderProductRepository;
use repositories\OrderRepository;
use repositories\OrderStatusRepository;
use repositories\ProductCategoryRepository;
use services\ProductService;
use repositories\ProductRepository;
use services\OrderService;

function renderOrderStats($stats)
{
    $output = '';
    foreach ($stats as $categoryName => $percentage) {
        // Ограничиваем количество знаков после запятой до двух
        $formattedPercentage = number_format($percentage, 2);

        $output .= '<div class="media">';
        $output .= '<div class="media-body">';
        $output .= '<h5 class="media-heading">' . $categoryName . ' ' . $formattedPercentage . ' %</h5>';
        $output .= '<div class="progress progress-mini">';
        $output .= '<div class="progress-bar" role="progressbar" aria-valuenow="' . $percentage . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentage . '%;">';
        $output .= '<span class="sr-only">' . $percentage . '% Complete</span>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
    return $output;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Проверяем наличие параметра 'action' в запросе
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        global $conn;
        $couponRepository=new CouponRepository($conn);
        $orderProductRepository=new OrderProductRepository($conn);
        $productRepository=new ProductRepository($conn);
        $productService=new ProductService($productRepository);
        $orderRepository=new OrderRepository($conn);
        $orderStatusRepository=new OrderStatusRepository($conn);
        $productCategoryRepository=new ProductCategoryRepository($conn);
        $orderService=new OrderService($orderRepository,$orderStatusRepository,$orderProductRepository,$couponRepository,$productService,$productCategoryRepository);
        $orderCount=count($orderService->getAll());
        // Если action равен 'getOrderStats', вызываем соответствующий метод
        if ($action === 'getOrderStats') {
            // Получаем статистику заказов по категориям
            $stats =$orderService->getOrderStats();
            echo renderOrderStats($stats);;
            exit;
        }
        if ($action === 'getOrderCount') {
            echo $orderCount;
            exit;
        }
        if ($action==='getUsersTable'){
            echo renderUserTable();
            exit;
        }
        if ($action==='getOrderTable'){
            echo renderOrderTable();
            exit;
        }
        if ($action==='getProductTable'){
            echo renderProductTable();
            exit;
        }
        if ($action==='getPromotionTable'){
            echo renderPromotionTable();
            exit;
        }
        if ($action==='getCouponTable'){
            echo renderCouponTable();
            exit;
        }
    }
}
?>
