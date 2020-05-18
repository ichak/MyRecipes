<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Meal;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'recipe.title',
                'attr' => ['placeholder' => 'recipe.title'],
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

            ->add('meals', EntityType::class, [
                'label' => 'recipe.meals',
                'class' => Meal::class,
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'asc')
                    ;
                },
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