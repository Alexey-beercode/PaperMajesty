<?php
namespace repositories;

require 'C:\Users\Алексей\vendor\autoload.php';
use Ramsey\Uuid\Uuid;
use PDO;

class RoleRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($role)
    {
        $uuid = Uuid::uuid4()->toString();

        $sql = "INSERT INTO roles (id, name) VALUES (:id, :name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $uuid,
            ':name' => $role['name'],
        ]);
    }


    public function find($id)
    {
        $sql = "SELECT * FROM roles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($role)
    {
        $sql = "UPDATE roles SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $role['id'],
            ':name' => $role['name'],
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM roles";
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
        $sql = "DELETE FROM roles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
