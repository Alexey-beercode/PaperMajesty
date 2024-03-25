<?php

namespace repositories;
require 'C:\Users\Алексей\vendor\autoload.php';
use PDO;
use Ramsey\Uuid\Uuid;

class OrderRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($date, $number, $userId, $statusId, $couponId = null, $address = null)
    {
        // Generate a new UUID for the order
        $orderId = Uuid::uuid4()->toString();

        $sql = "INSERT INTO orders (id, date, number, userId, statusId, couponId, address) 
                VALUES (:id, :date, :number, :userId, :statusId, :couponId, :address)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $orderId,
            ':date' => $date,
            ':number' => $number,
            ':userId' => $userId,
            ':statusId' => $statusId,
            ':couponId' => $couponId,
            ':address' => $address,
        ]);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($order)
    {
        $sql = "UPDATE orders 
                SET date = :date, number = :number, userId = :userId, statusId = :statusId, 
                    couponId = :couponId, address = :address 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $order['id'],
            ':date' => $order['date'],
            ':number' => $order['number'],
            ':userId' => $order['userId'],
            ':statusId' => $order['statusId'],
            ':couponId' => $order['couponId'],
            ':address' => $order['address'],
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM orders";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // Check for errors
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT * FROM orders WHERE userId = :userId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userId' => $userId]);

        // Check for errors
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($orderId, $newStatusId)
    {
        $sql = "UPDATE orders SET statusId = :statusId WHERE id = :orderId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':statusId' => $newStatusId,
            ':orderId' => $orderId,
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
