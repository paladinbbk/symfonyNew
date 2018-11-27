<?php

namespace App\Controller;

use App\Service\CartManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/cart")
 */
class CartController
{
    /**
     * @Route("/", name="cart_index")
     */
    public function cart(CartManager $cartManager)
    {
        $cart = $cartManager->getCart();

        return $this->render('cart.html.twig', compact('cart'));
    }

    /**
     * @Route("/add", name="cart_add")
     */
    public function addToCart(CartManager $cartManager, Request $request)
    {
        $cart = $cartManager->add($request->get('productId'), $request->get('quantity'));

        return $this->redirectToRoute('cart_index');
    }

}