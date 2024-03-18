document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для кнопки минус
    $(document).on('click', '.btn-minus', function() {
        const inputElement = $(this).closest('.input-group').find('input');
        let count = parseInt(inputElement.val());
        if (count > 1) {
            inputElement.val(count);
        }
    });

    // Обработчик для кнопки плюс
    $(document).on('click', '.btn-plus', function() {
        console.log(document.getElementById("input-count").value)


    });



    // Обработчик для кнопки "Add To Cart"
    $(document).on('click', '.add-to-card', function(e) {
        e.preventDefault(); // Предотвращаем стандартное действие ссылки
        const productId = $(this).data('product-id');
        const inputElement = $(".input-count")

        let count = document.getElementById("input-count").value;

        if (count) {
            console.log(productId);
            console.log(count);
            updateCart(productId, count);
        } else {
            console.error('Invalid quantity value:', inputElement.val());
        }
    });


});

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
