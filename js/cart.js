// Функция для отправки POST запроса на изменение корзины
function updateCart(userId, productId, count) {
    $.ajax({
        url: 'updateCart.php',
        type: 'POST',
        data: {
            userId: userId,
            productId: productId,
            count: count
        },
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                console.error('Error:', response.error);
            } else {
                // Если изменения прошли успешно, обновляем содержимое корзины
                getCart(userId);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

// Пример использования функции для удаления товара из корзины
$(document).on('click', '.btn-remove', function() {
    var userId = '123'; // Замените на актуальный userId
    var productId = $(this).data('product-id');
    var count = -1; // Отрицательное значение для удаления товара
    updateCart(userId, productId, count);
});
// Функция для получения содержимого корзины и вывода товаров в таблицу
function calculateTotalPrice() {
    let total = 0;
    // Find all elements with class 'align-middle' inside table body
    const totalPriceCells = document.querySelectorAll('tbody.align-middle td.align-middle');
    totalPriceCells.forEach(cell => {
        const priceText = cell.textContent.trim();
        // Check if content is a number
        if (!isNaN(priceText)) {
            total += parseFloat(priceText);
        }
    });
    // Update the element with ID 'total-price'
    document.getElementById('total-price').textContent = `$${total.toFixed(2)}`; // Format to two decimal places
}

// Вызываем функцию calculateTotalPrice() при загрузке страницы и после изменений в таблице
document.addEventListener('DOMContentLoaded', calculateTotalPrice);

