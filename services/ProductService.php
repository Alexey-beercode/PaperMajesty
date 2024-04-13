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

}