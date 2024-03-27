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