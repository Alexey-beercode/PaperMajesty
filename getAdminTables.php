<?php
include_once 'config/db_connection.php';
require_once 'repositories/CouponRepository.php';
require_once 'repositories/OrderProductRepository.php';
require_once 'repositories/OrderStatusRepository.php';
require_once 'services/ProductService.php';
require_once 'repositories/ProductRepository.php';
require_once 'repositories/OrderRepository.php';
require_once 'repositories/ProductCategoryRepository.php';
require_once 'repositories/UserRepository.php';
require_once 'repositories/RoleRepository.php';
require_once 'repositories/UserRoleRepository.php';
require_once 'services/UserService.php';
require_once 'services/OrderService.php';
require_once 'repositories/PromotionDiscountRepository.php';
require_once 'repositories/PromotionRepository.php';
require_once 'services/PromotionService.php';
require_once 'repositories/CouponDiscountRepository.php';
require_once 'services/CouponService.php';
include_once 'getOrderHistory.php';

use repositories\CouponDiscountRepository;
use repositories\CouponRepository;
use repositories\OrderProductRepository;
use repositories\OrderRepository;
use repositories\OrderStatusRepository;
use repositories\ProductCategoryRepository;
use repositories\PromotionDiscountRepository;
use repositories\PromotionRepository;
use repositories\RoleRepository;
use repositories\UserRepository;
use repositories\UserRoleRepository;
use services\CouponService;
use services\ProductService;
use repositories\ProductRepository;
use services\OrderService;
use services\PromotionService;
use services\UserService;

function getCouponService($conn)
{
    $couponRepository=new CouponRepository($conn);
    $couponDiscountRepository=new CouponDiscountRepository($conn);
    $productRepository=new ProductRepository($conn);
    return new CouponService($couponRepository,$couponDiscountRepository,$productRepository);
}
function renderOrdersTableHeader()
{
    $html='<div class="container-fluid pt-5">';
    $html.='<div class="row px-xl-5">';
    $html.='<div class="col-lg-12 table-responsive mb-5">';
    $html.='<table id="cartTable" class="table table-bordered text-center mb-0">';
    $html.='<thead class="bg-secondary text-dark">';
    $html.='<tr>';
    $html.='<th>Номер заказа</th>';
    $html.='<th>Дата заказа</th>';
    $html.='<th>Имя</th>';
    $html.='<th>Адрес</th>';
    $html.='<th>Товары</th>';
    $html.='<th>Итоговая стоимость</th>';
    $html.='<th>Статус</th>';
    return $html;
}
function getPromotionService()
{
    global $conn;
    $productRepository=new ProductRepository($conn);
    $promotionRepository=new PromotionRepository($conn);
    $promotionDiscountRepository=new PromotionDiscountRepository($conn);
    return new PromotionService($promotionRepository,$promotionDiscountRepository,$productRepository);
}
function renderUsersTableHeader()
{
    $html='<div class="container-fluid pt-5">';
    $html.='<div class="row px-xl-5">';
    $html.='<div class="col-lg-12 table-responsive mb-5">';
    $html.='<table id="cartTable" class="table table-bordered text-center mb-0">';
    $html.='<thead class="bg-secondary text-dark">';
    $html.='<tr>';
    $html.='<th>Имя</th>';
    $html.='<th>Роль</th>';
    $html.='<th>Логин</th>';
    $html.='<th>Email</th>';
    $html.='<th>Количество заказов</th>';
    $html.='</tr>';
    $html.='</thead>';
    $html.='<tbody>';
    return $html;
}
function renderTableFooter()
{
    $html='';
    $html.='</tr>';
    $html.='</thead>';
    $html.='<tbody class="align-middle">';
    $html.='</tbody>';
    $html.='</table>';
    $html.='</div>';
    $html.='</div>';
    $html.='</div>';
    return $html;
}
function renderUserTable()
{
    $userService=getUserService();
    $orderService=getOrderService();
    $users=$userService->getAllUsers();
    $output='';
    $output.=renderUsersTableHeader();
    foreach ($users as $user)
    {
        $countOfOrdersByUser=count($orderService->getAllOrdersByUserId($user['id']));
        $roles=$userService->getRolesByUserId($user['id']);
        $output.='<tr>';
        $output.='<td>'.$user['name'].'</td>';
        foreach ($roles as $role)
        {
            $output.='<td>'.$role['name'].'</td>';
        }

        $output.='<td>'.$user['login'].'</td>';
        $output.='<td>'.$user['email'].'</td>';
        $output.='<td>'.$countOfOrdersByUser.'</td>';
        $output.='</tr>';
    }
    $output.='</tbody>';
    $output.=renderTableFooter();
    return $output;
}
function renderProductTableHeader()
{
    $html='<div class="container-fluid pt-5">';
    $html.='<div class="row px-xl-5">';
    $html.='<div class="col-lg-12 table-responsive mb-5">';
    $html.='<table id="cartTable" class="table table-bordered text-center mb-0">';
    $html.='<thead class="bg-secondary text-dark">';
    $html.='<tr>';
    $html.='<th>Изображение</th>';
    $html.='<th>Название</th>';
    $html.='<th>Категория</th>';
    $html.='<th>Описание</th>';
    $html.='<th>Новая цена</th>';
    $html.='<th>Цена</th>';
    $html.='<th>Количество на складе</th>';
    $html.='</tr>';
    $html.='</thead>';
    $html.='<tbody>';
    return $html;
}
function renderProductTable()
{
    $html='';
    $html.=renderProductTableHeader();
    global $conn;
    $productCategoryRepository=new ProductCategoryRepository($conn);
    $productService=getProductService();
    $products=$productService->getAll();
    foreach ($products as $product)
    {
        $categoryName=$productCategoryRepository->find($product['categoryId'])['name'];
        $html.='<tr>';
        $html .= '<td><img src="' . $product['imageUrl'] . '" style="width: 50px;"></td>>';
        $html.='<td>'.$product['name'].'</td>';
        $html.='<td>'.$categoryName.'</td>';
        $html.='<td>'.$product['description'].'</td>';
        $html.='<td>'.$product['new_price'].'</td>';
        $html.='<td>'.$product['price'].'</td>';
        $html.='<td>'.$product['stockQuantity'].'</td>';
        $html.='</tr>';
    }
    $html.=renderTableFooter();
    $html.='<a data-action="addProduct"><button class="btn btn-block btn-primary my-3 py-3">Добавить товар</button></a>';
    return $html;
}

