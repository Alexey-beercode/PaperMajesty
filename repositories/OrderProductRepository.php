<?php
namespace repositories;
require 'C:\Users\Алексей\vendor\autoload.php';
use PDO;
use Ramsey\Uuid\Uuid;

class OrderProductRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($orderId, $productId, $count)
    {
        // Generate a new UUID for the order product
        $orderProductId = Uuid::uuid4()->toString();

        $sql = "INSERT INTO orders_products (id, orderId, productId, count) 
                VALUES (:id, :orderId, :productId, :count)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $orderProductId,
            ':orderId' => $orderId,
            ':productId' => $productId,
            ':count' => $count,
        ]);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM orders_products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($orderProduct)
    {
        $sql = "UPDATE orders_products 
                SET orderId = :orderId, productId = :productId, count = :count 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $orderProduct['id'],
            ':orderId' => $orderProduct['orderId'],
            ':productId' => $orderProduct['productId'],
            ':count' => $orderProduct['count'],
        ]);
    }

    public function getAllByOrderId($orderId)
    {
        $sql = "SELECT * FROM orders_products WHERE orderId = :orderId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':orderId' => $orderId]);

        // Check for errors
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM orders_products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
