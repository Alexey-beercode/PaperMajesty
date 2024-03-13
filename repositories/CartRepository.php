<?php
namespace repositories;
require_once 'config/db_connection.php';
use PDO;
use Ramsey\Uuid\Uuid;
class CartRepository
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Получить все товары в корзине пользователя
    public function getCartItemsByUserId($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM carts WHERE userId = ?");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cartItems = array();
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }
        return $cartItems;
    }

    public function addToCart($userId, $productId, $count)
    {
        // Проверяем, есть ли уже такой товар в корзине пользователя
        $stmt = $this->conn->prepare("SELECT * FROM carts WHERE userId = ? AND productId = ?");
        $stmt->bind_param("ss", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingItem = $result->fetch_assoc();

        if ($existingItem) {
            // Если товар уже есть в корзине, увеличиваем количество
            $newCount = $existingItem['count'] + $count;
            $stmt = $this->conn->prepare("UPDATE carts SET count = ? WHERE userId = ? AND productId = ?");
            $stmt->bind_param("iss", $newCount, $userId, $productId);
            return $stmt->execute();
        } else {
            // Если товара нет в корзине, создаем новую запись
            $stmt = $this->conn->prepare("INSERT INTO carts (id, userId, productId, count) VALUES (UUID(), ?, ?, ?)");
            $stmt->bind_param("ssi", $userId, $productId, $count);
            return $stmt->execute();
        }
    }

    // Удалить товар из корзины по идентификатору товара
    public function delete($productId, $userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM carts WHERE productId = ? AND userId = ?");
        $stmt->bind_param("ss", $productId, $userId);
        return $stmt->execute();
    }

    // Очистить корзину пользователя
    public function clearCart($userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM carts WHERE userId = ?");
        $stmt->bind_param("s", $userId);
        return $stmt->execute();
    }
}

?>
