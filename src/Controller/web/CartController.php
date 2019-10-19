<?php

namespace App\Controller\web;

use App\Service\cart\CartServiceSession;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="getCartProducts")
     */
    public function getCartProducts(CartServiceSession $cartServiceSession)
    {
        return $this->render(
            "cart/index.html.twig",
            [
                "cartWithProducts" => $cartServiceSession->getProducts(),
                "totall" => $cartServiceSession->getTotall()
            ]
        );
    }
    /**
     * @Route("/cart/add/{id}", name="newCartProduct")
     */
    public function submitCartProduct($id, CartServiceSession $cartServiceSession)
    {
        $cartServiceSession->addProduct($id);
        return $this->redirectToRoute('getCartProducts');
    }
    /**
     * @Route("/cart/delete/{id}", name="deleteCartProduct")
     */
    public function deleteCartProduct($id, CartServiceSession $cartServiceSession)
    {
        $cartServiceSession->deleteProduct($id);
        return $this->redirectToRoute('getCartProducts');
    }
}
