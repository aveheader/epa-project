<?php

namespace App\Service;

interface OrderPersisterInterface
{
    public function save(array $orderData): void;
}
