<?php

namespace App\Tests\Helpers;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserHelper
{
    public static function ensureUser(
        Container $container,
        string $email = 'test@test.ru',
        string $password = 'password',
        array $roles = ['ROLE_USER']
    ): User {
        $entityManager = $container->get(EntityManagerInterface::class);
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $user = new User();

        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $password)
        );

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }
}
