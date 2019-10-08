<?php

namespace App\Controller\web;

use App\Entity\Product;
use App\Entity\Review;
use App\Form\ProductReviewType;
use App\Form\ProductType;
use App\Repository\ProductRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function getProducts(ProductRepository $repo)
    {
        $products = $repo->findAll();
        return $this->render("product/products.html.twig", ["products" => $products]);
    }
    /**
     * @Route("/product/{id}", name="getProduct")
     */
    public function getProduct(Product $product, Request $request, ObjectManager $manager)
    {
        // add Review 
        $review = new Review();
        $form = $this->createForm(ProductReviewType::class, $review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();
            $review->setMockUser('mock User');
            $review->setProduct($product);
            $review->setCreatedAt(new \DateTime());

            $manager->persist($review);
            $manager->flush();

            $review = new Review();
            $form = $this->createForm(ProductReviewType::class, $review);
        }
        return $this->render("product/product.html.twig", ["product" => $product, 'form' => $form->createView()]);
    }
    /**
     * @Route("/products/new", name="newProduct", methods={"POST","GET"})
     * @Route("/product/edit/{id}", name="updateProduct", methods={"POST","GET"})
     */
    public function newProduct(Product $product = null, Request $request, ObjectManager $manager)
    {
        if (!$product) {
            $product = new Product();
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('products');
        }
        return $this->render('product/submit.html.twig', array(
            'form' => $form->createView(),
            'isEdit' => $product->getId() !== null,
        ));
    }

    /**
     * @Route("/product/delete/{id}", name="deleteProduct", methods={"GET"})
     */
    public function deleteProduct(Product $product, ObjectManager $manager)
    {
        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute('products');
    }
    /**
     * @Route("/product/review/delete/{id}", name="deleteProductReview", methods={"GET"})
     */
    public function deleteProductReview(Review $review, ObjectManager $manager)
    {
        $manager->remove($review);
        $manager->flush();

        return $this->json('review deleted');
    }
}
