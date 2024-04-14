<?php
session_start();

// Проверяем, установлена ли сессия и есть ли значение у ключа $_SESSION['userId']
if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
} else {
    $userId = '';
}
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'ru'; // Инициализация только если не установлена
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title data-translate="title">PaperMajesty</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<body>
<!-- Topbar Start -->
<div class="container-fluid">
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Language
        </button>
        <div class="dropdown-menu" aria-labelledby="languageDropdown">
            <a class="dropdown-item" onclick="changeLanguage('ru')">Русский</a>
            <a class="dropdown-item"  onclick="changeLanguage('en')">English</a>
            <a class="dropdown-item" onclick="changeLanguage('it')">Italiano</a>
        </div>

    </div>
    <div class="row align-items-center py-3 px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a href="index.php" class="text-decoration-none">
                <h1 class="m-0 display-5 font-weight-semi-bold" data-translate="title">PaperMajesty</h1>
            </a>
        </div>
        <div class="col-lg-6 col-6 text-left">
            <form id="searchForm">
                <div class="input-group">
                    <input id="searchInput" type="text" class="form-control" placeholder data-translate="searchPlaceholder">
                    <div class="input-group-append" id="searchButton">
                <span class="input-group-text bg-transparent text-primary">
                    <i class="fa fa-search"></i>
                </span>
                    </div>
                </div>
            </form>
        </div>


        <div class="col-lg-3 col-6 text-right">
            <a href="promotions.php"> <button type="button" class="btn btn-primary" data-translate="promotionsBtn">Акции</button></a>
            <a href="personalPage.php" class="btn border">
                <i class="fas fa-user fa-lg"></i>
            </a>
            <!-- Используем переменную $userId с проверкой в ссылке -->
            <a href="cart.php?userId=<?php echo $userId; ?>" class="btn border">
                <i class="fas fa-shopping-cart text-primary fa-lg"></i>
            </a>
        </div>
    </div>
</div>
<!-- Topbar End -->


<!-- Navbar Start -->
<div class="container-fluid">
    <div class="row border-top px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                <h6 class="m-0 font-weight-bold" data-translate="categories">Категории</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 100;">
                <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                    <?php include 'getCategories.php'?>
                </div>
            </nav>
        </div>
        <div class="col-lg-9">
            <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                <a href="" class="text-decoration-none d-block d-lg-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1"></span>PaperMajesty</h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </nav>
        </div>
    </div>
</div>
<!-- Navbar End -->

