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

    <!-- plugins -->
    <link rel="stylesheet" type="text/css" href="css/adminCss/plugins/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/adminCss/plugins/simple-line-icons.css"/>
    <link rel="stylesheet" type="text/css" href="css/adminCss/plugins/animate.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/adminCss/plugins/fullcalendar.min.css"/>
    <link href="css/adminCss/style.css" rel="stylesheet">
    <!-- end: Css -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
    <!-- start:Left Menu -->
    <div id="left-menu">
        <div class="sub-left-menu scroll">
            <ul class="nav nav-list">
                <li class="ripple">
                    <ul class="nav nav-list tree">
                        <li><a href="topnav.html">Top Navigation</a></li>
                        <li><a href="boxed.html">Boxed</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <!-- start: content -->
    <div id="content">

        <div class="col-md-12" style="padding:20px;">
            <div class="col-md-12 padding-0">
                <div class="col-md-8 padding-0">
                    <div class="col-md-12 padding-0">
                        <div class="col-md-6">
                            <div class="panel box-v1">
                                <div class="panel-heading bg-white border-none">
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-left padding-0">
                                        <h4 class="text-left">Orders</h4>
                                    </div>
                                </div>
                                <div id="orderCount" class="panel-body text-center">
                                    <h1></h1>
                                    <hr/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="col-md-12 padding-0">
                        <div class="panel box-v3">
                            <div class="panel-heading bg-white border-none">
                                <h4>Заказы по категориям</h4>
                            </div>
                            <div id="orderStats" class="panel-body">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: content -->
<script src="js/admin.js"></script>


</body>
</html>
