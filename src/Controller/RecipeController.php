<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\NewRecipeType;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Service\Spoonacular;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recipe")
 */
class RecipeController extends AbstractController
{
    /**
     * @Route("/index/{page}", requirements = {"page" : "\d+"}, defaults = {"page" : 1})
     */
    public function index(Request $request, RecipeRepository $repository, int $page): Response
    {
        $search = $request->query->get('search', '');

        $countPerPage = 15;

        if (empty($search)) {
            $recipes = $repository->findAll($page, $countPerPage);
        } else {
            $recipes = $repository->findBySearch($search, $page, $countPerPage);
        }

        $nbPages = ceil($recipes->count() / $countPerPage);

        return $this->render('recipe/index.html.twig', ['recipes' => $recipes, 'page' => $page, 'nbPages' => $nbPages]);
    }

    /**
     * @Route("/{id}", requirements = {"id": "\d+"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Recipe $recipe)
    {
        // Pour afficher une entité on va toujours faire un findOneById du repository de l'entité
        return $this->render('recipe/show.html.twig', ['recipe' => $recipe]);
    }

    /**
     * @Route("/new", name="recipe_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(NewRecipeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*$entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('recipe.new.success', ['%title%' => $recipe->getName()]));
            */
            $formData = $form->getData();
            $search = $formData['name']; // Récupére la donnée du champ 'name'
            return $this->redirectToRoute('recipe_suggest', ['search' => $search]);
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/suggest/{search}", name="recipe_suggest")
     */
    public function suggest(string $search, Spoonacular $spoonacular)
    {
        $result = $spoonacular->search($search);

        return $this->render('recipe/suggest.html.twig', ['results' => $result['results']]);
    }

    /**
     * @Route("/add/{apiId}", name="recipe_add", methods={"GET","POST"}, requirements={"apiId": "\d+"}, defaults={"apiId":0})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function add(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, Spoonacular $spoonacular, $apiId): Response
    {
        $recipe = (new Recipe)
            ->setUser($this->getUser())
        ;

        if ($apiId > 0) { // Choisi une recette dans l'api
            $apiRecipe = $spoonacular->searchById($apiId);
            $recipe->setName($apiRecipe['title']);
            // $recipe->setImage($apiRecipe['image']);
            $recipe->setTime($apiRecipe['readyInMinutes']);
            // $recipe->set($apiRecipe['image']);
            // $recipe->setImage($apiRecipe['image']);
            // $recipe->setImage($apiRecipe['image']);
        }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('recipe.new.success', ['%title%' => $recipe->getName()]));
            
            return $this->redirectToRoute('app_recipe_index');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", requirements = {"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setUser($this->getUser());
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('recipe.edit.success', ['%title%' => $recipe->getName()]));

            return $this->redirectToRoute('app_recipe_index');
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", requirements = {"id": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, Recipe $recipe): Response
    {
        // Crée un formulaire directement dans le controller
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'Supprimer'])
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($recipe);
            $entityManager->flush();
            $this->addFlash('success', 'Recette supprimée');

            return $this->redirectToRoute('accueil');
        }
        return $this->render('recipe/_delete_form.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(), ]
        );
    }
}
