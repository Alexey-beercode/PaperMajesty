<?php
namespace repositories;

require 'C:\Users\Алексей\vendor\autoload.php';
use Ramsey\Uuid\Uuid;
use PDO;

class UserRoleRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($userId, $roleId)
    {
        // Generate a new UUID
        $uuid = Uuid::uuid4()->toString();

        $sql = "INSERT INTO users_roles (id, userId, roleId) VALUES (:id, :userId, :roleId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $uuid,
            ':userId' => $userId,
            ':roleId' => $roleId,
        ]);
    }

    public function findUsersByRoleId($roleId)
    {
        $sql = "SELECT users.* FROM users JOIN users_roles ON users.id = users_roles.user_id WHERE users_roles.role_id = :roleId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':roleId' => $roleId]);

        // Check for errors
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findRolesByUserId($userId)
    {
        $sql = "SELECT roles.* FROM roles JOIN users_roles ON roles.id = users_roles.roleId WHERE users_roles.userId = :userId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userId' => $userId]);

        // Check for errors
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM users_roles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
