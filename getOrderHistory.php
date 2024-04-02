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
require_once 'repositories/CouponDiscountRepository.php';
require_once 'services/CouponService.php';
require_once 'repositories/CouponRepository.php';

use repositories\CouponDiscountRepository;
use repositories\CouponRepository;
use repositories\OrderProductRepository;
use repositories\OrderRepository;
use repositories\OrderStatusRepository;
use repositories\ProductCategoryRepository;
use repositories\RoleRepository;
use repositories\UserRepository;
use repositories\UserRoleRepository;
use services\ProductService;
use repositories\ProductRepository;
use services\OrderService;
use services\UserService;
use services\CouponService;
function getUserService()
{
    global $conn;
    $userRepository=new UserRepository($conn);
    $userRoleRepository=new UserRoleRepository($conn);
    $roleRepository=new RoleRepository($conn);
    $userService=new UserService($userRepository,$roleRepository,$userRoleRepository);
    return $userService;
}
function getOrderService()
{
    global $conn;
    $couponRepository=new CouponRepository($conn);
    $orderProductRepository=new OrderProductRepository($conn);
    $productService=getProductService();
    $orderRepository=new OrderRepository($conn);
    $orderStatusRepository=new OrderStatusRepository($conn);
    $productCategoryRepository=new ProductCategoryRepository($conn);
    $orderService=new OrderService($orderRepository,$orderStatusRepository,$orderProductRepository,$couponRepository,$productService,$productCategoryRepository);
    return $orderService;
}
function getProductService()
{
    global $conn;
    $productRepository=new ProductRepository($conn);
    $productService=new ProductService($productRepository);
    return $productService;
}
function renderOrder($order)
{
    global $conn;
    $orderCost = 0;
    $productService = getProductService();
    $orderService=getOrderService();
    $orderStatusName=$orderService->getOrderStatusNameByOrderId($order['statusId']);
    $productsIdsInOrder=$orderService->getProductIdsInOrderByOrderId($order['id']);
    $output = '';
    $output .= '<tr>';
    $output .= '<td class="align-middle">' . $order['number'] . '</td>';
    $output .= '<td class="align-middle">' . $order['date'] . '</td>';
    $output .= '<td class="align-middle">' . $order['name'] . '</td>';
    $output .= '<td class="align-middle">' . $order['address'] . '</td>';
    $output .= '<td class="align-middle">';
    foreach ($productsIdsInOrder as $productId=>$count) {
        $product = $productService->getById($productId);
        $output .= '<img src="' . $product['imageUrl'] . '" alt="" style="width: 50px;">' . $product['name'] . '('.$count.')<br>';

    }

    $output .= '</td>';
    if (isset($order['couponId'])) {
        global $conn;
        $productRepository = new ProductRepository($conn);
        $couponRepository = new CouponRepository($conn);
        $couponDiscountRepository = new CouponDiscountRepository($conn);
        $couponService = new CouponService($couponRepository, $couponDiscountRepository, $productRepository);
        $discountAndProducts = $couponService->getProductsAndDiscountsByCouponId($order['couponId']);
        foreach ($productsIdsInOrder as $productId=>$count) {
            $isOnSale=false;
            foreach ($discountAndProducts as $discountAndProduct) {
                if ($discountAndProduct['productId'] == $productId) {
                    $isOnSale=true;
                    $product = $productService->getById($discountAndProduct['productId']);
                    if (isset($product['new_price'])) {
                        $orderCost += ($product['new_price'] - $discountAndProduct['discount']) * $count;
                    } else {
                        $orderCost += ($product['price'] - $discountAndProduct['discount']) * $count;
                    }
                }
            }
            if ($isOnSale) continue;
            $product = $productService->getById($productId);
            if (isset($product['new_price'])) {
                $orderCost += $product['new_price'] * $productsIdsInOrder[$productId];
            } else {
                $orderCost += $product['price'] * $productsIdsInOrder[$productId];
            }
        }
    } else {
        foreach ($productsIdsInOrder as $productId=>$count) {
            $product = $productService->getById($productId);
            if (isset($product['new_price'])) {
                $orderCost += $product['new_price']*$count;
            } else {
                $orderCost += $product['price']*$count;
            }
        }
    }
    $output .= '<td class="align-middle">' . $orderCost . ' руб</td>';
    $output .= '<td class="align-middle">' . $orderStatusName . '</td>';
    $output .= '</tr>';
    return $output;
}
function getOrderHistoryByUserid($userId)
{
    $html = '';
    $orderService = getOrderService();
    if ($userId == null) {
        $orders = $orderService->getAll();
        if (count($orders)==0){
            echo "Не найдено заказов";
        }
        foreach ($orders as $order) {
            $html .= renderOrder($order);
        }
        echo $html;
        exit;
    }
    $orders=$orderService->getAll();
    if (count($orders)==0){
        echo "Не найдено заказов";
    }
    foreach ($orders as $order) {
        $html .= renderOrder($order);
    }
    echo $html;
    exit;

}
