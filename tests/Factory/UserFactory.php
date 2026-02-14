<?php

namespace App\tests\Factory;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function create(
        string $email = 'test@test.ru',
        string $password = 'password',
        array $roles = ['ROLE_USER']
    ): User {
        $user = new User();

        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
