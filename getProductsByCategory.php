<?php
require_once 'repositories/ProductRepository.php';
require_once 'repositories/ProductCategoryRepository.php';
require_once 'services/ProductService.php';

use repositories\ProductCategoryRepository;
use repositories\ProductRepository;
use services\ProductService;

include_once 'config/db_connection.php';
include_once 'getProducts.php';
include_once 'getOrderHistory.php';
global $conn;
$productCategoryRepository=new ProductCategoryRepository($conn);
$productService=getProductService();

if (isset($_GET['categoryId'])) {
    $language=$_SESSION['language'];
    $categoryId = $_GET['categoryId'];
    try {
        error_log($categoryId);
        $products = $productService->getByCategoryId($categoryId);
        $html = '';
        if (count($products)==0)
        {
            echo "<h4>Ничего не найдено</h4>";
            exit;
        }
        foreach ($products as $product) {
            $html .= renderProduct($product,$language);
        }
        echo $html;
    }
    catch (Exception $exception) {
        echo $exception->getMessage();
    }
}
?>
