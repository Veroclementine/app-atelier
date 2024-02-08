<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



class TicketController extends AbstractController
{
        /**
     * this function or controller display all tickets
     *
     * @param TicketRepository $ticketRepository,
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ticket', name: 'app_ticket', methods: ['GET'])]
    public function index(
    TicketRepository $ticketRepository, 
    PaginatorInterface $paginator,
    Request $request
    ): Response
    {
            $tickets = $paginator->paginate(
            $ticketRepository->findBy(['user' => $this -> getUser()] ),//method qui récupère les tickets associés à l'utilisateur authentifié dans l'application.
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets
        ]);
    }

    /**
     * This controller allow us to create a new ticket
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ticket/newTicket', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $manager
    ):
    Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData());
            $ticket = $form->getData();
            $ticket->setUser($this->getUser());

            $manager->persist($ticket); //commit
            $manager->flush(); //push

            $this->addFlash(
                'success',
                'Votre ticket a été créé avec succès !'
            );

            return $this->redirectToRoute('app_ticket'); 
        }

        return $this->render('ticket/newTicket.html.twig', [
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


    #[Route('/ticket/edition/{id}', name: 'app_ticket_edition', methods: ['GET', 'POST'])]
    public function edit(
        TicketRepository $ticketRepository,
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $ticket = $ticketRepository->findOneBy(["id" => $id]);
        dump($id);
         // Si no se encuentra el ticket, devolver una respuesta de error o redireccionar a una página de error
         if (!$ticket) {
            throw $this->createNotFoundException('Ticket no encontrado');
        }

        // Crear el formulario para editar el ticket
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);
    
        // Procesar la solicitud del formulario
        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();

            $entityManager->persist($ticket);
            $entityManager->flush();
    
            // Añadir un mensaje flash para indicar que el ticket ha sido modificado correctamente
            $this->addFlash('success', 'ticket modifié');
    
            // Redirigir al usuario de vuelta a la lista de tickets después de la edición
            return $this->redirectToRoute('app_ticket');
        }
    
        // Renderizar el formulario de edición
        return $this->render('ticket/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


   /**
     * Controller for deleting a ticket
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ticket/delete/{id}', name: 'app_ticket_delete', methods: ['GET'])]
    public function delete(
    EntityManagerInterface $manager,
    TicketRepository $ticketRepository, 
    int $id, 
        ): Response
    {
            $ticket = $ticketRepository->findOneBy(["id" => $id]);
            $manager->remove($ticket);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre ticket a été supprimée !'
            );

            return $this->redirectToRoute('app_ticket');
        }

    

}
