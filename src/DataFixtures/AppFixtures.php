<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Ticket;
use App\Entity\Category;
use App\Entity\Client;
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
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($this->faker->word());

            $categories[] = $category; // Agregar la categoría al array
            $manager->persist($category);
        }

        // Tickets
        $tickets = [];
        for ($j = 0; $j < 10; $j++) {
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

        //Clients
        $clients = [];
        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setUsername($this->faker->username())
                ->setEmail($this->faker->email())
                ->setPassword('password');

            $clients[] = $client;
            $manager->persist($client);
        }


        $manager->flush();
    }
}
