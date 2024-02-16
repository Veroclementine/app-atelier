<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManager;
use App\Repository\TicketRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\VarDumper\VarDumper;

class CategoryController extends AbstractController
{
    /**
     * this function or controller display all categories
     *
     * @param CategoryRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/category', name: 'app_category', methods: ['GET'])]
    public function index(
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $categories = $paginator->paginate(
            $categoryRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        //dd($categories); pour voir les donnees
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Controller show a form for create a new category
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/category/newCategory', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        // create notre formulaire
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData());
            $category = $form->getData();

            $manager->persist($category); //commit
            $manager->flush(); //push

            $this->addFlash(
                'success',
                'Votre categorie a été créé avec succès !'
            );

            return $this->redirectToRoute('app_category');
        }
        return $this->render(
            'category/newCategory.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * Controller for editing a category
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/category/edition/{id}', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(
        CategoryRepository $categoryRepository,
        int $id,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $category = $categoryRepository->findOneBy(["id" => $id]);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $manager->persist($category);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre categorie a été modifié avec succès !'
            );

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
   
}
