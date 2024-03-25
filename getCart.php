<?php
// Подключаем необходимые файлы и классы
require_once 'repositories/CartRepository.php';
require_once 'services/CartService.php';
require_once 'repositories/ProductRepository.php';
require_once 'services/ProductService.php';
require_once 'services/CouponService.php';
require_once 'repositories/CouponRepository.php';
require_once 'repositories/CouponDiscountRepository.php';

use repositories\CartRepository;
use repositories\CouponRepository;
use repositories\CouponDiscountRepository;
use repositories\ProductRepository;
use services\CartService;
use services\ProductService as ProductService;
use services\CouponService;
// Подключаемся к базе данных
include_once 'config/db_connection.php';
// Получаем userId из запроса
function renderProductInCart($product, $count, $productsAndDiscounts)
{
    $output = '';
        $output .= '<tr>';
        if (isset($product['imageUrl'])) {
            $output .= '<td class="align-middle"><img src="' . $product['imageUrl'] . '" alt="" style="width: 50px;">' . $product['name'] . ' </td>';
        } else {
            $output .= '<td class="align-middle">Нет изображения</td>';
        }
        if (isset($product['new_price'])) {
            $price = $product['new_price'];
        } else {
            $price = $product['price'];
        }

        // Проверяем наличие скидки для текущего продукта
        $discount = 0; // По умолчанию скидки нет
        foreach ($productsAndDiscounts as $productAndDiscount) {
            if ($productAndDiscount['productId'] == $product['id']) {
                $discount = $productAndDiscount['discount'];
                break;
            }
        }

        // Если скидка есть, выводим цену с зачеркиванием и цену со скидкой
        if ($discount > 0) {
            $output .= '<td class="align-middle"><del>' . $price . ' руб</del><br> ' . ($price - $discount) . ' руб</td>';
        } else {
            $output .= '<td class="align-middle">' . $price . ' руб</td>';
        }

        $output .= '<td class="align-middle">';
        $output .= ' <div class="input-group quantity mx-auto" style="width: 100px;">';
        $output .= '<div class="input-group-btn">';
        $output .= '<button class="btn btn-sm btn-primary btn-minus" data-product-id="' . $product['id'] . '">';
        $output .= '<i class="fa fa-minus"></i>';
        $output .= ' </button>';
        $output .= ' </div>';
        $output .= '<input type="text" class="form-control form-control-sm bg-secondary text-center" value="' . $count . '">';
        $output .= '<div class="input-group-btn">';
        $output .= '<button class="btn btn-sm btn-primary btn-plus" data-product-id="' . $product['id'] . '">';
        $output .= '<i class="fa fa-plus"></i>';
        $output .= '</button>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</td>';

        // Выводим общую стоимость с учетом количества и скидки
        if ($discount > 0) {
            $output .= '<td class="align-middle" id="product-price">' . ($price - $discount) * $count . ' руб </td>';
        } else {
            $output .= '<td class="align-middle" id="product-price">' . $price * $count . ' руб</td>';
        }

        $output .= '<td class="align-middle"><button data-product-id="' . $product['id'] . '"  class="btn btn-sm btn-primary btn-delete"><i class="fa fa-times"></i></button></td>';
        $output .= '</tr>';
    return $output;
}


function getCartByUserid()
{
    $userId = $_SESSION['userId'];
    if ($userId !== null) {
        // Если запрос метода GET, получаем данные о корзине по userId
        $html = '';
        try {
            global $conn;
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
                $renderProductInCart = renderProductInCart($product, $cartDatum['count'],$productsAndDiscounts);
                $html .= $renderProductInCart;
            }
        } catch (Exception $e) {

        }
        // Отправляем данные о корзине в формате JSON
        echo $html;

    } else {
        // Если userId не передан, возвращаем ошибку
        echo json_encode(['error' => 'User ID is missing']);
    }
}


?>
