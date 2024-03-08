<?php

namespace App\Tests\Unit;

use App\Entity\Ticket;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TicketTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = $this->getContainer();

        $ticket = new Ticket();
        $ticket->setName('Ticket #1')
            ->setDescription('Description #1')
            ->setIsOpen(true)
            ->setCreateAt(new \DateTimeImmutable())
            ->setUpdateAt(new \DateTimeImmutable())
            ->setPriority(1);


        $errors = $container->get('validator')->validate($ticket);

        $this->assertCount(0, $errors);
    }

    public function testGettersAndSetters()
    {
        self::bootKernel();

        $ticket = new Ticket();

        // Test setters and getters for name
        $ticket->setName('Test Ticket Name');
        $this->assertEquals('Test Ticket Name', $ticket->getName());

        // Test setters and getters for description
        $ticket->setDescription('Test Ticket Description');
        $this->assertEquals('Test Ticket Description', $ticket->getDescription());

        // Test setters and getters for createAt
        $createAt = new \DateTimeImmutable('2024-02-13');
        $ticket->setCreateAt($createAt);
        $this->assertEquals($createAt, $ticket->getCreateAt());

        // Test setters and getters for updateAt
        $updateAt = new \DateTimeImmutable('2024-02-14');
        $ticket->setUpdateAt($updateAt);
        $this->assertEquals($updateAt, $ticket->getUpdateAt());

        // Test setters and getters for category
        $category = new Category();
        $ticket->setCategory($category);
        $this->assertEquals($category, $ticket->getCategory());

        // Test setters and getters for priority
        $ticket->setPriority(1);
        $this->assertEquals(1, $ticket->getPriority());

        // Test setters and getters for user
        $user = new User();
        $ticket->setUser($user);
        $this->assertEquals($user, $ticket->getUser());
    }
}
