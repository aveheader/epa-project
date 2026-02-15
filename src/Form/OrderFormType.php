<?php

namespace App\Form;

use App\DTO\OrderRequestDTO;
use App\Service\ServiceTypeProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderFormType extends AbstractType
{
    public function __construct(
        private readonly ServiceTypeProvider $provider,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];
        foreach ($this->provider->getAll() as $service) {
            $choices[$service->label] = $service->id;
        }

        $builder
            ->add('serviceId', ChoiceType::class, [
                'label' => 'Сервис',
                'choices' => $choices,
                'placeholder' => 'Выберите сервис',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderRequestDTO::class,
        ]);
    }
}
