<?php

namespace repositories;

use PDO;

class OrderStatusRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Метод для получения статуса по его названию
    public function getStatusByName($name)
    {
        $sql = "SELECT * FROM order_statuses WHERE name = :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getStatusById($id)
    {
        $sql = "SELECT * FROM order_statuses WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Метод для получения всех статусов
    public function getAllStatuses()
    {
        $sql = "SELECT * FROM order_statuses";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
