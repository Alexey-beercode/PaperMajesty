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
    $productRepository=new ProductRepository($conn);
    $productService=new ProductService($productRepository);
    $orderRepository=new OrderRepository($conn);
    $orderStatusRepository=new OrderStatusRepository($conn);
    $productCategoryRepository=new ProductCategoryRepository($conn);
    $orderService=new OrderService($orderRepository,$orderStatusRepository,$orderProductRepository,$couponRepository,$productService,$productCategoryRepository);
    return $orderService;
}
function renderUserTable()
{
    $userService=getUserService();
    $orderService=getOrderService();
    $users=$userService->getAllUsers();
    $output='';
    $output.='<thead>';
    $output.='<tr>';
    $output.='<th>Имя</th>';
    $output.='<th>Роль</th>';
    $output.='<th>Логин</th>';
    $output.='<th>Email</th>';
    $output.='<th>Количество заказов</th>';
    $output.='</tr>';
    $output.='</thead>';
    $output.='<tbody>';
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
    return $output;
}
?>

