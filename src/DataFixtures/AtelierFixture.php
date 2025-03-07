<?php

namespace App\DataFixtures;

use App\Entity\Atelier;
use App\Entity\Inscription;
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
        for($i=1 ; $i<4 ; $i++){
            $user = new User();
            $user->setUsername('apprenti_'.$i)
                ->setPassword($this->passwordHasher->hashPassword(
                    $user,'secret'
                ))
                ->setNom('NomApprenti'.$i)
                ->setPrenom("PrenomApprenti$i")
                ->setRoles(['ROLE_APPRENTI']);
            $manager->persist($user);
            $users[]= $user;
        }

        $user2 =[];
        for($i=1 ; $i<3 ; $i++){
            $user = new User();
            $user->setUsername('instructeur_'.$i)
                ->setPassword($this->passwordHasher->hashPassword(
                    $user,'secret'
                ))
                ->setNom('NomInstructeur'.$i)
                ->setPrenom("PrenomInstructeur$i")
                ->setRoles(['ROLE_INSTRUCTEUR']);
            $manager->persist($user);
            $user2[]=$user;
        }


        $faker = Factory::create("fr_FR");
        $lesAteliers=[];
        for ($i=0; $i<4 ; $i++ ){
            $atelier = new Atelier();
            $atelier->setNom($faker->company())
                ->setDescription(join("\n\n", $faker->paragraphs))
                ->setUsername($user2[array_rand($user2)]);
            $manager->persist($atelier);
            $lesAteliers[]=$atelier;
        }
        // les inscripttions d'apprenti
        for ($i = 0; $i < 3; $i++) {
            $inscription = new Inscription();
            $atelier = $lesAteliers[array_rand($lesAteliers)];
            $user = $users[array_rand($users)];
            $inscription->setAtelier($atelier)
                ->setUser($user);

            // Mise à jour des deux côtés de la relation
            $user->addInscription($inscription);
            $atelier->addInscription($inscription);

            $manager->persist($inscription);
        }

        $manager->flush();
    }





}
