<?php

namespace App\Form\Admin;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre del producto'
            ])
            ->add('price',TextType::class, [
                'label' => 'Precio unitario'
            ])
            ->add('priceDivider',TextType::class, [
                'label' => 'Precio Divisor'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción'
            ])
            ->add('category', null, [
                'label' => 'Categoria'
            ])
            ->add('envases', null, [
                'label' => 'Envase'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => Product::TYPES,
                'label' => 'Tipo de producto'
            ])
            ->add('divisible')
            ->add('divisibleBy', null, ['required' => false])
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
            ->add('secondaryImageFilename', FileType::class, [
                'label' => 'Brochure (PDF file)',
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
            'data_class' => Product::class,
        ]);
    }
}