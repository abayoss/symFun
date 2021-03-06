<?php

namespace App\Controller\api;

use App\Entity\Product;
use App\Entity\Review;
use App\Repository\ProductRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\ImageUploader;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="ApiProducts")
     */
    public function getProducts(ProductRepository $repo)
    {
        $products = $repo->findAll();
        return $this->json(["products" => $products, "user" => $this->getUser()]);
    }
    /**
     * @Route("/api/product/{id}", name="ApiGetProduct", methods={"GET"})
     */
    public function getProduct(Product $product)
    {
        return $this->json(["product" => $product]);
    }
    /**
     * @Route("/api/product", name="ApiNewProduct", methods={"POST"})
     */
    public function newProduct(Request $request, ObjectManager $manager, ImageUploader $imageUploader)
    {
        $req = $request->request;
        $product = new Product;
        $product->setUser($this->getUser());
        if (
            !$req->get('title')
            & !$req->get('description')
            & !$req->get('price')
            & !$req->get('image')
            & !$request->files->get("image_file")
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
        } else if ($request->files->get("image_file")) {
            $imageFile = $request->files->get("image_file");
            $fileName = $imageUploader->upload($imageFile);
            $product->setImage($fileName);
        } else {
            $product->setImage("/uploads/images/products/no_image_to_show_-5d9fa1b8ad3ae.webp");
        }

        $manager->persist($product);
        $manager->flush();

        return $this->json($product);
    }
    /**
     * @Route("/api/product/edit/{id}", name="ApiUpdateProduct", methods={"POST"})
     */
    public function updateProduct(Product $product, Request $request, ObjectManager $manager, ImageUploader $imageUploader)
    {
        if ($this->getUser() !== $product->getUser()) {
            return $this->json(["code"=> 401, 'message' => 'not authorized'], 401);
        }
        $req = $request->request;
        if (
            !$req->get('title')
            & !$req->get('description')
            & !$req->get('price')
            & !$req->get('image')
            & !$request->files->get("image_file")
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
        } else if ($request->files->get("image_file")) {
            $imageFile = $request->files->get("image_file");
            $fileName = $imageUploader->upload($imageFile);
            $product->setImage($fileName);
        } else {
            $product->setImage("/uploads/images/products/no_image_to_show_-5d9fa1b8ad3ae.webp");
        }
        $manager->flush();
        return $this->json($product);
    }
    /**
     * @Route("/api/product/{id}", name="ApiDeleteProduct", methods={"DELETE"})
     */
    public function deleteProduct(Product $product, ObjectManager $manager)
    {
        if ($this->getUser() !== $product->getUser()) {
            return $this->json('not authorized', 401);
        }
        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute('products');
    }
    /**
     * @Route("/api/product/{id}/review/add", name="ApiAddReview", methods={"POST"})
     */
    public function addProductReview(Product $product, Request $request, ObjectManager $manager)
    {
        if (!$this->getUser()) {
            return $this->json(["code"=> 401, 'message' => 'not authorized'], 401);
        }
        $req = $request->request;
        $review = new Review();
        $review->setUser($this->getUser());
        $review->setMockUser('mock User');
        $review->setProduct($product);
        $review->setCreatedAt(new \DateTime());

        $review->setBody($req->get('body'));
        $review->setRating($req->get('rating'));

        $manager->persist($review);
        $manager->flush();

        return $this->json($review);
    }
    /**
     * @Route("/api/product/review/delete/{id}", name="ApiDeleteProductReview", methods={"DELETE"})
     */
    public function deleteProductReview(Review $review, ObjectManager $manager)
    {
        if ($this->getUser() !== $review->getUser()) {
            return $this->json(["code"=> 401, 'message' => 'not authorized'], 401);
        }
        $manager->remove($review);
        $manager->flush();

        return $this->json('review deleted');
    }
}
