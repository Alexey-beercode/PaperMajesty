<?php
include_once 'getDetails.php';

// Получаем данные о товаре
$productData = getProductDetails($_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PaperMajesty/<?php echo $productData['name']?></title>
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
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<body>
<!-- Topbar Start -->
<div class="container-fluid">
    <div class="row align-items-center py-3 px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a href="index.php" class="text-decoration-none">
                <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1"></span>PaperMajesty</h1>
            </a>
        </div>
        <div class="col-lg-6 col-6 text-left">
            <form id="searchForm">
                <div class="input-group">
                    <input id="searchInput" type="text" class="form-control" placeholder="Поиск">
                    <div class="input-group-append" id="searchButton">
                <span class="input-group-text bg-transparent text-primary">
                    <i class="fa fa-search"></i>
                </span>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-3 col-6 text-right">
            <button type="button" class="btn btn-primary">Акции</button>
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
                <h6 class="m-0">Categories</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 1;">
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
            <a href="index.php"><button  type="button"style="border-radius: 12px" class="btn btn-primary">Назад в каталог</button></a>
        </div>
    </div>
</div>


<!-- Shop Detail Start -->
<div class="container-fluid py-5">
    <div class="row px-xl-5">
        <div class="col-lg-5 pb-5">
            <div id="product-carousel" class="carousel slide" data-ride="carousel">

                <div class="carousel-inner border">
                    <?php
                    // Включаем getDetails.php для получения данных о товаре

                    echo '<img class="w-100 h-100" src="' . $productData['imageUrl'] . '" alt="Image">';
                    ?>
                </div>
            </div>
        </div>

        <div class="col-lg-7 pb-5">
            <?php
            // Проверяем, получены ли данные о товаре
            if ($productData !== null) {
                // Выводим название товара и его цену
                echo '<h3 class="font-weight-semi-bold ">' . $productData['name'] . '</h3>';
                if ($productData['new_price'] !== null) {
                    echo '<del>' . $productData['price'] . '</del>';
                    echo '<h3 class="font-weight-semi-bold ">' . $productData['new_price'] . 'руб  ';
                }

                else{
                    echo '<h3 class="font-weight-semi-bold ">Price: $' . $productData['price'];
                }
            }
            ?>
            <h5>Описание</h5><br>
            <?php

            echo '<p class="mb-4">' . $productData['description'] . '</p>';
            ?>
        </div>
        <?php
        if ($productData['stockQuantity']>0)
        {
            echo getAddToCartDiv($productData['id']);
        }
        ?>

    </div>
</div>
<!-- Shop Detail End -->

<!-- Shop Detail End -->

<!-- Footer Start -->
<div class="container-fluid bg-secondary text-dark mt-5 pt-5">
    <div class="row px-xl-5 pt-5">
        <div class="col-lg-8 col-md-12">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <h5 class="font-weight-bold text-dark mb-4">PaperMajesty</h5>
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

<script src="js/main.js"></script>
<script src="js/details.js"></script>
<script src="js/site.js"></script>
</body>

</html>