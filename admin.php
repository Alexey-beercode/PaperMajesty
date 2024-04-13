<?php
include_once 'config/db_connection.php';
include_once 'getAdminTables.php';
include_once 'adminServices.php';
include_once 'getOrderHistory.php';
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
        $productService=getProductService();
        $orderService=getOrderService();
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
        if ($action==='addProduct'){
            echo renderAddProduct();
            exit;
        }

    }
}
elseif($_SERVER['REQUEST_METHOD']==='POST'){
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action==='updateOrderStatus'){
        $orderId = $_POST['orderId'];
        $newStatus = $_POST['newStatus'];
        updateOrderStatus($orderId,$newStatus);
        header('Location: adminIndex.php');
        exit;
    }
    if ($action==='deleteProduct') {
        $productId=$_POST['productId'];
        error_log("Пришло");
        deleteProduct($productId);
    }
    if ($action==='updateNewPrice') {
        $productId=$_POST['productId'];
        $newPrice=$_POST['newPrice'];
        error_log("Пришло");
        updateNewPrice($productId,$newPrice);
        header('Location: adminIndex.php');
        exit;
    }
    if ($action==='updateStockQuantity') {
        $productId=$_POST['productId'];
        $newStockQuantity=$_POST['newStockQuantity'];
        error_log("Пришло");
        updateStockQuantity($productId,$newStockQuantity);
        header('Location: adminIndex.php');
        exit;
    }
    if ($action==='addProductToPromotion') {
        $productName=$_POST['productName'];
        $promotionId=$_POST['promotionId'];
        $discount=$_POST['discount'];
        error_log("Пришло");
        addProductToPromotion($productName,$promotionId,$discount);
        header('Location: adminIndex.php');
        exit;
    }
    if ($action==='addProduct'){
        $name=$_POST['name'];
        $price=$_POST['price'];
        $description=$_POST['description'];
        $categoryId=$_POST['categoryId'];
        $stockQuantity=$_POST['stockQuantity'];
        $imageUrl=$_POST['imageUrl'];
        $productService=getProductService();
        $product=[];
        $product['name']=$name;

        $productService->create();
    }

    }
}
?>
