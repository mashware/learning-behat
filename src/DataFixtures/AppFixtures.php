<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category("Satisfiers", "Masajeatelo todo");
        $manager->persist($category);
        
        $product = new Product("Masajeador de Ojos", "Disfruta del masaje en todos tus ojos");
        $product->setCategory($category);
        $manager->persist($product);
        
        $product = new Product("Masajeador de pieles", "Disfruta del masaje en tu escroto");
        $product->setCategory($category);
        $manager->persist($product);

        $manager->flush();
    }
}
