<?php
namespace services;
require 'C:\Users\Алексей\vendor\autoload.php';
use repositories\OrderRepository;
use repositories\OrderStatusRepository;
use repositories\OrderProductRepository;
use Ramsey\Uuid\Uuid;

class OrderService
{
    private $orderRepository;
    private $orderStatusRepository;
    private $orderProductRepository;

    public function __construct(
        OrderRepository $orderRepository,
        OrderStatusRepository $orderStatusRepository,
        OrderProductRepository $orderProductRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->orderProductRepository = $orderProductRepository;
    }

    public function createOrder($userId, $address, $couponId, array $products)
    {
        $orderId = Uuid::uuid4()->toString();
        $orderNumber = $this->generateOrderNumber();
        $statusName = 'Processing'; // Assuming this is the default status name

        // Get status ID by name
        $status = $this->orderStatusRepository->getStatusByName($statusName);
        $statusId = $status['id'];

        // Create the order
        $this->orderRepository->create($orderId, $orderNumber, $userId, $statusId, $couponId, $address);

        // Add products to order
        foreach ($products as $product) {
            $this->orderProductRepository->create($orderId, $product['productId'], $product['count']);
        }
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
