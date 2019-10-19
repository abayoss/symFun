<?php

namespace App\Controller\web;

use App\Entity\Product;
use App\Repository\ProductRepository;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="getCartProducts")
     */
    public function getCartProducts(ProductRepository $repo, SessionInterface $session)
    {
        // $session = $request->getSession();
        $cart = $session->get("cart", []);
        $cartWithProducts = [];
        $totall = 0;
        foreach ($cart as $id => $quantity) {
            $cartWithProducts[] = [
                "product" => $repo->find($id),
                "quantity" => $quantity
            ];
        };
        foreach ($cartWithProducts as $item) {
            $totall += $item["product"]->getPrice() * $item["quantity"];
        }
        // dd($cartWithProducts);
        return $this->render("cart/index.html.twig", ["cartWithProducts" => $cartWithProducts, "totall" => $totall]);
    }
    /**
     * @Route("/cart/add/{id}", name="newCartProduct")
     */
    public function submitCartProduct($id, SessionInterface $session)
    {
        // $session = $request->getSession();
        $cart = $session->get("cart", []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $session->set("cart", $cart);
        // dd($session->get("cart", []));
        return $this->redirectToRoute('getCartProducts');
    }
    /**
     * @Route("/cart/delete/{id}", name="deleteCartProduct")
     */
    public function deleteCartProduct($id, SessionInterface $session)
    {
        $cart = $session->get("cart", []);
        // add product & quantity
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        // delete product & quantity 
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->set("cart", $cart);
        return $this->redirectToRoute('getCartProducts');
    }
}
