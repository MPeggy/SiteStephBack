<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Exception;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    /** @throws Exception */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $user = (new User())
                ->setFirstName("Firstname $i")
                ->setLastName("Lastname $i")
                ->setEmail("email.$i@studi.fr")
                ->setTelephone("060000000$i")
                ->setCreatedAt(new DateTimeImmutable());

            $user->setPassword($this->passwordHasher->hashPassword($user, 'password' . $i));

            $manager->persist($user);
        }
        $manager->flush();
    }
}