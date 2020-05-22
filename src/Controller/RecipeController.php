<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
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
        $recipe = new Recipe();
        $recipe->setUser($this->getUser());
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
            'recipe' => $recipe,
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

            return $this->redirectToRoute('recipe_index');
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
