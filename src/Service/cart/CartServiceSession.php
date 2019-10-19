<?php

namespace App\Service\cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServiceSession{
    protected $repo;
    protected $session;
    public function __construct(ProductRepository $repo, SessionInterface $session)
    {
        $this->repo = $repo;
        $this->session = $session;
    }
    public function getProducts() : array {
        $cart = $this->session->get("cart", []);
        $cartWithProducts = [];
        foreach ($cart as $id => $quantity) {
            $cartWithProducts[] = [
                "product" => $this->repo->find($id),
                "quantity" => $quantity
            ];
        };
        return $cartWithProducts;
    }
    public function getTotall() : float {
        $totall = 0;
        foreach ($this->getProducts() as $item) {
            $totall += $item["product"]->getPrice() * $item["quantity"];
        }
        return $totall;
    }
    public function addProduct(int $id) {
        $cart = $this->session->get("cart", []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set("cart", $cart);
    }
    public function deleteProduct(int $id) {
        $cart = $this->session->get("cart", []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $this->session->set("cart", $cart);
    }
}