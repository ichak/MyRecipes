<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Recipe $recipe)
    {
        return $this->render('recipes/index.html.twig', [
            'recipe' => $recipe
        ]);
    }
}
