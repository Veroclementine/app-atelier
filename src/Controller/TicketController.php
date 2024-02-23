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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;



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
    ): Response {
        $tickets = $paginator->paginate(
            $ticketRepository->findBy(['user' => $this->getUser()]), //method qui récupère les tickets associés à l'utilisateur authentifié dans l'application.
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        //condition pour savoir s'il y a des tickets ou pas
        if (empty($tickets)) {
            $newTicketButton = true;
        } else {
            $newTicketButton = false;
        }

        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
            'newTicketButton' => $newTicketButton,
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
    ): Response {
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
        EntityManagerInterface $entityManager,
    ): Response {

        $ticket = $ticketRepository->find($id);

        // If the ticket is not found, return an error response
        if (!$ticket) {
            throw $this->createNotFoundException('Ticket non trouvé');
        }

        // Check if the logged-in user is the owner of the ticket
        if ($ticket->getUser() !== $this->getUser()) {
            $this->addFlash('warning', 'Vous n\'êtes pas autorisé à modifier ce ticket.');
            return $this->redirectToRoute('app_ticket');
        }

        // Create the form to edit the ticket
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();

            $entityManager->persist($ticket);
            $entityManager->flush();

            // Message success
            $this->addFlash('success', 'ticket modifié correctement');

            return $this->redirectToRoute('app_ticket');
        }

        // Render the edit form
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
    ): Response {
        $ticket = $ticketRepository->findOneBy(["id" => $id]);

        // Check if the logged-in user is the owner of the ticket
        if ($ticket->getUser() !== $this->getUser()) {
            throw new AccessDeniedException('Pas autorisé à supprimer ce ticket');
        }

        $manager->remove($ticket);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre ticket a été supprimée !'
        );

        return $this->redirectToRoute('app_ticket');
    }
}
