$(document).ready(function() {
    // Функция для получения статистики заказов по категориям
    function getOrderStats() {
        console.log("good stat")
        $.ajax({
            url: 'admin.php',
            type: 'GET',
            data: { action: 'getOrderStats' },
            success: function(data) {
                // Обработка полученных данных
                console.log(data);
                // Вывод статистики на страницу
                $('#orderStats').html(data)
            },
            error: function(xhr, status, error) {
                // Обработка ошибок
                console.error(error);
            }
        });
    }
    function loadOrderCount() {
        console.log("good count");
        $.ajax({
            url: 'admin.php',
            type: 'GET',
            data: { action: 'getOrderCount' },
            success: function(response) {
                // При успешном получении ответа, обновляем содержимое <h1> элемента
                console.log(response);
                $('#orderCount h1').text(response); // Вставляем полученные данные в <h1>
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }


    $(document).ready(function() {
        // Обработчик события щелчка на ссылку <a>
        $('a[data-action]').click(function(event) {
            // Отменяем стандартное действие ссылки
            event.preventDefault();

            // Получаем значение атрибута data-action
            var action = $(this).data('action');

            // Отправляем AJAX запрос на сервер
            $.ajax({
                url: 'admin.php', // URL, куда отправляем запрос
                type: 'GET', // Метод запроса
                data: { action: action }, // Данные запроса
                success: function(response) { // Функция, которая будет выполнена при успешном запросе
                    // Вставляем полученный HTML в <div id="content">
                    $('#content').html(response);
                },
                error: function(xhr, status, error) { // Функция, которая будет выполнена в случае ошибки
                    console.error(error); // Выводим ошибку в консоль
                }
            });
        });
    });
    $(document).ready(function() {
        // Обработчик события клика на кнопку
        $(document).on('click', '.btn-delete', function(event) {
            // Отменяем стандартное действие кнопки
            event.preventDefault();

            // Получаем значения атрибутов data-action и data-product-id
            var action = $(this).data('action');
            var productId = $(this).data('product-id');

            // Отправляем POST-запрос на сервер
            $.post('admin.php', { action: action, productId: productId })
                .done(function(response) { // Функция, которая будет выполнена при успешном запросе
                    // Обработка успешного ответа от сервера
                    window.location.href = 'adminIndex.php';
                    console.log(response);
                })
                .fail(function(xhr, status, error) { // Функция, которая будет выполнена в случае ошибки
                    // Обработка ошибки при выполнении запроса
                    console.error(error);
                });

        });
    });






    // Вызываем функцию для получения статистики при загрузке страницы
    getOrderStats();
    loadOrderCount();
});
