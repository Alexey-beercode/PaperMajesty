<?php

namespace services;

use Exception;
use repositories\ProductRepository;
class ProductService
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository=$productRepository;
    }
    public function getAll()
    {
        $products=$this->productRepository->getAll();
        if (count($products)==0)
            throw new Exception("No products");
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
        $products=$this->productRepository->getByProductId($categoryId);
        if (count($products)==0)
            throw new Exception("No products with category id: ".$categoryId->toString()."");
        return $products;
    }
    public function updateStockQuantity($newStockQuantity,$productId)
    {
        if (!$productId)
            throw new Exception("Invalid id");
        $product=$this->productRepository->find($productId);
        if ($product==null)
            throw new Exception("No product with id: ".$productId->toString()."");
        $product["stockQuantity"]=$newStockQuantity;
        $this->productRepository->update($product);
        return $product;
    }
    public function delete($id)
    {
        if (!id)
            throw new Exception("Invalid id");
        $this->productRepository->delete($id);
    }
}