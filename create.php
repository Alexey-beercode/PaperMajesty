<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PaperMajesty</title>
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
    <!-- start: Css -->
    <link rel="stylesheet" type="text/css" href="css/adminCss/bootstrap.min.css">

    <link href="css/adminCss/style.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- end: Css -->


</head>

<body id="mimin" class="dashboard">
<!--nav bar start -->
<div class="container-fluid mimin-wrapper">
    <div class="container-fluid">
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="index.php" class="text-decoration-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold">PaperMajesty</h1>
                </a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="index.php" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0 font-weight-bold" style="font-size: 1.2em;">Вернуться в каталог</h6>
                </a>
            </div>
        </div>
    </div>
</div>
<!--nav bar end -->
<?php
include_once 'adminServices.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
// Проверяем наличие параметра 'action' в запросе
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action=='addProduct')
    echo renderAddProduct();
    if ($action=='addPromotion')
        echo renderAddPromotion();
    if ($action=='addCoupon')
        echo renderAddCoupon();
}

}
?>
</body>
</html>
