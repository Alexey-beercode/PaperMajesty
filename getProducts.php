<?php
require_once 'repositories/ProductRepository.php';
require_once 'repositories/ProductCategoryRepository.php';
require_once 'services/ProductService.php';

use repositories\ProductRepository;
use services\ProductService;

include_once 'config/db_connection.php';
include_once 'getOrderHistory.php';



function renderProduct($product,$language) {

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
        if ($language=="ru") {
            $output .= '<a href="details.php?id=' . $product["id"] . '" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Подробнее</a>';
        }
        if ($language=="en"){
            $output .= '<a href="details.php?id='.$product["id"].'" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Details</a>';
        }
        if ($language=="it"){
            $output .= '<a href="details.php?id='.$product["id"].'" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Più dettagli</a>';
        }
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
    if ($language=="ru")
    {
        $output .= '<a href="details.php?id='.$product["id"].'" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Подробнее</a>';
        $output .= '<a href="#" data-product-id="' . $product['id'] . '" class="btn btn-sm text-dark p-0 add-to-cart-btn"><i class="fas fa-shopping-cart text-primary mr-1"></i>Добавить в корзину</a>';
    }
    if ($language=="en")
    {
        $output .= '<a href="details.php?id='.$product["id"].'" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Details</a>';
        $output .= '<a href="#" data-product-id="' . $product['id'] . '" class="btn btn-sm text-dark p-0 add-to-cart-btn"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add to cart</a>';
    }
    if ($language=="it")
    {
        $output .= '<a href="details.php?id='.$product["id"].'" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Più dettagli</a>';
        $output .= '<a href="#" data-product-id="' . $product['id'] . '" class="btn btn-sm text-dark p-0 add-to-cart-btn"><i class="fas fa-shopping-cart text-primary mr-1"></i>Aggiungi al carrello</a>';
    }
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;
}

function getProducts($language)
{

    $productService=getProductService();
    try {
        $products = $productService->getAll();
        $html = '';
        foreach ($products as $product) {
            $html .= renderProduct($product,$language);
        }
        echo $html;
    }
    catch (Exception $exception) {
        echo $exception->getMessage();
    }
}
if ($_SERVER['REQUEST_METHOD']=="POST")
{
    if (isset($_POST['action']))
    {
        session_start();
        $language=$_SESSION['language'];
        $action=$_POST['action'];
        if ($action=="filter")
        {
            try {
                $priceFilter = isset($_POST['priceFilter']) ? $_POST['priceFilter'] : '';
                $countryFilter = isset($_POST['countryFilter']) ? $_POST['countryFilter'] : '';
                $stockFilter = isset($_POST['stockFilter']) ? $_POST['stockFilter'] : '';
                error_log($priceFilter);
                error_log($countryFilter);
                $priceArray = $priceFilter != '' ? explode(' ', $priceFilter) : [];
                $countryArray = $countryFilter != '' ? explode(' ', $countryFilter) : [];
                $stockArray = $stockFilter != '' ? explode(' ', $stockFilter) : [];

                $productService=getProductService();
                $filteredProducts=$productService->getFilteredProduct($priceArray,$countryArray,$stockArray);
                $html='';
                foreach ($filteredProducts as $product) {
                    $html .= renderProduct($product,$language);
                }
                echo $html;
            }
            catch (Exception $exception) {
                echo $exception->getMessage();
            }
        }
        if ($action=="sort")
        {
            try {
                $sortParam=$_POST['sortParam'];
                $productService=getProductService();
                $sortedProducts=$productService->getSortedProducts($sortParam);
                $html='';
                foreach ($sortedProducts as $product) {
                    $html .= renderProduct($product,$language);
                }
                echo $html;
            }
    catch (Exception $exception) {
        echo $exception->getMessage();
    }
        }
    }
}
?>