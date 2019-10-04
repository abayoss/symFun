<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('title', TextType::class, array('attr' => array('class' => 'form-control mb-2')))
            // ->add('description', TextareaType::class, array(
            //     'required' => true,
            //     'attr' => array('class' => 'form-control mb-2')
            // ))
            // ->add('price', IntegerType::class, array(
            //     'required' => true,
            //     'attr' => array('class' => 'form-control mb-2')
            // ))
            // ->add('image', TextType::class, array('attr' => array('class' => 'form-control mb-2')))
            // ->add('save', SubmitType::class, array(
            //     'label' => 'Submit',
            //     'attr' => array('class' => 'btn btn-primary btn-block mt-3')
            // ))
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
