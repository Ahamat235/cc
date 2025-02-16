<?php

namespace App\DataFixtures;

use App\Entity\Atelier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AtelierFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create("fr_FR");

        for ($i=0; $i<4 ; $i++ ){
            $atelier = new Atelier();
            $atelier->setNom($faker->company())
                ->setDescription(join("\n\n", $faker->paragraphs));
            $manager->persist($atelier);
        }
        $manager->flush();
    }
}
