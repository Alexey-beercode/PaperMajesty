<?php
include_once 'config/db_connection.php';
require_once 'repositories/CouponRepository.php';
require_once 'repositories/OrderProductRepository.php';
require_once 'repositories/OrderStatusRepository.php';
require_once 'services/ProductService.php';
require_once 'repositories/ProductRepository.php';
require_once 'repositories/OrderRepository.php';
require_once 'repositories/ProductCategoryRepository.php';
require_once 'repositories/UserRepository.php';
require_once 'repositories/RoleRepository.php';
require_once 'repositories/UserRoleRepository.php';
require_once 'services/UserService.php';
require_once 'services/OrderService.php';
require_once 'repositories/PromotionDiscountRepository.php';
require_once 'repositories/PromotionRepository.php';
require_once 'services/PromotionService.php';
require_once 'repositories/CouponDiscountRepository.php';
require_once 'services/CouponService.php';
include_once 'getOrderHistory.php';

use repositories\CouponDiscountRepository;
use repositories\CouponRepository;
use repositories\OrderProductRepository;
use repositories\OrderRepository;
use repositories\OrderStatusRepository;
use repositories\ProductCategoryRepository;
use repositories\PromotionDiscountRepository;
use repositories\PromotionRepository;
use repositories\RoleRepository;
use repositories\UserRepository;
use repositories\UserRoleRepository;
use services\CouponService;
use services\ProductService;
use repositories\ProductRepository;
use services\OrderService;
use services\PromotionService;
use services\UserService;
function updateOrderStatus($orderId,$orderStatus)
{
    $orderService=getOrderService();
    $orderService->updateOrderStatus($orderId,$orderStatus);
}
function deleteProduct($productId)
{
    global $conn;
    $productService=getProductService();
    $productService->delete($productId);
}
function updateNewPrice($productId,$newPrice)
{
    $productService=getProductService();
    $product=$productService->getById($productId);
    $product['new_price']=$newPrice;
    $productService->update($product);
}
function updateStockQuantity($productId,$newStockQuantity)
{
    $productService=getProductService();
    $product=$productService->getById($productId);
    $product['stockQuantity']=$newStockQuantity;
    $productService->update($product);
}
function addProductToPromotion($productName,$promotionId,$discount)
{
    $promotionService = getPromotionService();
    $productService = getProductService();
    $product = $productService->getByName($productName);
    $promotionService->addDiscount($product['id'], $discount, $promotionId);
}
function renderAddProduct()
{
    global $conn;
    $productCategoryRepositories=new ProductCategoryRepository($conn);
    $productCategories=$productCategoryRepositories->getAll();
    $html='';
    $html.='
<div class="container-fluid pt-5">
  <div class="row justify-content-center"> <div class="col-lg-8"> <div class="mb-4">
        <h4 class="font-weight-semi-bold mb-4 " align="center">Добавление товара</h4>
        <form action="admin.php" method="post" class="form-group shadow-sm rounded p-4 mx-auto" style="max-width: 500px;">
          <input type="hidden" name="action" value="addProduct">
          <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" name="name">
          </div>
          <div class="mb-3">
            <label for="price" class="form-label">Цена</label>
            <input type="number" step="0.01" class="form-control" name="price">
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <input type="text" class="form-control" name="description">
          </div>
          <div class="mb-3">
            <label for="imageUrl" class="form-label">URl картинки</label>
            <input type="text" class="form-control" name="imageUrl">
          </div>
          <div class="mb-3">
            <label for="stockQuantity" class="form-label">Кол-во на складе</label>
            <input type="number" class="form-control" name="stockQuantity">
          </div>
          <div class="mb-3">
            <label for="categoryId" class="form-label">Категория</label>
            <select name="categoryId" class="form-select">';

              foreach ($productCategories as $productCategory) {
                  $html .= '<option value="' . $productCategory['id'] . '">' . $productCategory['name'] . '</option>';
              }
$html.='</select>
          </div>
          <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
      </div>
    </div>
  </div>
</div>';
    return $html;
}
function renderAddPromotion()
{
    $html='';
    $html = '';
    $html .= '
<div class="container-fluid pt-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="mb-4">
        <h4 class="font-weight-semi-bold mb-4" align="center">Добавление акции</h4>
        <form action="admin.php" method="post" class="form-group shadow-sm rounded p-4 mx-auto" style="max-width: 500px;">
          <input type="hidden" name="action" value="addPromotion">
          <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" name="name">
          </div>
          <div class="mb-3">
            <label for="startDate" class="form-label">Дата начала</label>
            <input type="date" class="form-control" name="startDate">
          </div>
          <div class="mb-3">
            <label for="endDate" class="form-label">Дата окончания</label>
            <input type="date" class="form-control" name="endDate">
          </div>
          <div class="mb-3">
            <label for="imageUrl" class="form-label">URl картинки</label>
            <input type="text" class="form-control" name="imageUrl">
          </div>
          <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
      </div>
    </div>
  </div>
</div>';

    return $html;
}
function renderAddCoupon()
{
    $html='';
    $html .= '
<div class="container-fluid pt-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="mb-4">
        <h4 class="font-weight-semi-bold mb-4" align="center">Добавление купона</h4>
        <form action="admin.php" method="post" class="form-group shadow-sm rounded p-4 mx-auto" style="max-width: 500px;">
          <input type="hidden" name="action" value="addCoupon">
          <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" name="name">
          </div>
          <div class="mb-3">
            <label for="code" class="form-label">Код</label>
            <input type="text" class="form-control" name="code">
          </div>
          <div class="mb-3">
            <label for="endDate" class="form-label">Дата окончания</label>
            <input type="date" class="form-control" name="expireDate">
          </div>
          <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
      </div>
    </div>
  </div>
</div>';
    return $html;
}