<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {   
        for ($i=1; $i <= 10; $i++) { 
            $product = new Product();
            $product->setTitle("product n°$i")
                    ->setDescription("product n°$i description")
                    ->setImage("https://source.unsplash.com/collection/3356576")
                    ->setPrice(mt_rand(12,999)/10*0.3);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
