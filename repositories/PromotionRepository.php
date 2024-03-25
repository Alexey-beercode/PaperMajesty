<?php
namespace repositories;
require 'C:\Users\Алексей\vendor\autoload.php';
use PDO;
use Ramsey\Uuid\Uuid;

class PromotionRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($startDate, $endDate, $name)
    {
        // Generate a new UUID for the promotion
        $promotionId = Uuid::uuid4()->toString();

        $sql = "INSERT INTO promotions (id, startDate, endDate, name) 
                VALUES (:id, :startDate, :endDate, :name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $promotionId,
            ':startDate' => $startDate,
            ':endDate' => $endDate,
            ':name' => $name,
        ]);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM promotions WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($promotion)
    {
        $sql = "UPDATE promotions 
                SET startDate = :startDate, endDate = :endDate, name = :name 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $promotion['id'],
            ':startDate' => $promotion['startDate'],
            ':endDate' => $promotion['endDate'],
            ':name' => $promotion['name'],
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM promotions";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // Check for errors
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllActivePromotions()
    {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM promotions WHERE endDate > :now";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':now' => $now]);

        // Check for errors
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM promotions WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
