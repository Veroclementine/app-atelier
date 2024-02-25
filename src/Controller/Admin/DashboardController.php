<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Contact;
use App\Entity\Equipment;
use App\Entity\Ticket;
use App\Entity\TicketSolutions;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App Atelier')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Tickets', 'fas fa-ticket', Ticket::class);
        yield MenuItem::linkToCrud('Solutions Tickets', 'fas fa-book', TicketSolutions::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-pencil-square', Category::class);
        yield MenuItem::linkToCrud('Clients', 'fas fa-user-circle', Client::class);
        yield MenuItem::linkToCrud('Equipement', 'fas fa-laptop', Equipment::class);
        yield MenuItem::linkToCrud('Demandes de contact', 'fas fa-envelope', Contact::class);

        // Add a link to return to the homepage
        yield MenuItem::linkToUrl('Accueil', 'fas fa-home', '/');

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
