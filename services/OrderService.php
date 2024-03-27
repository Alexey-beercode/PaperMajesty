<?php
namespace services;
require 'C:\Users\Алексей\vendor\autoload.php';

use repositories\CouponRepository;
use repositories\OrderRepository;
use repositories\OrderStatusRepository;
use repositories\OrderProductRepository;
use Ramsey\Uuid\Uuid;

class OrderService
{
    private $orderRepository;
    private $orderStatusRepository;
    private $orderProductRepository;
    private $couponRepository;

    public function __construct(
        OrderRepository $orderRepository,
        OrderStatusRepository $orderStatusRepository,
        OrderProductRepository $orderProductRepository,
        CouponRepository $couponRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->couponRepository=$couponRepository;
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

        // Get products for each order
        foreach ($orders as &$order) {
            $order['products'] = $this->orderProductRepository->getAllByOrderId($order['id']);
        }

        return $orders;
    }

    private function generateOrderNumber()
    {
        // Generate order number logic here
        return 'ORD' . strtoupper(Uuid::uuid4()->toString());
    }
}
