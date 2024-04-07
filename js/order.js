$(document).ready(function() {
    // Обработчик события клика на кнопку "Отправить заказ"
    $('#submitOrderButton').click(function () {
        // Получаем значения полей ввода
        var name = $('#nameInput').val();
        var address = $('#addressInput').val();
        // Отправляем POST-запрос на сервер
        $.post('getOrder.php', {name: name, address: address}, function (data) {

                window.location.href = 'index.php';
        });
    });
});