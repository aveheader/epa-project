<?php

namespace App\Service;

use App\DTO\ServiceTypeDTO;

final class ServiceTypeProvider
{
    /** @return ServiceTypeDTO[] */
    public function getAll(): array
    {
        return [
            new ServiceTypeDTO(id: 'car', label: 'Оценка машины', price: 500),
            new ServiceTypeDTO(id: 'apartment', label: 'Оценка квартиры', price: 800),
            new ServiceTypeDTO(id: 'business', label: 'Оценка бизнеса', price: 1500),
        ];
    }

    public function getById(string $id): ?ServiceTypeDTO
    {
        foreach ($this->getAll() as $dto) {
            if ($dto->id === $id) {
                return $dto;
            }
        }

        return null;
    }

    /** @return array<string,int> id => price */
    public function getPricesMap(): array
    {
        $map = [];
        foreach ($this->getAll() as $dto) {
            $map[$dto->id] = $dto->price;
        }

        return $map;
    }
}
