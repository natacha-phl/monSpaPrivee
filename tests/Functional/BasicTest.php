<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BasicTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient(); //client crée avec la méthode create client
        $crawler = $client->request('GET', '/');// le client va nous permettre d'aller sur tel ou tel url avec la méthode request

        $this->assertResponseIsSuccessful(); //comme quoi la réponse est succesfull bien retour 200 car la page existe
//        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
// encironnement test différent de l'environnement de développement