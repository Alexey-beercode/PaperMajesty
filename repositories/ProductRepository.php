<?php

namespace repositories;
require 'C:\Users\Алексей\vendor\autoload.php';
use PDO;
use Ramsey\Uuid\Uuid;

class ProductRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($product)
    {
        $uuid = Uuid::uuid4()->toString();

        $sql = "INSERT INTO products (id, categoryId, price, name, description, imageUrl, new_price, stockQuantity,isDeleted) VALUES (:id, :categoryId, :price, :name, :description, :imageUrl, :new_price, :stockQuantity, :isDeleted, :createCountry)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $uuid,
            ':categoryId' => $product['categoryId'],
            ':price' => $product['price'],
            ':name' => $product['name'],
            ':description' => $product['description'],
            ':imageUrl' => $product['imageUrl'],
            ':new_price' => $product['new_price'],
            ':stockQuantity' => $product['stockQuantity'],
            ':isDeleted'=>false,
            ':createCountry'=>$product['createCountry']
        ]);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getByCategoryId($categoryId)
    {
        $sql = "SELECT * FROM products WHERE categoryId = :categoryId AND (isDeleted = false OR isDeleted IS NULL)";


        $stmt=$this->conn->prepare($sql);
        $stmt->execute([':categoryId'=>$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function update($product)
    {
        $sql = "UPDATE products SET categoryId = :categoryId, price = :price, name = :name, description = :description, imageUrl = :imageUrl, new_price = :new_price, stockQuantity = :stockQuantity WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $product['id'],
            ':categoryId' => $product['categoryId'],
            ':price' => $product['price'],
            ':name' => $product['name'],
            ':description' => $product['description'],
            ':imageUrl' => $product['imageUrl'],
            ':new_price' => $product['new_price'],
            ':stockQuantity' => $product['stockQuantity'],
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM products WHERE (isDeleted = false OR isDeleted IS NULL)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "UPDATE products SET isDeleted=true WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    public function searchByName($searchTerm)
    {
        if (empty($searchTerm)) {
            throw new Exception("Search term cannot be empty");
        }

        $sql = "SELECT * FROM products WHERE name LIKE ? AND (isDeleted = false OR isDeleted IS NULL) ";
        $stmt = $this->conn->prepare($sql);

        $searchParam = "%$searchTerm%";
        $stmt->bindValue(1, $searchParam);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getByName($name)
    {
        $sql = "SELECT * FROM products WHERE name = :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
