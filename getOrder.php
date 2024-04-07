<?php
if (session_status() == PHP_SESSION_NONE) {
    // Если сессия не активна, запускаем новую сессию
    session_start();
}
require_once 'repositories/CartRepository.php';
require_once 'services/CartService.php';
require_once 'repositories/ProductRepository.php';
require_once 'services/ProductService.php';
require_once 'services/CouponService.php';
require_once 'repositories/CouponRepository.php';
require_once 'repositories/CouponDiscountRepository.php';
include_once 'config/db_connection.php';
require_once 'repositories/OrderRepository.php';
require_once 'repositories/OrderStatusRepository.php';
require_once 'repositories/OrderProductRepository.php';
require_once  'services/OrderService.php';
require_once 'repositories/ProductCategoryRepository.php';
use repositories\CartRepository;
use repositories\CouponRepository;
use repositories\CouponDiscountRepository;
use repositories\OrderProductRepository;
use repositories\OrderRepository;
use repositories\OrderStatusRepository;
use repositories\ProductCategoryRepository;
use repositories\ProductRepository;
use services\CartService;
use services\OrderService;
use services\ProductService as ProductService;
use services\CouponService;
global $conn;
function updateStockQuantity($cart)
{
    global $conn;
    $productRepository=new ProductRepository($conn);
    $productService=new ProductService($productRepository);
    foreach ($cart as $cartData)
    {
        $product=$productService->getById($cartData['productId']);
        $product['stockQuantity']-=$cartData['count'];
        $productService->update($product);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['address'])) {
        try {
            $couponCode = $_SESSION['coupon_code'];
            if ($couponCode == '') {
                $couponCode = null;
            }
            $userId = $_SESSION['userId'];
            error_log($userId);
            $name = $_POST['name'];
            $address = $_POST['address'];
            $cart = getCartByUserId($userId, $conn);
            $orderRepository = new OrderRepository($conn);
            $orderStatusRepository = new OrderStatusRepository($conn);
            $cartRepository = new CartRepository($conn);
            $cartService = new CartService($cartRepository);
            $orderProductRepository = new OrderProductRepository($conn);
            $couponRepository = new CouponRepository($conn);
            $productRepository = new ProductRepository($conn);
            $productService = new ProductService($productRepository);
            $productCategoryRepository = new ProductCategoryRepository($conn);
            $orderService = new OrderService($orderRepository, $orderStatusRepository, $orderProductRepository, $couponRepository, $productService, $productCategoryRepository);
            error_log($name);
            error_log($address);
            $orderService->createOrder($userId, $address, $couponCode, $cart, $name);
            error_log("it ok");
            $_SESSION['coupon_code'] = '';
            $cartService->clearCartByUserId($userId);
            updateStockQuantity($cart);
            error_log("true");
            echo json_encode(['success' => true]);
            exit;
        }
        catch (Exception $exception)
        {
            // Отправка текста ошибки в случае возникновения ошибки
            echo "Ошибка: " . $exception->getMessage();

        }

    }
}
function getCartByUserId($userId,$conn)
{
    $cartRepository = new CartRepository($conn);
    $cartService = new CartService($cartRepository);
    return $cartService->getCartByUserId($userId);
}

function getProductsAndDiscountsByCouponCode($couponCode,$conn,$productRepository)
{
    $couponRepository = new CouponRepository($conn);
    $couponDiscountRepository = new CouponDiscountRepository($conn);
    $couponService = new CouponService($couponRepository, $couponDiscountRepository, $productRepository);
    $productsAndDiscounts=$couponService->getProductsAndDiscountsByCouponCode($couponCode);
    return $productsAndDiscounts;
}
function renderProductInOrder($product, $count)
{
    $output = '';
    $output .= '<div class="d-flex justify-content-between">';
    $output.='<p>'.$product['name'].'('.$count.')</p>';
    $output.='<p>'.$product['price'].'руб</p>';
    $output.='</div>';
    return $output;
}

function getUserIdFromSession()
{
    return $_SESSION['userId'];
}
function getCouponCodeFromSession()
{
    return $_SESSION['coupon_code'];
}
function getOrder()
{
    $userId = $_SESSION['userId'];
    if ($userId !== null) {
        // Если запрос метода GET, получаем данные о корзине по userId
        $html = '';
        try {
            global $conn;
            $sum=0;
            $generalDiscount = 0; // По умолчанию скидки нет
            $couponCode = $_SESSION['coupon_code'];
            $productRepository = new ProductRepository($conn);
            $productService = new ProductService($productRepository);
            $productsAndDiscounts=getProductsAndDiscountsByCouponCode($couponCode,$conn,$productRepository);
            $cartData = getCartByUserid($userId,$conn);
            foreach ($cartData as $cartDatum) {
                $product = $productService->getById($cartDatum['productId']);
                $renderProductInOrder = renderProductInOrder($product, $cartDatum['count']);
                $sum += $product['price']*$cartDatum['count'];
                if (isset($product['new_price']))
                {
                    $generalDiscount+=($product['price']-$product['new_price'])*$cartDatum['count'];
                }
                foreach ($productsAndDiscounts as $productAndDiscount) {
                    if ($productAndDiscount['productId'] == $product['id']) {
                        $discount = $productAndDiscount['discount'];
                        $generalDiscount += $discount*$cartDatum['count'];
                        break;
                    }
                }
                $html .= $renderProductInOrder;
            }
            $html.='<hr class="mt-0">';
            $html.='<div class="d-flex justify-content-between mb-3 pt-1">';
            $html.='<h6 class="font-weight-medium">Сумма</h6>';
            $html.='<h6 class="font-weight-medium">'.$sum.' руб</h6>';
            $html.='</div>';
            $html.='<div class="d-flex justify-content-between">';
            $html.='<h6 class="font-weight-medium">Скидка</h6>';
            $html.='<h6 class="font-weight-medium">'.$generalDiscount.' руб</h6>';
            $html.=' </div>';
            $html.='<div class="card-footer border-secondary bg-transparent">';
            $html.='<div class="d-flex justify-content-between mt-2">';
            $html.='<h5 class="font-weight-bold">Итог</h5>';
            $html.='<h5 class="font-weight-bold">'.$sum-$generalDiscount.'руб</h5>';
            $html.='</div>';
            $html.=' </div>';

        } catch (Exception $e) {

        }
        // Отправляем данные о корзине в формате JSON
        echo $html;

    } else {
        // Если userId не передан, возвращаем ошибку
        echo json_encode(['error' => 'User ID is missing']);
    }
}

