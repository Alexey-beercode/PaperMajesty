<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true) {
    // Если пользователь не авторизован, перенаправляем его на страницу входа
    header('Location: authorization.php');
    exit;
}
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'ru'; // Инициализация только если не установлена
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PaperMajesty/Cart</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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
                <h1 class="m-0 display-5 font-weight-semi-bold">PaperMajesty</h1>
            </a>
        </div>
        <div class="col-lg-6 col-6 text-left">
            <form id="searchForm">
                <div class="input-group">
                    <input id="searchInput" type="text" class="form-control" placeholder="Поиск">
                    <div class="input-group-append" id="searchButton">
                <span class="input-group-text bg-transparent text-primary">
                    <i class="fa fa-search fa-lg"></i>
                </span>
                    </div>
                </div>
            </form>
        </div>


        <div class="col-lg-3 col-6 text-right">
            <a href="promotions.php"> <button type="button" class="btn btn-primary " data-translate="promotions">Акции</button></a>
            <a href="personalPage.php" class="btn border">
                <i class="fas fa-user fa-lg"></i>
            </a>
            <a href="cart.php" class="btn border">
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
            <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 1;">
                <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                    <?php include_once 'getCategories.php'?>
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
            <a href="index.php"><button  type="button"style="border-radius: 12px" class="btn btn-primary" data-translate="back">Назад в каталог</button></a>
        </div>
    </div>
</div>
<!-- Navbar End -->
<!-- Cart Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table id="cartTable" class="table table-bordered text-center mb-0">
                <thead class="bg-secondary text-dark">
                <tr>
                    <th data-translate="product">Товар</th>
                    <th data-translate="price">Цена</th>
                    <th data-translate="count">Количество</th>
                    <th data-translate="score">Итоговая стоимость</th>
                    <th data-translate="formDelete">Удалить</th>
                </tr>
                </thead>
                <tbody class="align-middle">
                <?php
                include_once 'getCart.php';
                getCartByUserid();
                ?>
                </tbody>
            </table>
        </div>
        <div class="col-lg-4">
            <?php
            if ($_SESSION['coupon_code']!='' && $_SESSION['coupon_code']!="Unactive")
                {
                    echo '<h5 style="color: #28a745" data-translate="appliedCoupon">Купон применен</h5>';
                }

            elseif ($_SESSION['coupon_code']=="Unactive")
            {
                echo '<h5 style="color: red" data-translate="expireCoupon">Купон истек</h5>';
            }
            ?>
            <form class="mb-5" action="applyCoupon.php" method="post">
                <div class="input-group">
                    <input type="text" class="form-control p-4" name="coupon_code" placeholder="Ввести код купона">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" data-translate="applyCoupon">Применить купон</button>
                    </div>
                </div>
            </form>

            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0" data-translate="orderPrice">Цена заказа</h4>
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold" data-translate="sum">Сумма</h5>
                        <h5 id="total-price" class="font-weight-bold"></h5>
                    </div>
                    <?php
                    try {
                        checkIsGoodQuantity();
                        echo '<a href="order.php"><button class="btn btn-block btn-primary my-3 py-3" data-translate="sendOrder">Отправить заказ</button></a>';
                    }
                    catch (Exception $exception){
                        echo '<h5 style="color: red">'.$exception->getMessage().'</h5>';
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cart End -->

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


<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>


<!-- Template Javascript -->
<script src="js/cart.js"></script>
<script src="js/site.js"></script>
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
                    console.log("Язык успешно изменен на <?php echo $_SESSION['language']?>");
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
