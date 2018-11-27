<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartManager
{
    const SESSION_CART_NAME = '_cart';

    private $session;
    private $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function getCart()
    {
        $cart = $cart = $this->get();
        $result = [];

        foreach ($cart as $productId => $quantity) {
            $item = [];
            $item['product'] = $this->productRepository->find($productId);
            $item['quantity'] = $quantity;
            $result[] = $item;
        }

        return $result;
    }

    public function getCart2()
    {
        $cart = $cart = $this->get();
        $result = [];

        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepository->find($productId);

            if (!method_exists($product, 'setQuantity')) {
                throw new \Exception('add quantity to product');
            }

            $product->setQuantity($quantity);
            $result[] = $product;
        }
        return $result;
    }

    public function add($productId, $quantity = 1)
    {
        if (null === $this->productRepository->find($productId)) {
            return;
        }

        $cart = $cart = $this->get();

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        $this->save($cart);
    }

    public function remove($productId)
    {
        $cart = $cart = $this->get();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->save($cart);
        }
    }

    public function change($productId, $quantity)
    {
        if (null === $this->productRepository->find($productId)) {
            return;
        }

        $cart = $this->get();

        $cart[$productId] = $quantity;

        $this->save($cart);
    }

    public function emptyCart()
    {
        $this->save([]);
    }

    private function get(): array
    {
        $cart = $this->session->get(self::SESSION_CART_NAME);

        if (is_array($cart)) {
            return $cart;
        }

        return [];
    }

    private function save(array $cart)
    {
        $this->session->set(self::SESSION_CART_NAME, $cart);
    }
}