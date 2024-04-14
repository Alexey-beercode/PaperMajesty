<?php

namespace services;

use Exception;
use repositories\ProductRepository;
class ProductService
{
    private $promotionService;
    private $productRepository;
    public function __construct(ProductRepository $productRepository,PromotionService $promotionService)
    {
        $this->productRepository=$productRepository;
        $this->promotionService=$promotionService;
    }
    public function getById($id)
    {
        if (!$id)
            throw new Exception("Invalid id");
        $product=$this->productRepository->find($id);
        if ($product==null)
            throw new Exception("No product with id: ".$id."");
        return $product;
    }
    public function getByName($name)
    {
        return $this->productRepository->getByName($name);
    }
    public function getAll(){
        $products=$this->productRepository->getAll();
        if (count($products)==0)
            return [];
        foreach ($products as &$product) {
            if ($product['new_price']!=null)
            {
                $promotionDiscount = $this->promotionService->getByProductId($product['id']);
                if ($promotionDiscount == null) {
                    continue;
                } else {

                    $product['new_price'] = intval($product['price']) - intval($promotionDiscount[0]['discount']);
                }
            }

        }


        return $products;
    }
    public function create($product)
    {
        $this->productRepository->create($product);
    }
    public function update($product)
    {
        $this->productRepository->update($product);
    }
    public function getByCategoryId($categoryId)
    {
        if (!$categoryId)
            throw new Exception("Invalid id");
        $products=$this->productRepository->getByCategoryId($categoryId);
        return $products;
    }
    public function updateStockQuantity($newStockQuantity,$productId)
    {
        if (!$productId)
            throw new Exception("Invalid id");
        $product=$this->productRepository->find($productId);
        if ($product==null)
            return[];
        $product["stockQuantity"]=$newStockQuantity;
        $this->productRepository->update($product);
        return $product;
    }
    public function delete($id)
    {
        if (!$id)
            throw new Exception("Invalid id");
        $this->productRepository->delete($id);
    }
    public function searchByName($searchTerm)
    {
        if (!$searchTerm) {
            throw new Exception("Search term cannot be empty");
        }

        return $this->productRepository->searchByName($searchTerm);
    }

// Метод для проверки продукта по параметрам фильтрации
    private function checkProductFilter($product, $priceArray, $countryArray, $stockArray)
    {
        if ($product['new_price']!=null)
        {
            if (!empty($priceArray) && !in_array($product['new_price'], $priceArray)) {
                return false;
            }
        }
        else
        {
            if (!empty($priceArray) && !in_array($product['price'], $priceArray)) {
                return false;
            }
        }

        // Проверка по стране производства
        if (!empty($countryArray) && !in_array($product['createCountry'], $countryArray)) {
            return false;
        }

        // Проверка по наличию на складе
        if (!empty($stockArray)) {
            return false;
        }

        return true; // Продукт прошел все проверки фильтрации
    }
    public function getFilteredProduct($priceArray, $countryArray, $stockArray)
    {
        $allProducts = $this->productRepository->getAll();
        $filteredProducts = [];

        foreach ($allProducts as $product) {
            // Преобразование строки в массив числовых значений
            // Проверка фильтров
            if (!empty($priceArray)) {
                $priceInRange = false;
                foreach ($priceArray as $priceId) {
                    if ($this->checkPriceRange($product, $priceId)) {
                        $priceInRange = true;
                        break;
                    }
                }
                if (!$priceInRange) {
                    continue; // Пропускаем продукт, не попадающий в диапазон цен
                }
            }
            if (!empty($countryArray))
            {
                error_log("не пустой страна");
            }
            // Проверка по стране производства и наличию на складе
            if (!empty($countryArray) && !in_array($product['createCountry'], $countryArray)) {
                continue;
            }
            if (!empty($stockArray)){
                error_log("что то не так сток");
                $isRightStock=false;
                foreach ($stockArray as $stockId){
                    if ($this->checkStock($product,$stockId)){
                        $isRightStock=true;
                        break;
                    }
                }
                if (!$isRightStock){
                    continue;
                }
            }
            error_log("добавляем товар в список");
            $filteredProducts[] = $product;
        }

        return $filteredProducts;
    }

    private function checkPriceRange($product, $priceId)
    {
        if ($priceId=="all") return true;
        // Преобразование числового идентификатора в соответствующий диапазон цен
        if (isset($product['new_price'])) {
            switch ($priceId) {
                case '1':
                    return $product['new_price'] >= 0 && $product['new_price'] <= 5;
                case '2':
                    return $product['new_price'] > 5 && $product['new_price'] <= 10;
                case '3':
                    return $product['new_price'] > 10 && $product['new_price'] <= 20;
                case '4':
                    return $product['new_price'] > 20 && $product['new_price'] <= 30;
                case '5':
                    return $product['new_price'] > 30 && $product['new_price'] <= 40;
                case '6':
                    return $product['new_price'] > 40 && $product['new_price'] <= 50;
                default:
                    return true; // По умолчанию пропускаем все продукты
            }
        }
        switch ($priceId) {
            case '1':
                return $product['price'] >= 0 && $product['price'] <= 5;
            case '2':
                return $product['price'] > 5 && $product['price'] <= 10;
            case '3':
                return $product['price'] > 10 && $product['price'] <= 20;
            case '4':
                return $product['price'] > 20 && $product['price'] <= 30;
            case '5':
                return $product['price'] > 30 && $product['price'] <= 40;
            case '6':
                return $product['price'] > 40 && $product['price'] <= 50;
            default:
                return true; // По умолчанию пропускаем все продукты

        }
    }
    private function checkStock($product, $stockId)
    {
        switch ($stockId){
            case 'outOfstock':
                return $product['stockQuantity']==0;
            case 'inStock':
                return $product['stockQuantity']>0;
        }
    }
    public function getSortedProducts($sortParam)
    {
        $allProducts = $this->productRepository->getAll();


        // Сортировка товаров в зависимости от параметра
        switch ($sortParam) {
            case 'name':
                usort($allProducts, function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
                break;
            case 'stockQuantity':
                usort($allProducts, function ($a, $b) {
                    return $a['stockQuantity'] - $b['stockQuantity'];
                });
                break;
            case 'availability':
                $availableProducts = [];
                $unavailableProducts = [];
                foreach ($allProducts as $product) {
                    if ($product['stockQuantity'] > 0) {
                        $availableProducts[] = $product;
                    } else {
                        $unavailableProducts[] = $product;
                    }
                }
                $sortedProducts = array_merge($availableProducts, $unavailableProducts);
                return $sortedProducts;
            case 'price':
                usort($allProducts, function ($a, $b) {
                    if (isset($a['new_price'])) {
                        return $a['new_price'] - $b['new_price'];
                    } else {
                        return $a['price'] - $b['price'];
                    }
                });
                break;
            default:
                // По умолчанию, возвращаем все товары без сортировки
                break;
        }

        return $allProducts;
    }

}