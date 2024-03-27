<?php
require_once 'repositories/ProductCategoryRepository.php';
use repositories\ProductCategoryRepository;
include_once 'config/db_connection.php';
global $conn;
$categoryRepository = new ProductCategoryRepository($conn);
$categories = $categoryRepository->getAll();
function renderCategoryLink($category) {
    return '<a  data-category-id="' . $category['id'] . '" id="category-nav" class="nav-item nav-link">' . $category['name'] . '</a>';
    //return '<a  data-category-id="' . $category['id'] . '" id="category-nav-' . $category['name'] . '" class="nav-item nav-link">' . $category['name'] . '</a>';
}
$html = '';
foreach ($categories as $category) {
    $html .= renderCategoryLink($category);
}
//href="getProductsByCategory.php?categoryId=' . $category['id'] . '"
echo $html;
?>
