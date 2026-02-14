<?php

namespace App\Service;

class OrderPersister implements OrderPersisterInterface
{
    public function __construct(
        private readonly string $filePath,
    ) {
    }
    public function save(array $orderData): void
    {
        file_put_contents(
            $this->filePath,
            json_encode($orderData),
            FILE_APPEND | LOCK_EX
        );
    }
}
