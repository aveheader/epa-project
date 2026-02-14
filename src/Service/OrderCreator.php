<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\ServiceType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;


class OrderCreator
{
    public function __construct(
        private readonly OrderPersisterInterface $orderPersister,
    ) {
    }

    public function create(ServiceType $service, string $email, User $user): void
    {
        $order = [
            'id' => Uuid::v7()->toString(),
            'services' => $service->value,
            'price' => $service->price(),
            'email' => $email,
            'user_id' => $user->getId(),
            'created_at' => new DateTimeImmutable()->format('Y-m-d H:i:s'),
        ];

        $this->orderPersister->save($order);
    }
}
