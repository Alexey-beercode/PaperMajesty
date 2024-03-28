
    // Функция для загрузки товаров по категории через AJAX
    function loadProductsByCategory(categoryId) {
        $.ajax({
            url: 'getProductsByCategory.php',
            type: 'GET',
            data: {categoryId: categoryId},
            success: function(response) {
                // При успешном получении ответа, обновляем содержимое контейнера с товарами
                console.log(response);
                $('#productContainer').html(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
    $(document).on('click', '.nav-item.nav-link', function(e) {
        e.preventDefault(); // Предотвращаем переход по ссылке
        var categoryId = $(this).attr('data-category-id'); // Получаем ID категории
        console.log(categoryId)
        loadProductsByCategory(categoryId); // Загружаем товары по категории
    });

        function searchProducts(searchTerm) {
            $.ajax({
                url: 'searchProducts.php',
                type: 'POST',
                data: { searchTerm: searchTerm }, // Передаем поисковый запрос на сервер
                success: function(data) {
                    // Очищаем результаты поиска
                    $('#searchResults').empty();
                    console.log(data);
                    // Выводим результаты поиска
                    console.log("ok")
                    $('#productContainer').html(data);
                },
                error: function(xhr, status, error) {
                    // В случае ошибки выводим сообщение об ошибке
                    console.error(error);
                }
            });
        }
    $(document).ready(function() {
        // Обработчик клика на иконке поиска
        $('#searchButton').click(function() {
            // Получаем значение из поля ввода
            var searchTerm = $('#searchInput').val();
            // Делаем AJAX запрос на сервер
            searchProducts(searchTerm);

        });

        // Обработчик события отправки формы (предотвращаем действие по умолчанию)
        $('#searchForm').submit(function(event) {
            event.preventDefault();
        });
    });
