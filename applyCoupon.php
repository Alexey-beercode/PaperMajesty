<?php
session_start();
require_once 'repositories/ProductRepository.php';
require_once 'services/ProductService.php';
require_once 'services/CouponService.php';
require_once 'repositories/CouponRepository.php';
require_once 'repositories/CouponDiscountRepository.php';
include_once 'config/db_connection.php';
use repositories\CouponRepository;
use repositories\CouponDiscountRepository;
use repositories\ProductRepository;
use services\CouponService;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, что код купона был отправлен
    if (isset($_POST["coupon_code"])) {
        $coupon_code = $_POST["coupon_code"];
        global $conn;
        $productRepository = new ProductRepository($conn);
        $couponRepository = new CouponRepository($conn);
        $couponDiscountRepository = new CouponDiscountRepository($conn);
        $couponService = new CouponService($couponRepository, $couponDiscountRepository, $productRepository);
        $productsAndDiscounts=$couponService->getProductsAndDiscountsByCouponCode($coupon_code);
        if ($productsAndDiscounts==null)
        {
            $_SESSION['coupon_code']="Unactive";
            header("Location: cart.php");
            exit;
        }
        // Сохраняем код купона в переменной сессии
        $_SESSION["coupon_code"] = $coupon_code;

        // Перенаправляем пользователя обратно на предыдущую страницу или на другую страницу
        // Например, на страницу корзины
        header("Location: cart.php");
        exit;
    } else {
        // Если код купона не был отправлен, можно выполнить какие-то дополнительные действия или вывести сообщение об ошибке
        echo "Код купона не был отправлен";
    }
} else {
    // Если запрос был не методом POST, можно выполнить какие-то дополнительные действия или вывести сообщение об ошибке
    echo "Неверный метод запроса";
}
?>
