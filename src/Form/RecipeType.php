<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Meal;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

            ->add('ingredients', null, [
                'label' => 'recipe.ingredients',
                'attr' => ['placeholder' => 'recipe.ingredients'],
            ])

            ->add('steps', null, [
                'label' => 'recipe.steps',
                'attr' => ['class' => 'wysiwyg'],
            ])

            ->add('meals', ChoiceType::class, [
                'label' => 'recipe.meal',
                'help' => 'recipe.meal_help',
                'expanded' => true,
                'choices' => [
                    'recipe.breakfast' => 0,
                    'recipe.lunch' => 1,
                    'recipe.diner' => 2,
                ],
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