<?php
namespace repositories;
require 'C:\Users\Алексей\vendor\autoload.php';
use PDO;
use Ramsey\Uuid\Uuid;

class CouponRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($code, $expireTime, $name)
    {
        // Generate a new UUID for the coupon
        $couponId = Uuid::uuid4()->toString();

        $sql = "INSERT INTO coupons (id, code, expireTime, name) 
                VALUES (:id, :code, :expireTime, :name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $couponId,
            ':code' => $code,
            ':expireTime' => $expireTime,
            ':name' => $name,
        ]);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM coupons WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($coupon)
    {
        $sql = "UPDATE coupons 
                SET code = :code, expireTime = :expireTime, name = :name 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $coupon['id'],
            ':code' => $coupon['code'],
            ':expireTime' => $coupon['expireTime'],
            ':name' => $coupon['name'],
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM coupons";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // Check for errors
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM coupons WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
