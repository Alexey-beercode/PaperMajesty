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


    // Вызываем функцию для получения статистики при загрузке страницы
    getOrderStats();
    loadOrderCount();
});
