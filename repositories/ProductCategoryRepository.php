<?php
namespace repositories;

require 'C:\Users\Алексей\vendor\autoload.php';
use Ramsey\Uuid\Uuid;
use PDO;

class ProductCategoryRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($category)
    {
        // Generate a new UUID
        $uuid = Uuid::uuid4()->toString();

        $sql = "INSERT INTO product_categories (id, name) VALUES (:id, :name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $uuid,
            ':name' => $category['name'],
        ]);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM product_categories WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($category)
    {
        $sql = "UPDATE product_categories SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $category['id'],
            ':name' => $category['name'],
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM product_categories";
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
        $sql = "DELETE FROM product_categories WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
