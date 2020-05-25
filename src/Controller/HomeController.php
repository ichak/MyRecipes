<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\SearchRecipeType;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request ,RecipeRepository $recipeRepository)
    {
        $recipes = $recipeRepository->findall();
        $searchRecipeForm = $this->createForm(SearchRecipeType::class);

        if($searchRecipeForm->handleRequest($request)->isSubmitted() && $searchRecipeForm->isValid())
        {
            $search = $searchRecipeForm->getData();
            $recette = $recipeRepository->findBySearch($search);
            dd($recette);
        }

        return $this->render('home/index.html.twig', [
            'recipes' => $recipes,
            'search_form' => $searchRecipeForm->createView(),
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
