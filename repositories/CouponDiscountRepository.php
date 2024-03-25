<?php
namespace repositories;

require 'C:\Users\Алексей\vendor\autoload.php';

use PDO;
use Ramsey\Uuid\Uuid;

class CouponDiscountRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($couponId, $productId, $discount)
    {
        // Generate a new UUID for the coupon discount
        $couponDiscountId = Uuid::uuid4()->toString();

        $sql = "INSERT INTO coupons_discounts (id, couponId, productId, discount) 
                VALUES (:id, :couponId, :productId, :discount)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $couponDiscountId,
            ':couponId' => $couponId,
            ':productId' => $productId,
            ':discount' => $discount,
        ]);
    }

    public function findByCouponId($couponId)
    {
        $sql = "SELECT * FROM coupons_discounts WHERE couponId = :couponId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':couponId' => $couponId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByProductId($productId)
    {
        $sql = "SELECT * FROM coupons_discounts WHERE productId = :productId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':productId' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM coupons_discounts WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
