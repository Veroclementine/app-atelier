<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactTest extends WebTestCase
{
    public function testFormContact(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Formulaire de contact');

        //retrieve the formulaire de contact
        $submitButton = $crawler->selectButton('Envoyer message');
        $form = $submitButton->form();

        //fill the form
        $form["contact[name]"] = "Jean";
        $form["contact[lastname]"] = "Loustau";
        $form["contact[email]"] = "jlouestau@gmail.com";
        $form["contact[subject]"] = "Test Functional Contact";
        $form["contact[message]"] = "Message test"; 

        //Submit form
        $client->submit($form);

        // Check status HTTP// This assertion testing the form submissions that should redirect the user to another page after it's performed successfully. 
        $this->assertResponseRedirects();

        //check email sent
        $this->assertEmailCount(1);

        $client->followRedirect();

        //check message succes
        $this->assertSelectorTextContains('div.alert.alert-succes', 'Votre message a été envoyé avec succès!');
    }
}
