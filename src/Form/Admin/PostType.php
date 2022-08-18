<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\PostCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Título'
            ])
            ->add('shortDescription',TextareaType::class, [
                'label' => 'Descripción breve (máximo 150 caracteres)'
            ])
            ->add('description',TextareaType::class, [
                'label' => 'Texto',
                'required' => false
            ])
            ->add('category',EntityType::class, [
                'class' => PostCategory::class,
                'label' => 'Categoria',
                'placeholder' => 'Seleccione una categoria'
            ])
            ->add('image', FileType::class, [
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
            'data_class' => Post::class,
        ]);
    }
}