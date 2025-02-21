<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasher = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setNom("admin")
            ->setUsername("admin");
        $user->setPrenom("admin");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "secret"));

        $manager->persist($user);

        $manager->flush();
    }

}
