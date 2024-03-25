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

    public function create($expireTime, $name)
    {
        // Generate a shorter and simpler coupon code
        $couponCode = $this->generateCouponCode();

        $sql = "INSERT INTO coupons (id, code, expireTime, name) 
                VALUES (:id, :code, :expireTime, :name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => Uuid::uuid4()->toString(),
            ':code' => $couponCode,
            ':expireTime' => $expireTime,
            ':name' => $name,
        ]);

        return $couponCode;
    }

    private function generateCouponCode()
    {
        // Generate a random alphanumeric code of length 6
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
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
    public function findByCode($code)
    {
        $sql = "SELECT * FROM coupons WHERE code = :code";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':code' => $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
