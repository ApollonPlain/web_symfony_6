<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'password123'
        );

        $user->setPassword($hashedPassword);
        $user->setIsVerified(true);

        $manager->persist($user);

        // Create reference for this user to be used in other fixtures
        $this->addReference('default-user', $user);

        $manager->flush();
    }
}
