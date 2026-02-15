<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\OrderRequestDTO;
use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

final readonly class OrderCreator
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ServiceTypeProvider    $serviceTypeProvider,
    ) {
    }

    public function create(User $user, OrderRequestDTO $dto): Order
    {
        $service = $this->serviceTypeProvider->getById($dto->serviceId ?? '');

        if (!$service) {
            throw new InvalidArgumentException('Такого сервиса не существует');
        }

        $order = new Order()
            ->setServiceId($service->id)
            ->setEmail($dto->email ?? '')
            ->setPrice($service->price)
            ->setUser($user);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }
}
