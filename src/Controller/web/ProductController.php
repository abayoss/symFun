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
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @Route("/product/{id}", name="getProduct", methods={"POST","GET"})
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
        if ($form->isSubmitted()) {
            $product = $form->getData();
            if ($form['image_File']->getData()) {
                $imageFile = $form['image_File']->getData();
                $fileName = $this->uploadImage($imageFile);
                $product->setImage($fileName);
            }
            if (!$product->getImage()) {
                $product->setImage("/uploads/images/products/no_image_to_show_-5d9fa1b8ad3ae.webp");
            };

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
     * @Route("/product/{id}", name="deleteProduct", methods={"DELETE"})
     */
    public function deleteProduct(Product $product, ObjectManager $manager)
    {
        $manager->remove($product);
        $manager->flush();
        return $this->json('product deleted');
    }
    /**
     * @Route("/product/review/delete/{id}", name="deleteProductReview", methods={"DELETE"})
     */
    public function deleteProductReview(Review $review, ObjectManager $manager)
    {
        $manager->remove($review);
        $manager->flush();
        return $this->json('review deleted');
    }
    public function uploadImage(UploadedFile $imageFile)
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
        try {
            $imageFile->move(
                $this->getParameter('product_images_directory')
                    . $this->getParameter('product_images_directory_URL'),
                $newFilename
            );
        } catch (FileException $e) {
            var_dump($e);
        }
        return $this->getParameter('product_images_directory_URL') . $newFilename;
    }
}
