<?php

namespace App\DataFixtures;

use App\Entity\Atelier;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AtelierFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $users =[];
        for($i=0 ; $i<3 ; $i++){
            $user = new User();
            $user->setUsername('instructeur_'.$i)
                ->setPassword($this->passwordHasher->hashPassword(
                    $user,'secret'
                ))
                ->setNom('Nom'.$i)
                ->setPrenom("Prenom$i");
            $manager->persist($user);
            $users[]= $user;
        }


        $faker = Factory::create("fr_FR");

        for ($i=0; $i<4 ; $i++ ){
            $atelier = new Atelier();
            $atelier->setNom($faker->company())
                ->setDescription(join("\n\n", $faker->paragraphs))
                ->setUsername($users[array_rand($users)]);
            $manager->persist($atelier);
        }
        $manager->flush();
    }

}
