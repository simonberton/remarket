<?php

namespace App\Form\Admin;

use App\Entity\Envase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EnvaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre del envase'
            ])
            ->add('price', null, ['label' => 'Precio'])
            ->add('quantity', null, ['label' => 'Cantidad'])
            ->add('mainImageFilename', FileType::class, [
                'label' => 'Imagen principal',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k'
                    ])
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Guardar'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Envase::class,
        ]);
    }
}