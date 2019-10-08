<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Review;
// use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('rating', null, [
                'attr' => [
                    'min' => 1,
                    'max' => 5
                ]
            ])
            // ->add('mock_user')
            // ->add('createdAt')
            // ->add('product', EntityType::class, ['class'=> Product::class, 'choice_label' => 'title'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
