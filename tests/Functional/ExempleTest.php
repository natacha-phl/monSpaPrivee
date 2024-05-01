<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExempleTest extends WebTestCase
{
    public function testSomething(): void
    {
        // Instance du client de test de Symfont qui sera utilisé pour effectuer des requêtes HTTP simulées
        $client = static::createClient();

        // Récupération du contenu HTML de la page d'accueil
        $crawler = $client->request('GET', '/');

        // Vérifie que la réponse HTTP de la requête est réussie (code de statut 200)
        $this->assertResponseIsSuccessful();

        // Sélection du bouton 'Valider' sur la page d'accueil
        $button = $crawler->selectButton('Valider');

        // Vérifie s'il y a 1 bouton 'Valider'
        $this->assertEquals(1, count($button));

        // Filtre pour trouver l'élément #home-forms sur la page d'acueil
        $roomSpa = $crawler->filter('#home-forms');

        // Vérifie s'il y a 1 élément #home-forms
        $this->assertEquals(1, count($roomSpa));
    }

}








//public function testSomething(): void
//{
//    $client = static::createClient(); //appel la méthode boot kernel de kernel test case et crée un client qui aura pour role un navigateur
//    $crawler = $client->request('GET', '/');
//
//    $this->assertResponseIsSuccessful();//tester que la réponse est bien successfull
//
////        $button = $crawler->filter('button[type="submit"]');
//    $button = $crawler->selectButton('Valider');
//    $this->assertEquals(1, count($button));
//
//    $roomSpa = $crawler->filter('.rooms .card');
//    $this->assertEquals(4, $roomSpa->count());
//}

