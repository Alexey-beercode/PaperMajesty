<?php
namespace repositories;
require 'C:\Users\Алексей\vendor\autoload.php';
use PDO;
use Ramsey\Uuid\Uuid;

class PromotionDiscountRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($productId, $discount, $promotionId)
    {
        // Generate a new UUID for the promotion discount
        $promotionDiscountId = Uuid::uuid4()->toString();

        $sql = "INSERT INTO promotions_discounts (id, productId, discount, promotionId) 
                VALUES (:id, :productId, :discount, :promotionId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $promotionDiscountId,
            ':productId' => $productId,
            ':discount' => $discount,
            ':promotionId' => $promotionId,
        ]);
    }

    public function findByProductId($productId)
    {
        $sql = "SELECT * FROM promotions_discounts WHERE productId = :productId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':productId' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByPromotionId($promotionId)
    {
        $sql = "SELECT * FROM promotions_discounts WHERE promotionId = :promotionId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':promotionId' => $promotionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM promotions_discounts WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
