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

    public function create($expireTime, $name,$couponCode)
    {

        $sql = "INSERT INTO coupons (id, code, expireTime, name,isDeleted) 
                VALUES (:id, :code, :expireTime, :name,:isDeleted)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => Uuid::uuid4()->toString(),
            ':code' => $couponCode,
            ':expireTime' => $expireTime,
            ':name' => $name,
            ':isDeleted'=> 0 // Передаем 0 вместо false
        ]);

        return $couponCode;
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
        $sql = "SELECT * FROM coupons WHERE (isDeleted = false OR isDeleted IS NULL)";
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
        $sql = "UPDATE coupons 
                SET isDeleted=true WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    public function findByCode($code)
    {
        $sql = "SELECT * FROM coupons WHERE code = :code AND (isDeleted = false OR isDeleted IS NULL)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':code' => $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
