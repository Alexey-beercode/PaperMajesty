<?php
namespace repositories;

use Cassandra\Uuid;
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

        $sql = "INSERT INTO user_roles (id, user_id, role_id) VALUES (:id, :user_id, :role_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $uuid,
            ':user_id' => $userId,
            ':role_id' => $roleId,
        ]);
    }

    public function findUsersByRoleId($roleId)
    {
        $sql = "SELECT users.* FROM users JOIN user_roles ON users.id = user_roles.user_id WHERE user_roles.role_id = :role_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':role_id' => $roleId]);

        // Check for errors
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findRolesByUserId($userId)
    {
        $sql = "SELECT roles.* FROM roles JOIN user_roles ON roles.id = user_roles.role_id WHERE user_roles.user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        // Check for errors
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM user_roles WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
