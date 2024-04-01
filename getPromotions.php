<?php
require_once 'repositories/PromotionRepository.php';
require_once 'services/PromotionService.php';
require_once 'repositories/ProductRepository.php';
require_once 'repositories/PromotionDiscountRepository.php';
require_once 'services/ProductService.php';
include_once 'config/db_connection.php';
include_once 'getProducts.php';

use repositories\ProductRepository;
use repositories\PromotionDiscountRepository;
use services\PromotionService;
use repositories\PromotionRepository;
function renderPromotion($promotion)
{
    $output = '<a href="promotionProducts.php?id=' . $promotion['id'] . '">';
    $output .= '<img height="500" width="1000" src="' . $promotion['image'] . '">';
    $output .= '</a>';
    return $output;
}
function getPromotions(){
    global $conn;
    $promotionRepository=new PromotionRepository($conn);
    $productRepository=new ProductRepository($conn);
    $promotionDiscountRepository=new PromotionDiscountRepository($conn);
    $promotionService=new PromotionService($promotionRepository,$promotionDiscountRepository,$productRepository);
    $promotions=$promotionService->getActivePromotions();
    $html='';
    foreach ($promotions as $promotion)
    {
        $html.=renderPromotion($promotion);
    }
    echo $html;
}
function renderProductsInPromotions($promotionId)
{
    global $conn;
    $promotionRepository=new PromotionRepository($conn);
    $productRepository=new ProductRepository($conn);
    $promotionDiscountRepository=new PromotionDiscountRepository($conn);
    $promotionService=new PromotionService($promotionRepository,$promotionDiscountRepository,$productRepository);
    $promotionProducts=$promotionService->getAllProductsInPromotion($promotionId);
    if (count($promotionProducts)==0)
    {
        echo "Ничего не найдено";
        exit();
    }
    $html='';
    foreach ($promotionProducts as $product)
    {
        $html.=renderProduct($product);
    }
    echo $html;
}
?>
