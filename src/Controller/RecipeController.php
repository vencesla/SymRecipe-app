<?php

namespace App\Controller;

use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Recipe;

class RecipeController extends AbstractController
{
    /**
     * This controller displyay all recipes
     *
     * @param RecipeRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recettes', name: 'recipe.index', methods: ['GET'])]
    public function index(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $recipes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes'=> $recipes
        ]);
    }

    /**
     * This controller allow us to create new recipe
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/creation', name:'recipe.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();


            $this->addFlash(
                'success',
                'Votre recette a bien été créé avec succès!');

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recette/modification/{id}', name:'recipe.edit', methods: ['GET', 'POST'])]
    public function  edit(
        RecipeRepository $repository,
        int $id,
        Request $request,
        EntityManagerInterface $manager): Response
    {
        $recipe = $repository->findOneBy(['id' => $id]);
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();


            $this->addFlash(
                'success',
                'Votre recette a bien été modifiée avec succès!');

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/recette/suppression/{id}', name:'recipe.delete', methods: ['GET'])]
    public function delete(
        EntityManagerInterface $manager,
        int $id,
        RecipeRepository $repository): Response
    {
        $recipe = $repository->findOneBy(['id' => $id]);

        if(!$recipe) {
            $this->addFlash(
                'warning',
                "Votre recette n'a pas été trouvée");


            return $this->redirectToRoute('recipe.index');
        }
        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre recette a bien été supprimée avec succès!');

        return $this->redirectToRoute('recipe.index');
    }


}
