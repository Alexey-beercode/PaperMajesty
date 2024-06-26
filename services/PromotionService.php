<?php

namespace services;

use repositories\PromotionRepository;
use repositories\PromotionDiscountRepository;
use repositories\ProductRepository;

class PromotionService
{
    private $promotionRepository;
    private $promotionDiscountRepository;
    private $productRepository;

    public function __construct(
        PromotionRepository $promotionRepository,
        PromotionDiscountRepository $promotionDiscountRepository,
        ProductRepository $productRepository
    ) {
        $this->promotionRepository = $promotionRepository;
        $this->promotionDiscountRepository = $promotionDiscountRepository;
        $this->productRepository = $productRepository;
    }

    public function addPromotion($startDate, $endDate, $name,$image)
    {
        $this->promotionRepository->create($startDate, $endDate, $name,$image);
    }

    public function addDiscount($productId, $discount, $promotionId)
    {
        $promotionsDiscounts=$this->promotionDiscountRepository->findByPromotionId($promotionId);
        foreach ($promotionsDiscounts as $promotionsDiscount)
        {
            if ($promotionsDiscount['productId']==$productId);
            return;
        }
        $this->promotionDiscountRepository->create($productId, $discount, $promotionId);
    }

    public function getAllProductsInPromotion($promotionId)
    {
        $discounts = $this->promotionDiscountRepository->findByPromotionId($promotionId);
        $productIds = array_column($discounts, 'productId');
        return $this->getProductsByIds($productIds);
    }

    public function getAllProductsInAllPromotions()
    {
        $discounts = $this->promotionDiscountRepository->getAll();
        return ($discounts);
    }
    public function getByProductId($productId)
    {
        $productDiscount=$this->promotionDiscountRepository->getByProductId($productId);
        return $productDiscount;
    }

    public function deletePromotion($promotionId)
    {
        $this->promotionRepository->delete($promotionId);
        $this->promotionDiscountRepository->deleteByPromotionId($promotionId);
    }

    private function getProductsByIds($productIds)
    {
        $products = [];
        foreach ($productIds as $productId) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                $products[] = $product;
            }
        }
        return $products;
    }
    public function getAll()
    {
        return $this->promotionRepository->getAll();
    }
    public function getActivePromotions()
    {
        return $this->promotionRepository->getAllActivePromotions();
    }
}
