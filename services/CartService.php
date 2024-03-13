<?php

namespace services;
use Exception;
use http\Exception\InvalidArgumentException;
use repositories\CartRepository;
class CartService
{
    private $cartRepository;
    public function  __construct(CartRepository $cartRepository)
    {
        $this->cartRepository=$cartRepository;
    }
    public function getCartByUserId($userId)
    {
        if (!userId)
            throw new Exception("Invalid id");
        $productInCart=$this->cartRepository->getCartItemsByUserId($userId);
        if (count($productInCart)==0)
            throw new InvalidArgumentException("No products in cart");
        return $productInCart;
    }
    public function deleteFromUserCartByProductId($userId,$productId)
    {
        if (!userId)
            throw new Exception("Invalid id");
        $this->cartRepository->delete($productId,$userId);
        return $this->cartRepository->getCartItemsByUserId($userId);
    }
    public function clearCartByUserId($userId)
    {
        if (!userId)
            throw new Exception("Invalid id");
        $this->cartRepository->clearCart($userId);
        return $this->cartRepository->getCartItemsByUserId($userId);
    }
    public function addToCart($userId, $productId, $count)
    {
        if (!userId)
            throw new Exception("Invalid id");
        $this->cartRepository->addToCart($userId,$productId,$count);
        return $this->cartRepository->getCartItemsByUserId($userId);
    }

}