<?php
namespace repositories;
use PDO;
require 'C:\Users\Алексей\vendor\autoload.php';
use Ramsey\Uuid\Uuid;


class UserRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create($login,$password,$name,$email)
    {
        // Generate a new UUID
        $uuid = Uuid::uuid4()->toString();

        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (id, login, name, email, passwordHash) VALUES (:id, :login, :name, :email, :passwordHash)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $uuid,
            ':login' => $login,
            ':name' => $name, // Assuming name is added to the user data
            ':email' => $email,
            ':passwordHash' => $hashedPassword,
        ]);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($user)
    {
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
        // Assuming password update is handled separately (if needed)
        $sql = "UPDATE users SET passwordHash = :$hashedPassword, email = :email, name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $user['id'],
            ':hashedPassword' => $hashedPassword,
            ':email' => $user['email'],
            ':name' => $user['name'],
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM users";
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
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    public function getUserByUsername($username) {
        // Предполагается, что у вас есть таблица "users" с полями "id", "username", "password", "fullname", "email"
        $query = "SELECT * FROM users WHERE name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
