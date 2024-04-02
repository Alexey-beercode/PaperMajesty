<?php
namespace services;
require 'C:\Users\Алексей\vendor\autoload.php';

use repositories\CouponRepository;
use repositories\OrderRepository;
use repositories\OrderStatusRepository;
use repositories\OrderProductRepository;
use Ramsey\Uuid\Uuid;
use repositories\ProductCategoryRepository;

class OrderService
{
    private $orderRepository;
    private $orderStatusRepository;
    private $orderProductRepository;
    private $couponRepository;
    private $productService;
    private $categoryRepository;

    public function __construct(
        OrderRepository $orderRepository,
        OrderStatusRepository $orderStatusRepository,
        OrderProductRepository $orderProductRepository,
        CouponRepository $couponRepository,
        ProductService $productService,
        ProductCategoryRepository $productCategoryRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->couponRepository=$couponRepository;
        $this->productService=$productService;
        $this->categoryRepository=$productCategoryRepository;
    }

    public function createOrder($userId, $address, $couponCode, array $products,$name)
    {
        $orderId = Uuid::uuid4()->toString();
        $orderNumber = $this->generateOrderNumber();
        $statusName = 'Processing'; // Assuming this is the default status name

        // Get status ID by name
        $status = $this->orderStatusRepository->getStatusByName($statusName);
        $statusId = $status['id'];
        $couponId=$this->couponRepository->findByCode($couponCode)['id'];
        $currentDateTime = date('Y-m-d H:i:s');
        // Create the order
        $this->orderRepository->create($currentDateTime,$orderId, $orderNumber, $userId, $statusId, $couponId, $address,$name);

        // Add products to order
        foreach ($products as $product) {
            $this->orderProductRepository->create($orderId, $product['productId'], $product['count']);
        }
        $order=$this->orderRepository->find($orderId);
        if (isset($order))
        {
            return true;
        }
        return false;

    }
    public function getOrderStats()
    {
        $ordersByCategories = array();
        $ordersAndProducts = $this->orderProductRepository->getAll();
        $orders = $this->orderRepository->getAll();

        if (empty($ordersAndProducts)) {
            return $ordersByCategories;
        }

        $productsInOrders = [];
        foreach ($ordersAndProducts as $order) {
            $productId = $order['productId'];
            $productsInOrders[$productId] = $this->productService->getById($productId);
        }

        // Count the number of orders
        $ordersCount = count($orders);

        // Initialize an array to store category counts
        $categoryCounts = [];

        foreach ($productsInOrders as $productId => $product) {
            $categoryId = $product['categoryId'];

            if (!isset($categoryCounts[$categoryId])) {
                $categoryCounts[$categoryId] = 0;
            }

            $categoryCounts[$categoryId]++;
        }

        // Calculate the percentage of orders per category
        foreach ($categoryCounts as $categoryId => $count) {
            $categoryName=$this->categoryRepository->find($categoryId)['name'];
            $percentage = ($count / $ordersCount) * 100;
            $ordersByCategories[$categoryName] = $percentage;
        }

        return $ordersByCategories;
    }

    public function getAll()
    {
        $orders=$this->orderRepository->getAll();
        if (count($orders)==0)
            return [];
        return $orders;
    }

    public function updateOrderStatus($orderId, $newStatusName)
    {
        // Get order by ID
        $order = $this->orderRepository->find($orderId);

        // Get new status ID by name
        $newStatus = $this->orderStatusRepository->getStatusByName($newStatusName);
        $newStatusId = $newStatus['id'];

        // Update order status
        $this->orderRepository->updateStatus($orderId, $newStatusId);
    }

    public function deleteOrder($orderId)
    {
        // Delete order from order repository
        $this->orderRepository->delete($orderId);

        // Delete associated products from order product repository
        $this->orderProductRepository->deleteByOrderId($orderId);
    }

    public function getAllOrdersByUserId($userId)
    {
        // Get all orders for the user
        $orders = $this->orderRepository->getByUserId($userId);
        if (count($orders)==0)
            return [];
        return $orders;
    }
    public function getProductIdsInOrderByOrderId($orderId)
    {
        $rows = $this->orderProductRepository->getAllByOrderId($orderId);
        $productCounts = [];

        foreach ($rows as $row) {
            // Проверяем, существует ли ключ 'productId' в текущей строке
            if (isset($row['productId'])) {
                // Проверяем, существует ли уже такой productId в массиве
                if (isset($productCounts[$row['productId']])) {
                    // Увеличиваем счетчик для этого продукта
                    $productCounts[$row['productId']]++;
                } else {
                    // Инициализируем счетчик для этого продукта
                    $productCounts[$row['productId']] = 1;
                }
            }
        }

        return $productCounts;
    }

    public function getOrderStatusNameByOrderId($statusId)
    {
        return $this->orderStatusRepository->getStatusById($statusId)['name'];
    }

    private function generateOrderNumber()
    {
        // Генерируем UUID
        $uuid = Uuid::uuid4()->toString();

        // Сокращаем UUID до 8 символов
        $shortUuid = substr($uuid, 0, 8);

        // Возвращаем сокращенный номер заказа
        return 'ORD' . strtoupper($shortUuid);
    }

}
