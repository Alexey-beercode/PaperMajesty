<?php
require_once 'repositories/CartRepository.php';
require_once 'services/CartService.php';
require_once 'repositories/ProductRepository.php';
require_once 'services/ProductService.php';
require_once 'services/CouponService.php';
require_once 'repositories/CouponRepository.php';
require_once 'repositories/CouponDiscountRepository.php';
include_once 'config/db_connection.php';
use repositories\CartRepository;
use repositories\CouponRepository;
use repositories\CouponDiscountRepository;
use repositories\ProductRepository;
use services\CartService;
use services\ProductService as ProductService;
use services\CouponService;
function renderProductInOrder($product, $count)
{
    $output = '';
    $output .= '<div class="d-flex justify-content-between">';
    $output.='<p>'.$product['name'].'('.$count.')</p>';
    $output.='<p>'.$product['price'].'руб</p>';
    $output.='</div>';
    return $output;
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
            $cartRepository = new CartRepository($conn);
            $cartService = new CartService($cartRepository);
            $productRepository = new ProductRepository($conn);
            $productService = new ProductService($productRepository);
            $couponRepository = new CouponRepository($conn);
            $couponDiscountRepository = new CouponDiscountRepository($conn);
            $couponService = new CouponService($couponRepository, $couponDiscountRepository, $productRepository);
            $productsAndDiscounts=$couponService->getProductsAndDiscountsByCouponCode($couponCode);
            $cartData = $cartService->getCartByUserId($userId);
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

