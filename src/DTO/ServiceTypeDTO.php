<?php

namespace App\DTO;

final class ServiceTypeDTO
{
    public function __construct(
        public string $id,
        public string $label,
        public int $price,
    ) {
    }
}
