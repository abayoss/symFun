<?php

namespace App\Controller\api;

use App\Entity\Product;
use App\Repository\ProductRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="APIproducts")
     */
    public function getProducts(ProductRepository $repo)
    {
        $products = $repo->findAll();
        return $this->json(["products" => $products]);
    }
    /**
     * @Route("/api/product/{id}", name="APIgetProduct", methods={"GET"})
     */
    public function getProduct(Product $product)
    {
        return $this->json(["product" => $product]);
    }
    /**
     * @Route("/api/product", name="APInewProduct", methods={"POST"})
     */
    public function newProduct(Request $request, ObjectManager $manager)
    {
        $req = $request->request;
        $product = new Product;
        if (
            !$req->get('title')
            & !$req->get('description')
            & !$req->get('price')
            & !$req->get('image')
        ) {
            return $this->json("please send some data");
         }
        if ($req->get('title')) {
            $product->setTitle($req->get('title'));
        } else {
            $product->setTitle("no title");
        }
        if ($req->get('description')) {
            $product->setDescription($req->get('description'));
        } else {
            $product->setDescription("no description");
        }
        if ($req->get('price')) {
            $product->setPrice($req->get('price'));
        } else {
            $product->setPrice(0);
        }
        if ($req->get('image')) {
            $product->setImage($req->get('image'));
        } else {
            $product->setImage("https://cdn.dribbble.com/users/844846/screenshots/2855815/no_image_to_show_.jpg");
        }

        $manager->persist($product);
        $manager->flush();

        return $this->json($product);
    }
    /**
     * @Route("/api/product/edit/{id}", name="APIupdateProduct", methods={"POST"})
     */
    public function updateProduct(Product $product, Request $request, ObjectManager $manager)
    {
        $req = $request->request;
        if (
            !$req->get('title')
            & !$req->get('description')
            & !$req->get('price')
            & !$req->get('image')
        ) {
            return $this->json("please send some data to modify");
         }
        if ($req->get('title')) {
            $product->setTitle($req->get('title'));
        }
        if ($req->get('description')) {
            $product->setDescription($req->get('description'));
        }
        if ($req->get('price')) {
            $product->setPrice($req->get('price'));
        }
        if ($req->get('image')) {
            $product->setImage($req->get('image'));
        } else {
            $product->setImage("https://cdn.dribbble.com/users/844846/screenshots/2855815/no_image_to_show_.jpg");
        }
        $manager->flush();

        return $this->json($product);
    }
    /**
     * @Route("/api/product/{id}", name="APIdeleteProduct", methods={"DELETE"})
     */
    public function deleteProduct(Product $product, Request $request, ObjectManager $manager)
    {
        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute('products');
    }
}
