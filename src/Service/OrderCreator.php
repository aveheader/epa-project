<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\ServiceType;
use DateTimeImmutable;
use http\Env\Request;
use Symfony\Component\Validator\Constraints\Uuid;

class OrderCreator
{
    public function __construct(
        private readonly OrderPersisterInterface $orderPersister,
    ) {
    }

    public function create(ServiceType $service, string $email, User $user): void
    {
        $order = [
            'id' => Uuid::V7_MONOTONIC,
            'services' => $service->value,
            'price' => $service->price(),
            'email' => $email,
            'user_id' => $user->getId(),
            'created_at' => new DateTimeImmutable(),
        ];

        $this->orderPersister->save($order);
    }
}
