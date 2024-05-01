<?php

namespace App\Tests\Unit;

use App\Entity\Room;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExempleTest extends KernelTestCase
{

    public function testUserEntityIsValid(): void
    {
        // Configuration de l'environnement de test de Symfony et accès aix services de l'application
        self::bootKernel();
        $container = static::getContainer();

        //Création d'un nouvel utilisateur avec des propriétés valides
        $user = new User();
        $user->setStatus('standby');
        $user->setFirstName('Prénom 1');
        $user->setLastName('Nom 1');
        $user->setRoles(['ROLE_OWNER']);
        $user->setEmail('test@test.fr');
        $user->setPassword('Abcdef123456@');
        $user->setCity('City Test');
        $user->setStreet('Street Test');
        $user->setZipCode('12345');
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        // Validation de l'entité utilisateur
        $errors = $container->get('validator')->validate($user);

        // Vérifie que le nombre d'erreur est égal à 0
        $this->assertCount(0, $errors);
    }

    public function testEntitySpaIsNotValid()
    {
        self::bootKernel();
        $container = static::getContainer();

        //Création d'un nouvel room (room - spa) avec une capacité invalide
        $room = new Room();
        $room->setCapacity('6 personnes');


        $errors = $container->get('validator')->validate($room);

        // Vérifie que le nombre d'erreur est égal à 1
        $this->assertCount(1, $errors);


    }
}
