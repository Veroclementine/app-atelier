<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * this function display all categories
     *
     * @param CategoryRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/category', name: 'app_category', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request): Response
    {

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
    //function new category pour le formulaire
    #[Route('/category/newCategory', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
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

            return $this->redirectToRoute('app_category'); //ver error de redireccion a lista
        }
        return $this->render(
            'category/newCategory.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
