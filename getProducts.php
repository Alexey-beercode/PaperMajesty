<?php
require_once 'repositories/ProductRepository.php';
require_once 'repositories/ProductCategoryRepository.php';
require_once 'services/ProductService.php';

use repositories\ProductRepository;
use services\ProductService;

include_once 'config/db_connection.php';



function renderProduct($product) {

    $output = '<div class="col-lg-4 col-md-6 col-sm-12 pb-1">';
    $output .= '<div class="card product-item border-0 mb-4">';
    $output .= '<div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">';
    $output .= '<img height=200 width=400 class=" img-fluid h-100" src="' . $product['imageUrl'] . '" alt="" ' . $product['name'] . '">';
    $output .= '</div>';
    $output .= '<div class="card-body border-left border-right text-center p-0 pt-4 pb-3">';
    $output .= '<h6 class="text-truncate mb-3">' . $product['name'] . '</h6>';
    $output .= '<div class="d-flex justify-content-center">';
    if ($product['stockQuantity'] == 0) {
        $output .= '<p style="color: red;">Товара нет в наличии</p>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="card-footer d-flex justify-content-between bg-light border">';
        $output .= '<a href="details.php?id='.$product["id"].'" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Подробнее</a>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    } else {

        if ($product['new_price'] !== null) {
            $output .= ' <br><del>' . $product['price'] . '</del>';
            $output .= ' ' . $product['new_price'] . 'руб  ';
        }

        else{
            $output .= '<p>' . $product['price'];
            $output.="руб";
        }
        $output .= '</p>';
    }
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="card-footer d-flex justify-content-between bg-light border">';
    $output .= '<a href="details.php?id='.$product["id"].'" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Подробнее</a>';
    $output .= '<a href="#" data-product-id="' . $product['id'] . '" class="btn btn-sm text-dark p-0 add-to-cart-btn"><i class="fas fa-shopping-cart text-primary mr-1"></i>Добавить в корзину</a>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;
}

function getProducts()
{
    global $conn;
    $productRepository=new ProductRepository($conn);
    $productService=new ProductService($productRepository);
    try {
        $products = $productService->getAll();
        $html = '';
        foreach ($products as $product) {
            $html .= renderProduct($product);
        }
        echo $html;
    }
    catch (Exception $exception) {
        echo $exception->getMessage();
    }
}
?>