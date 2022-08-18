<?php

namespace App\Form;


use App\Form\Data\OrderData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('deliveryAddress')
            ->add('deliveryCity')
            ->add('deliveryPhone')
            ->add('deliveryZone', ChoiceType::class, [
                'choices' => [
                    'Cordón $150' => 'Cordón $150',
                    'Parque Rodó $150' => 'Parque Rodó $150',
                    'Parque Batlle $200' => 'Parque Batlle $200',
                ],
            ])
            ->add('pickupRefillments', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Si' => true,
                ]
            ])
            ->add('pickupAddress')
            ->add('pickupCity')
            ->add('pickupPhone')
            ->add('pickupZone', ChoiceType::class, [
                'choices' => [
                    'Cordón' => 'Cordón',
                    'Parque Rodó' => 'Parque Rodó',
                    'Parque Batlle' => 'Parque Batlle',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderData::class,
        ]);
    }
}