function renderPromotionTableHeader()
{
    $html='<div class="container-fluid pt-5">';
    $html.='<div class="row px-xl-5">';
    $html.='<div class="col-lg-12 table-responsive mb-5">';
    $html.='<table id="cartTable" class="table table-bordered text-center mb-0">';
    $html.='<thead class="bg-secondary text-dark">';
    $html.='<tr>';
    $html.='<th>Изображение</th>';
    $html.='<th>Название</th>';
    $html.='<th>Дата начала</th>';
    $html.='<th>Дата окончания</th>';
    $html.='<th>Завершена</th>';
    $html.='<th>Товары в акции</th>';
    $html.='</tr>';
    $html.='</thead>';
    $html.='<tbody>';
    return $html;
}
function renderCouponTableHeader()
{
    $html='<div class="container-fluid pt-5">';
    $html.='<div class="row px-xl-5">';
    $html.='<div class="col-lg-12 table-responsive mb-5">';
    $html.='<table id="cartTable" class="table table-bordered text-center mb-0">';
    $html.='<thead class="bg-secondary text-dark">';
    $html.='<tr>';
    $html.='<th>Название</th>';
    $html.='<th>Дата окончания</th>';
    $html.='<th>Код</th>';
    $html.='<th>Активен</th>';
    $html.='<th>Товары в акции</th>';
    $html.='</tr>';
    $html.='</thead>';
    $html.='<tbody>';
    return $html;
}
function renderPromotionTable()
{
    global $conn;
    $html='';
    $now = date('Y-m-d H:i:s');
    $html.=renderPromotionTableHeader();
    $promotionService=getPromotionService();
    $promotions=$promotionService->getAll();
    foreach ($promotions as $promotion)
    {
        $isPromotionActive=$promotion['endDate']>$now;
        $productsIdsInPromotion=$promotionService->getAllProductsInPromotion($promotion['id']);
        $html.='<tr>';
        $html.='<td><img src="' . $promotion['image'] . '" style="width: 50px;"></td>>';
        $html.='<td>'.$promotion['name'].'</td>';
        $html.='<td>'.$promotion['startDate'].'</td>';
        $html.='<td>'.$promotion['endDate'].'</td>';
        if ($isPromotionActive==true) {
            $html.='<td>Не завершена</td>';
        }
        else{
            $html.='<td>Завершена</td>';
        }
        $html.='<td class="align-middle">';
        foreach ($productsIdsInPromotion as $product) {
            $html .= '<img src="' . $product['imageUrl'] . '" alt="" style="width: 50px;">' . $product['name'] . '<br>';

        }
        $html.='</td>';
    }
    $html.=renderTableFooter();
    $html.='<a data-action="addAction"><button class="btn btn-block btn-primary my-3 py-3">Добавить акцию</button></a>';
    return $html;

}
function renderCouponTable()
{
    global $conn;
    $html='';
    $now = date('Y-m-d H:i:s');
    $html.=renderCouponTableHeader();
    $couponService=getCouponService($conn);
    $coupons=$couponService->getAll();
    $productService=getProductService($conn);
    foreach ($coupons as $coupon)
    {
        $isCouponActive=$coupon['expireTime']>$now;
        $productsAndDiscountsByCouponId=$couponService->getProductsAndDiscountsByCouponId($coupon['id']);
        $html.='<tr>';
        $html.='<td>'.$coupon['name'].'</td>';
        $html.='<td>'.$coupon['expireTime'].'</td>';
        $html.='<td>'.$coupon['code'].'</td>';
        if ($isCouponActive==true) {
            $html.='<td>Активен</td>';
        }
        else{
            $html.='<td>Не активен</td>';
        }
        $html.='<td class="align-middle">';
        foreach ($productsAndDiscountsByCouponId as $value) {
            $product=$productService->getById($value['productId']);
            $html .= '<img src="' . $product['imageUrl'] . '" alt="" style="width: 50px;">' . $product['name'] . '<br>';

        }
        $html.='</td>';
    }
    $html.=renderTableFooter();
    $html.='<a data-action="addCoupon"><button class="btn btn-block btn-primary my-3 py-3">Добавить купон</button></a>';
    return $html;

}
function renderOrderTable()
{
    $html='';
    $html.=renderOrdersTableHeader();
    $html.=getOrderHistoryByUserid(null);
    $html.=renderTableFooter();
    return $html;
}
?>

