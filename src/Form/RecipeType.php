<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'recipe.name',
                'attr' => ['placeholder' => 'recipe.name'],
            ])

            ->add('image', ImageType::class, ['label' => false])

            ->add('deleteImage', CheckboxType::class, [
                'label' => 'recipe.delete_image',
                'required' => false,
            ])

            ->add('time', null, [
                'label' => 'recipe.time',
                'attr' => ['placeholder' => 'recipe.time'],
            ])

            ->add('recipeIngredients', CollectionType::class, [
                'label' => 'recipe.ingredients',
                'entry_type' => RecipeIngredientType::class, 
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false,
                'by_reference' => false,
            ])

            ->add('step', CollectionType::class, [
                'label' => 'recipe.steps',
                'entry_type' => StepType::class, 
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false,
                'by_reference' => false,
            ])

            ->add('meals', CollectionType::class, [
                'label' => 'recipe.meals',
                'entry_type' => MealType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false,
                'by_reference' => false,
            ])

            ->add('save', SubmitType::class, ['label' => 'save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}