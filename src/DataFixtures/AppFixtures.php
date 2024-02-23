<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Client;
use App\Entity\Ticket;
use App\Entity\Contact;
use App\Entity\Category;
use App\Entity\Equipment;
use App\Entity\TicketSolutions;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        //Users 
        $users = [];
        //create admin and put in user table
        $admin = new User();
        $admin->setName('Admin')
            ->setLastname('Atelier Desk')
            ->setEmail('admin@atelierdesk.com')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('password');

        $users[] = $admin;
        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
        }

        // Categories
        $categories = [];
        for ($i = 0; $i < 6; $i++) {
            $category = new Category();
            $category->setName($this->faker->words(2, true));

            $categories[] = $category; // Agregar la categoría al array
            $manager->persist($category);
        }

        // Tickets
        $tickets = [];
        for ($j = 0; $j < 15; $j++) {
            $ticket = new Ticket();
            $ticket->setName($this->faker->words(3, true))
                ->setDescription($this->faker->text(300))
                ->setPriority(mt_rand(0, 1) == 1 ? mt_rand(1, 3) : null)
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            // Seleccionar una categoría aleatoria del array de categorías
            $randomCategory = $categories[array_rand($categories)];
            $ticket->setCategory($randomCategory);

            $tickets[] = $ticket;
            $manager->persist($ticket);
        }

        //ticket_solutions

        $ticketSolutions = [];
        for ($i = 0; $i < 8; $i++) {
            $ticketSolution = new TicketSolutions();
            $ticketSolution->setName($this->faker->words(3, true))
                ->setDescription($this->faker->text(300));

            // Asociar una solución a un ticket existente
            $randomTicket = $tickets[array_rand($tickets)];
            $ticketSolution->setName($randomTicket->getName());

            $ticketSolutions[] = $ticketSolution;
            $manager->persist($ticketSolution);
        }

        //Clients
        $clients = [];
        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setUsername($this->faker->userName())
                ->setEmail($this->faker->email())
                ->setTelephone($this->faker->phoneNumber())
                ->setCity($this->faker->city())
                ->setAddress($this->faker->address());

            $clients[] = $client;
            $manager->persist($client);
        }

        // Create Equipment
        for ($i = 0; $i < 20; $i++) {
            $equipment = new Equipment();
            $equipment->setName($this->faker->word())
                ->setBrand($this->faker->word())
                ->setModel($this->faker->word())
                ->setPurchaseDate($this->faker->dateTimeBetween('-2 years', 'now'))
                ->setWarrantyExpiry($this->faker->dateTimeBetween('now', '+3 years'))
                ->setSerialNumber($this->faker->ean13())
                ->setClient($clients[array_rand($clients)]);

            $manager->persist($equipment);
        }

        $manager->flush();


        //contact
        for ($i = 0; $i < 15; $i++) {
            $contact = new Contact();
            $contact->setName($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setSubject($this->faker->words(3, true))
                ->setMessage($this->faker->text(300));

            $manager->persist($contact);
        }

        $manager->flush();
    }
}
