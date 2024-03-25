<?php
namespace services;

use repositories\CouponRepository;
use repositories\CouponDiscountRepository;
use repositories\ProductRepository;

class CouponService
{
    private $couponRepository;
    private $couponDiscountRepository;
    private $productRepository;

    public function __construct(CouponRepository $couponRepository, CouponDiscountRepository $couponDiscountRepository, ProductRepository $productRepository)
    {
        $this->couponRepository = $couponRepository;
        $this->couponDiscountRepository = $couponDiscountRepository;
        $this->productRepository = $productRepository;
    }

    public function getProductsAndDiscountsByCouponCode($couponCode)
    {
        // Find the coupon by code
        $coupon = $this->couponRepository->findByCode($couponCode);

        if (!$coupon) {
            return null; // Coupon not found
        }

        // Check if the coupon has expired
        if ($this->isCouponExpired($coupon)) {
            return null; // Coupon has expired
        }

        // Get discounts associated with the coupon
        $couponDiscounts = $this->couponDiscountRepository->findByCouponId($coupon['id']);

        // Get products and their discounts
        $productsAndDiscounts = [];
        foreach ($couponDiscounts as $couponDiscount) {
            $product = $this->productRepository->find($couponDiscount['productId']);
            if ($product) {
                $productsAndDiscounts[] = [
                    'product' => $product,
                    'discount' => $couponDiscount['discount'],
                ];
            }
        }

        return $productsAndDiscounts;
    }

    public function isCouponExpired($coupon)
    {
        $currentTime = time();
        $expireTime = strtotime($coupon['expireTime']);
        return $currentTime > $expireTime;
    }

    public function addCoupon($expireTime, $name)
    {
        return $this->couponRepository->create($expireTime, $name);
    }
    // Add other methods as needed...
}
