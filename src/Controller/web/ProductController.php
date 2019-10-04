<?php

namespace App\Controller\web;

use App\Entity\Product;
use App\Repository\ProductRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
     * @Route("/product/{id}", name="getProduct", methods={"GET"})
     */
    public function getProduct(Product $product)
    {
        return $this->render("product/product.html.twig", ["product" => $product]);
    }
    /**
     * @Route("/products/new", name="newProduct", methods={"POST","GET"})
     */
    public function newProduct(Request $request, ObjectManager $manager)
    {
        $product = new Product();

        $form = $this->createFormBuilder($product)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control mb-2')))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-2')
                ))
            ->add('image', TextType::class, array('attr' => array('class' => 'form-control mb-2')))
            ->add('price', IntegerType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-2')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary btn-block mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            // $entityManager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('products');
        }
        return $this->render('product/new.html.twig', array(
            'form' => $form->createView()
        ));
    }
    /**
     * @Route("/product/edit/{id}", name="updateProduct", methods={"POST","GET"})
     */
    public function updateProduct(Product $product, Request $request, ObjectManager $manager)
    {
        $form = $this->createFormBuilder($product)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control mb-2')))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-2')
                ))
            ->add('image', TextType::class, array('attr' => array('class' => 'form-control mb-2')))
            ->add('price', IntegerType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control mb-2')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-warning btn-block mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            // $entityManager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirectToRoute('products');
        }
        return $this->render('product/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }
    /**
     * @Route("/product/delete/{id}", name="deleteProduct", methods={"GET"})
     */
    public function deleteProduct(Product $product, Request $request, ObjectManager $manager)
    {
        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute('products');
    }
}
