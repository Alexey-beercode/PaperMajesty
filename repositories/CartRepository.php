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
    // Получить все товары в корзине пользователя
    public function getCartItemsByUserId($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM carts WHERE userId = ?");
        $stmt->bindValue(1, $userId, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function addToCart($userId, $productId, $count)
    {
        // Проверяем, есть ли уже такой товар в корзине пользователя
        $stmt = $this->conn->prepare("SELECT * FROM carts WHERE userId = ? AND productId = ?");
        $stmt->bindValue(1, $userId, PDO::PARAM_STR);
        $stmt->bindValue(2, $productId, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingItem = $result->fetch_assoc();

        if ($existingItem) {
            // Если товар уже есть в корзине, увеличиваем количество
            $newCount = $existingItem['count'] + $count;
            if ($newCount<=0)
            {
                $this->delete($productId,$userId);
            }
            else{
                $stmt = $this->conn->prepare("UPDATE carts SET count = ? WHERE userId = ? AND productId = ?");
                $stmt->bindValue(1, $newCount, PDO::PARAM_INT);
                $stmt->bindValue(2, $userId, PDO::PARAM_STR);
                $stmt->bindValue(3, $productId, PDO::PARAM_STR);
                return $stmt->execute();
            }
        } else {
            // Если товара нет в корзине, создаем новую запись
            $stmt = $this->conn->prepare("INSERT INTO carts (id, userId, productId, count) VALUES (UUID(), ?, ?, ?)");
            $stmt->bindValue(1, $userId, PDO::PARAM_STR);
            $stmt->bindValue(2, $productId, PDO::PARAM_STR);
            $stmt->bindValue(3, $count, PDO::PARAM_INT);
            return $stmt->execute();
        }
    }

    // Удалить товар из корзины по идентификатору товара
    public function delete($productId, $userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM carts WHERE productId = ? AND userId = ?");
        $stmt->bindValue(1, $productId, PDO::PARAM_STR);
        $stmt->bindValue(2, $userId, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Очистить корзину пользователя
    public function clearCart($userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM carts WHERE userId = ?");
        $stmt->bindValue(1, $userId, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
?>
