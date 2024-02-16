<?php

namespace App\Controller;

use App\Entity\TicketSolutions;
use App\Form\TicketSolutionType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\TicketSolutionsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketsolutionsController extends AbstractController
{
    /**
     * Controller for display ticket solutions
     *
     * @param TicketSolutionsRepository $ticketSolutionsRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ticketsolutions', name: 'app_ticketsolutions', methods:['GET'])]
    public function index(TicketSolutionsRepository $ticketSolutionsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $ticketsolutions = $paginator->paginate(
            $ticketSolutionsRepository->findBy([], ['createdAt' => 'DESC']), // Order by creation date DESC
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );


        return $this->render('ticketsolutions/index.html.twig', [
            'ticketsolutions' => $ticketsolutions,
        ]);
    }

    /**
     * This controller allow us to create a new solution
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ticketsolutions/new', name: 'app_ticketsolutions_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $ticketsolution = new TicketSolutions();
        $form = $this->createForm(TicketSolutionType::class, $ticketsolution);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData());
            $ticketsolution = $form->getData();
            // $ticketsolution->setUser($this->getUser());

            $manager->persist($ticketsolution); //commit
            $manager->flush(); //push

            $this->addFlash(
                'success',
                'Votre solution a été créé avec succès !'
            );

            return $this->redirectToRoute('app_ticketsolutions');
        }

        return $this->render('ticketsolutions/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Controller for editing a ticket
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/ticketsolutions/edition/{id}', name: 'app_ticketsolutions_edit', methods: ['GET', 'POST'])]
    public function edit(
        TicketSolutionsRepository $ticketSolutionsRepository,
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $ticketsolutions = $ticketSolutionsRepository->find($id);

        // Si no se encuentra el ticket, devolver una respuesta de error o redireccionar a una página de error
        if (!$ticketsolutions) {
            throw $this->createNotFoundException('solution non trouvé');
        }

        // Crear el formulario para editar el ticket
        $form = $this->createForm(TicketSolutionType::class, $ticketsolutions);
        $form->handleRequest($request);

        // Procesar la solicitud del formulario
        if ($form->isSubmitted() && $form->isValid()) {
            $ticketsolutions = $form->getData();

            $entityManager->persist($ticketsolutions);
            $entityManager->flush();

            // Añadir un mensaje flash para indicar que el ticket ha sido modificado correctamente
            $this->addFlash('success', 'solution modifié correctement');

            // Redirigir al usuario de vuelta a la lista de tickets después de la edición
            return $this->redirectToRoute('app_ticketsolutions');
        }

        // Renderizar el formulario de edición
        return $this->render('ticketsolutions/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Controller for deleting a solution
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ticketsolutions/delete/{id}', name: 'app_ticketsolutions_delete', methods: ['GET'])]
    public function delete(
        EntityManagerInterface $manager,
        TicketSolutionsRepository $ticketSolutionsRepository,
        int $id,
    ): Response {
        $ticket = $ticketSolutionsRepository->findOneBy(["id" => $id]);


        $manager->remove($ticket);
        $manager->flush();
        $this->addFlash(
            'success',
            'La solution a été supprimée !'
        );

        return $this->redirectToRoute('app_ticketsolutions');
    }


}