<!-- Shop Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <!-- Shop Sidebar Start -->
        <div class="col-lg-3 col-md-12">
            <!-- Price Start -->
            <div class="border-bottom mb-4 pb-4">
                <h5 class="font-weight-semi-bold mb-4" data-translate="priceLabel">Цена</h5>
                <form id="priceFilterForm">
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" checked id="price-all">
                        <label class="custom-control-label" for="price-all" data-translate="priceAll">Все</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" id="price-1">
                        <label class="custom-control-label" for="price-1" data-translate="priceRange1">0 руб - 5 руб</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" id="price-2">
                        <label class="custom-control-label" for="price-2" data-translate="priceRange2">5 руб - 10 руб</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" id="price-3">
                        <label class="custom-control-label" for="price-3" data-translate="priceRange3">10 руб - 20 руб</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" id="price-4">
                        <label class="custom-control-label" for="price-4" data-translate="priceRange4">20 руб - 30 руб</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between">
                        <input type="checkbox" class="custom-control-input" id="price-5">
                        <label class="custom-control-label" for="price-5" data-translate="priceRange5">30 руб - 40 руб</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between">
                        <input type="checkbox" class="custom-control-input" id="price-6">
                        <label class="custom-control-label" for="price-6" data-translate="priceRange6">40 руб - 50 руб</label>
                    </div>
                </form>
            </div>
            <!-- Price End -->

            <!-- Color Start -->
            <div class="border-bottom mb-4 pb-4">
                <h5 class="font-weight-semi-bold mb-4" data-translate="countryLabel">Страна производства</h5>
                <form id="countryFilterForm">
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" checked id="Беларусь">
                        <label class="custom-control-label" for="Беларусь" data-translate="countryBelarus">Беларусь</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input"  id="Россия">
                        <label class="custom-control-label" for="Россия" data-translate="countryRussia">Россия</label>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" id="Китай">
                        <label class="custom-control-label" for="Китай" data-translate="countryChina">Китай</label>
                    </div>
                </form>
            </div>
            <div class="border-bottom mb-4 pb-4">
                <h5 class="font-weight-semi-bold mb-4" data-translate="availabilityLabel">Наличие</h5>
                <form id="stockFilterForm">
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input" checked id="stock-inStock">
                        <label class="custom-control-label" for="stock-inStock" data-translate="availabilityInStock">В наличии</label>
                        <span class="badge border font-weight-normal"></span>
                    </div>
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input"  id="stock-outOfstock">
                        <label class="custom-control-label" for="stock-outOfstock" data-translate="availabilityOutOfStock">Нет в наличии</label>
                    </div>
                </form>
            </div>
            <a id="applyFilterBtn" class="btn btn-block btn-primary my-3 py-3" data-translate="applyFilterBtn">Применить фильтрацию</a>
        </div>
        <!-- Shop Sidebar End -->


        <!-- Shop Product Start -->
        <div class="col-lg-9 col-md-12">
            <div class="row pb-3">
                <div class="col-12 pb-1">
                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <div class="dropdown ml-4">
                            <button class="btn border dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                <span data-translate="sortLabel">Сортировка</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="triggerId">
                                <a class="dropdown-item sort-item" href="#" data-sort="name" data-translate="sortName">Название</a>
                                <a class="dropdown-item sort-item" href="#" data-sort="price" data-translate="sortPrice">Цена</a>
                                <a class="dropdown-item sort-item" href="#" data-sort="availability" data-translate="sortAvailability">Наличие</a>
                                <a class="dropdown-item sort-item" href="#" data-sort="stock" data-translate="sortStock">Кол-во на складе</a>
                            </div>
                        </div>

                    </div>
                </div>
                <div id="productContainer" class="col-12 d-flex flex-wrap">
                    <?php include 'getProducts.php';
                    getProducts($_SESSION['language']);
                    ?>
                </div>
            </div>
        </div>
        <!-- Shop Product End -->
    </div>
</div>
<!-- Shop End -->


<!-- Footer Start -->
<div class="container-fluid bg-secondary text-dark mt-5 pt-5">
    <div class="row px-xl-5 pt-5">
        <div class="col-lg-8 col-md-12">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <h5 class="font-weight-bold text-dark mb-4" data-translate="title">PaperMajesty</h5>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Footer End -->


<!-- Back to Top -->
<a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


<!-- Template Javascript -->
<script src="js/main.js"></script>
<script src="js/site.js"></script>
<script src="js/cart.js"></script>
    <script>
        let currentLanguage = <?php echo $_SESSION['language']?>; // Default language

        // Function to change the language
        function changeLanguage(lang) {
        const formData = new FormData();
        formData.append('language', lang);

        fetch('set_language.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
        if (response.ok) {
        console.log("Язык успешно изменен");
        changePageLanguage(lang);
    } else {
        console.error('Произошла ошибка при изменении языка');
    }
    })
        .catch(error => {
        console.error('Ошибка при выполнении запроса:', error);
    });

    }
        function changePageLanguage(lang)
        {
            console.log("начало перевода");
            fetch('json/translations.json')
                .then(response => response.json())
                .then(translations => {
                    const translateElements = document.querySelectorAll('[data-translate]');

                    translateElements.forEach(element => {
                        const key = element.dataset.translate;
                        if (translations[lang] && translations[lang][key]) {
                            element.textContent = translations[lang][key];
                        }
                    });
                })
                .catch(error => console.error('Ошибка загрузки переводов:', error));

        }

        // Initial call to set the default language
        $(document).ready(function () {
        changeLanguage(currentLanguage);
    });

</script>
</body>

</html>