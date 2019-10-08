<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Review;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $faker = \Faker\Factory::create();
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {

            $product = new Product();
            $product->setTitle("product n°$i")
                ->setDescription("product n°$i description")
                ->setImage("https://source.unsplash.com/collection/3356576")
                ->setPrice(mt_rand(1, 1000) / 10);
            $manager->persist($product);
            
            for ($j = 0; $j < mt_rand(2, 6); $j++) {
                $review = new Review();
                $review->setBody($faker->paragraph(mt_rand(1, 3)))
                    ->setMockUser($faker->name)
                    ->setRating(mt_rand(1, 5))
                    ->setProduct($product)
                    ->setCreatedAt($faker->dateTimeBetween("-6 months"));
                $manager->persist($review);
            }
        }

        $manager->flush();
    }
}
