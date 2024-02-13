<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        // check that the "Connexion" link is present and has the correct URL.
        //Symfony's link() method returns an absolute URL by default. To compare it correctly, you should extract just the path component from the URL. You can use parse_url() to achieve this.
        $conexionLink = $crawler->filter('a.btn-primary')->link();
        $this->assertSame('/connexion', parse_url($conexionLink->getUri(), PHP_URL_PATH));

        // check that the "Contact" link is present and has the correct URL.
        $contactLink = $crawler->filter('a.btn-secondary')->link();
        $this->assertSame('/contact', parse_url($contactLink->getUri(), PHP_URL_PATH));


        $this->assertSelectorTextContains('h1', 'Bienvenue sur Atelier - Desk');
    }
}
