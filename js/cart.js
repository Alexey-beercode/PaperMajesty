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
function getCart(userId) {
    $.ajax({
        url: 'getCart.php',
        type: 'GET',
        data: { userId: userId },
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                console.error('Error:', response.error);
            } else {
                // Очищаем таблицу перед добавлением новых данных
                $('#cartTable tbody').empty();

                // Выводим товары корзины в таблицу
                $.each(response.products, function(index, product) {
                    var row = $('<tr>');
                    row.append($('<td>').text(product.name));
                    row.append($('<td>').text(product.price));
                    row.append($('<td>').html('<div class="input-group quantity mx-auto" style="width: 100px;">' +
                        '<div class="input-group-btn">' +
                        '<button class="btn btn-sm btn-primary btn-minus">' +
                        '<i class="fa fa-minus"></i>' +
                        '</button>' +
                        '</div>' +
                        '<input type="text" class="form-control form-control-sm bg-secondary text-center" value="' + product.quantity + '">' +
                        '<div class="input-group-btn">' +
                        '<button class="btn btn-sm btn-primary btn-plus">' +
                        '<i class="fa fa-plus"></i>' +
                        '</button>' +
                        '</div>' +
                        '</div>'));
                    row.append($('<td>').text(product.total));
                    row.append($('<td>').html('<button class="btn btn-sm btn-primary"><i class="fa fa-times"></i></button>'));
                    $('#cartTable tbody').append(row);
                });

                // Обновляем общую сумму
                $('#totalAmount').text(response.total);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

// Вызываем функцию для получения содержимого корзины при загрузке страницы
// Получаем userId из URL
var urlParams = new URLSearchParams(window.location.search);
var userId = urlParams.get('userId');

// Вызываем функцию для получения содержимого корзины при загрузке страницы
$(document).ready(function() {
    // Проверяем, есть ли userId в URL
    if (userId) {
        // Если userId есть в URL, вызываем функцию getCart
        getCart(userId);
    } else {
        console.error('User ID is missing in the URL');
    }
});

