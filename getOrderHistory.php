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
require_once 'repositories/PromotionRepository.php';
require_once 'repositories/PromotionDiscountRepository.php';
require_once 'services/PromotionService.php';

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
use services\ProductService;
use repositories\ProductRepository;
use services\OrderService;
use services\PromotionService;
use services\UserService;
use services\CouponService;
function getPromotionService()
{
    global $conn;
    $productRepository=new ProductRepository($conn);
    $promotionRepository=new PromotionRepository($conn);
    $promotionDiscountRepository=new PromotionDiscountRepository($conn);
    return new PromotionService($promotionRepository,$promotionDiscountRepository,$productRepository);
}
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
    $productService=new ProductService($productRepository,getPromotionService());
    return $productService;
}
function renderOrder($order,$isUserOrder)
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
            if ($discountAndProducts!=null)
            {
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
    if ($isUserOrder == false) {
        $output .= '<td>';
        $output .= '<form id="updateOrderForm" action="admin.php" method="post">';
        $output .= '<input type="hidden" name="orderId" value="' . $order['id'] . '">';
        $output .= '<input type="hidden" name="action" value="updateOrderStatus">';
        $output .= '<select id="newStatusSelect" name="newStatus">';
        $output .= '<option value="В обработке">В обработке</option>';
        $output .= '<option value="Отправлен">Отправлен</option>';
        $output .= '<option value="Завершен">Завершен</option>';
        $output .= '</select>';
        $output .= '<button type="submit">Изменить статус</button>';
        $output .= '</form>';
        $output .= '</td>';
    }
    $output .= '</tr>';
    return $output;
}
function getOrderHistoryByUserid($userId)
{
    $html = '';
    $orderService = getOrderService();
    if ($userId == null) {
        $isUserOrder=false;
        $orders = $orderService->getAll();
        if (count($orders)==0){
            echo "Не найдено заказов";
        }
        foreach ($orders as $order) {
            $html .= renderOrder($order,$isUserOrder);
        }
        return $html;
    }
    $isUserOrder=true;
    $orders=$orderService->getAll();
    if (count($orders)==0){
        echo "Не найдено заказов";
    }
    foreach ($orders as $order) {
        $html .= renderOrder($order,$isUserOrder);
    }
    return $html;
}
