<?php

namespace App\Tests\Functional;

use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketTest extends WebTestCase
{ //new ticket
    public function testIfCreateTicketIsSuccessfull(): void
    {
        $client = static::createClient();
        //get url
        $urlGenerator = $client->getContainer()->get('router');
        //get EntityManager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);
        //get page for create a new ticket
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('app_ticket_new'));
        //manage le form
        $form = $crawler->filter('form[name=ticket]')->form([
            'ticket[name]' => "New ticket",
            'ticket[description]' => "ticket description",
            'ticket[priority]' => intval(1),
            'ticket[category]' => "3",
            'ticket[isOpen]' => true,
            
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert.alert-success', 'Votre ticket a été créé avec succès !');

        $this->assertRouteSame('app_ticket');
    }

//update ticket
    public function testIfUpdateTicketIsSuccessfull(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);
        $ticket = $entityManager->getRepository(Ticket::class)->findOneBy([
            'user' => $user
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_ticket_edition', ['id' => $ticket->getId()])
        );

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name=ticket]')->form([
            'ticket[name]' => "Update ticket",
            'ticket[description]' => "ticket description",
            'ticket[priority]' => intval(1),
            'ticket[category]' => "4",
            'ticket[isOpen]' => true,

        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert.alert-success', 'ticket modifié correctement');

        $this->assertRouteSame('app_ticket');
    }

    //delete ticket
    public function testIfDeleteTicketIsSuccessful(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);
        $ticket = $entityManager->getRepository(Ticket::class)->findOneBy([
            'user' => $user
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate('app_ticket_delete', ['id' => $ticket->getId()])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert.alert-success', 'Votre ticket a été supprimée !');

        $this->assertRouteSame('app_ticket');
    }
}

