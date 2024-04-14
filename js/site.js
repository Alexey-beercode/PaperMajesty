
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
    $(document).ready(function() {
        $('#applyFilterBtn').click(function() {
            console.log("filter");
            var priceFilter = getSelectedOptions('priceFilterForm');
            var countryFilter = getSelectedOptions('countryFilterForm');
            var stockFilter = getSelectedOptions('stockFilterForm');
            console.log(priceFilter );
            console.log(countryFilter );
            console.log(stockFilter);
            // Send filters to server using AJAX POST request
            $.ajax({
                url: 'getProducts.php',
                type: 'POST',
                data: { priceFilter: priceFilter,countryFilter: countryFilter,stockFilter: stockFilter, action:"filter" }, // Передаем поисковый запрос на сервер
                success: function(data) {
                    console.log("ok")
                    $('#productContainer').html(data);
                },
                error: function(xhr, status, error) {
                    // В случае ошибки выводим сообщение об ошибке
                    console.error(error);
                }
            });
        });

        function getSelectedOptions(formId) {
            // Use consistent variable naming (lowercase with underscores)
            const selectedOptions = [];

            // Target checkboxes within the specified form using a more robust selector
            $(`#${formId} input[type="checkbox"]:checked`).each(function() {
                // Extract the relevant part of the ID without hardcoding assumptions
                const optionId = $(this).attr('id').split('-').slice(-1)[0]; // Get the last part of the ID

                // Only add IDs that are valid (avoid empty strings or unnecessary characters)
                if (optionId) {
                    selectedOptions.push(optionId);
                }
            });

            return selectedOptions.join(' '); // Return comma-separated options for better readability
        }
    });

