function updateTotalPrice() {
    let total = 0;
    // Находим все элементы с классом align-middle и идентификатором product-price
    const totalPriceCells = document.querySelectorAll('#product-price');
    totalPriceCells.forEach(cell => {
        const cellContent = cell.textContent.trim();
        // Используем регулярное выражение для извлечения числа из строки
        const priceMatch = cellContent.match(/(\d+(?:\.\d+)?) руб/);
        if (priceMatch) {
            const price = parseFloat(priceMatch[1]);
            total += price;
        } else {
            console.error('Некорректная цена:', cellContent);
        }
    });
    // Обновляем содержимое элемента с id total-price на странице
    document.getElementById('total-price').textContent = `${total} руб`;
}

// Вызываем функцию calculateTotalPrice() при загрузке страницы и после изменений в таблице
document.addEventListener('DOMContentLoaded', function() {
    updateTotalPrice(); // Обновляем итоговую стоимость
});
// Функция для отправки POST запроса на изменение количества товара в корзине
// Функция для отправки POST запроса на изменение количества товара в корзине
function updateCart(productId, count) {
    $.ajax({
        url: 'addToCart.php',
        type: 'POST',
        data: {
            productId: productId,
            count: count
        },
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                console.error('Error in success:', response.error);
            } else {
                // Если изменения прошли успешно, обновляем итоговую стоимость
                console.log("good send");
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

// Используем делегирование событий для кнопки "Add To Cart"
$(document).on('click', '.add-to-cart-btn', function(e) {
    e.preventDefault(); // Предотвращаем стандартное поведение ссылки
    var productId = $(this).data('product-id');
    var count = 1; // Устанавливаем значение count равным 1
    updateCart(productId, count);
});

// Используем делегирование событий для кнопок "+"
$(document).on('click', '.btn-plus', function() {

    var productId = $(this).data('product-id');
    var count = 1; // Положительное значение для добавления товара
    console.log("good plus"+"\t"+productId+"\t"+count);
    updateCart(productId, count);
});

// Используем делегирование событий для кнопок "-"
$(document).on('click', '.btn-minus', function() {
    var productId = $(this).data('product-id');
    var count = -1; // Отрицательное значение для удаления товара
    console.log("good minus"+"\t"+productId+"\t"+count);
    updateCart(productId, count);
});

// Используем делегирование событий для кнопок "Удалить"
$(document).on('click', '.btn-delete', function() {
    console.log("good del");
    var productId = $(this).data('product-id');
    deleteFromCart(productId);
});

// Функция для отправки POST запроса на удаление товара из корзины
function deleteFromCart(productId) {
    $.ajax({
        url: 'deleteFromCart.php',
        type: 'POST',
        data: {
            productId: productId
        },
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                console.error('Error:', response.error);
            } else {
                // Если удаление прошло успешно, обновляем итоговую стоимость
                console.log("good send");
                location.reload();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

// Пример использования функции для удаления товара из корзины по нажатию кнопки "Удалить"

