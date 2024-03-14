
    // Функция для загрузки товаров по категории через AJAX
    function loadProductsByCategory(categoryId) {
    $.ajax({
        url: 'getProductsByCategory.php',
        type: 'GET',
        data: {categoryId: categoryId},
        success: function(response) {
            // При успешном получении ответа, обновляем содержимое контейнера с товарами
            $('#productContainer').html(response);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

    // Обработчик события для клика по категории
    $('.nav-link').click(function(e) {
        e.preventDefault(); // Предотвращаем переход по ссылке
        var categoryId = $(this).attr('data-category-id'); // Получаем ID категории
        loadProductsByCategory(categoryId); // Загружаем товары по категории
    });
    $(document).ready(function() {
        // Обработчик клика на иконке поиска
        $('#searchButton').click(function() {
            // Получаем значение из поля ввода
            var searchTerm = $('#searchInput').val();
            // Делаем AJAX запрос на сервер
            $.ajax({
                url: 'searchProducts.php',
                method: 'POST',
                dataType: 'json',
                data: { searchTerm: searchTerm }, // Передаем поисковый запрос на сервер
                success: function(data) {
                    // Очищаем результаты поиска
                    $('#searchResults').empty();

                    // Выводим результаты поиска
                    if (data.length > 0) {
                        // Если найдены продукты, выводим их
                        $.each(data, function(index, product) {
                            $('#searchResults').append('<div>' + product.name + '</div>');
                        });
                    } else {
                        // Если продукты не найдены, выводим сообщение
                        $('#searchResults').append('<div>No products found</div>');
                    }
                },
                error: function(xhr, status, error) {
                    // В случае ошибки выводим сообщение об ошибке
                    console.error(error);
                }
            });
        });

        // Обработчик события отправки формы (предотвращаем действие по умолчанию)
        $('#searchForm').submit(function(event) {
            event.preventDefault();
        });
    });