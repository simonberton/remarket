<?php

namespace App\Form\Admin;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre de la categoria'
            ])
            ->add('description',TextareaType::class, [
                'label' => 'Descripción de la categoria'
            ])
            ->add('mainImageFilename', FileType::class, [
                'label' => 'Imagen principal',
                'mapped' => false,
                'required' => true,
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
            'data_class' => Category::class,
        ]);
    }
}