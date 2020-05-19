<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(RecipeRepository $recipeRepository)
    {
        $recipes = $recipeRepository->findall();

        return $this->render('recipes/index.html.twig', [
            'recipes' => $recipes,
    ]);
    }

    /**
     * @Route("/{id}", requirements = {"id": "\d+"})
     */
    public function show(Recipe $recipe)
    {
        return $this->render('recipes/show.html.twig', [
            'recipe' => $recipe
        ]);
    }
}
