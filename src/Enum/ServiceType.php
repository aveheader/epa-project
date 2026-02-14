<?php

namespace App\Enum;

enum ServiceType: string
{
    case CAR = 'car';
    case APARTMENT = 'apartment';
    case BUSINESS = 'business';

    public function label(): string
    {
        return match ($this) {
            self::CAR => 'Оценка автомобиля',
            self::APARTMENT => 'Оценка квартиры',
            self::BUSINESS => 'Оценка бизнеса',
        };
    }

    public function price(): int
    {
        return match ($this) {
            self::CAR => 500,
            self::APARTMENT => 800,
            self::BUSINESS => 1500,
        };
    }
}
