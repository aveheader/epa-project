<?php

namespace App\Form;

use App\Enum\ServiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('service', ChoiceType::class, [
                'label' => 'Сервис',
                'choices' => ServiceType::cases(),
                'placeholder' => 'Выберите сервис',
                'choice_label' => fn(?ServiceType $type) => $type?->label() ?? '',
                'choice_value' => fn(?ServiceType $type) => $type?->value ?? '',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
        ;
    }
}
