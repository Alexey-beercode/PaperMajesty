<?php
require_once 'repositories/ProductCategoryRepository.php';
use repositories\ProductCategoryRepository;
include_once 'config/db_connection.php';
global $conn;
$categoryRepository = new ProductCategoryRepository($conn);
$categories = $categoryRepository->getAll();

function renderCategoryLink($category) {
    return '<a href="getProductByCategory.php?categoryId=' . $category['id'] . '" class="nav-item nav-link">' . $category['name'] . '</a>';
}
$html = '';
foreach ($categories as $category) {
    $html .= renderCategoryLink($category);
}
echo $html;
?>
