<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderRequestDTO
{
    #[Assert\NotBlank(message: 'Выберите сервис')]
    public ?string $serviceId = null;

    #[Assert\NotBlank(message: 'Поле email не может быть пустым')]
    #[Assert\Email(message: 'Введите корректный адрес email')]
    public ?string $email = null;
}
