<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Bien;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    // Inyecto el hasher para codificar contraseñas
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- Agentes (typeUser = 'admin' o 'agent') ---
        $agent1 = new User();
        $agent1->setEmail('juan.garcia@inmobiliaria.com');
        $agent1->setFirstName('Juan');
        $agent1->setLastName('García');
        $agent1->setPhone('3101234567');
        $agent1->setTypeUser('admin');
        $agent1->setRoles(['ROLE_ADMIN']);
        $agent1->setPassword($this->passwordHasher->hashPassword($agent1, 'secret123'));
        $manager->persist($agent1);

        $agent2 = new User();
        $agent2->setEmail('camila.martinez@inmobiliaria.com');
        $agent2->setFirstName('Camila');
        $agent2->setLastName('Martínez');
        $agent2->setPhone('3207654321');
        $agent2->setTypeUser('admin');
        $agent2->setRoles(['ROLE_ADMIN']);
        $agent2->setPassword($this->passwordHasher->hashPassword($agent2, 'secret456'));
        $manager->persist($agent2);

        // --- Clientes de ejemplo ---
        $client1 = new User();
        $client1->setEmail('sofia.rodriguez@gmail.com');
        $client1->setFirstName('Sofía');
        $client1->setLastName('Rodríguez');
        $client1->setPhone('3119988776');
        $client1->setTypeUser('client');
        $client1->setRoles(['ROLE_CLIENT']);
        $client1->setPassword($this->passwordHasher->hashPassword($client1, 'clientpass'));
        $manager->persist($client1);

        $client2 = new User();
        $client2->setEmail('andres.perez@gmail.com');
        $client2->setFirstName('Andrés');
        $client2->setLastName('Pérez');
        $client2->setPhone('3125566778');
        $client2->setTypeUser('client');
        $client2->setRoles(['ROLE_CLIENT']);
        $client2->setPassword($this->passwordHasher->hashPassword($client2, 'clientpass'));
        $manager->persist($client2);

        // --- Bienes fijos ---
        $bien1 = new Bien();
        $bien1->setTypeDeBien('Appartement');
        $bien1->setVille('Bogotá');
        $bien1->setAdresse('Carrera 7 #85-24, Chapinero');
        $bien1->setPrix('520000000');
        $bien1->setSurfaceM2(90);
        $bien1->setEtatDuBien('Neuf');
        $bien1->setTipoTransaccion('venta');
        $bien1->setFoto('assets/img/apartamentos/apto1.jpg');
        $bien1->setProprietaire($agent1); // Asigno el agente como propietario
        $manager->persist($bien1);

        $bien2 = new Bien();
        $bien2->setTypeDeBien('Maison');
        $bien2->setVille('Bogotá');
        $bien2->setAdresse('Calle 127 #45-19, Suba');
        $bien2->setPrix('780000000');
        $bien2->setSurfaceM2(180);
        $bien2->setEtatDuBien('Rénové');
        $bien2->setTipoTransaccion('arriendo');
        $bien2->setFoto('assets/img/casas/casa1.jpg');
        $bien2->setProprietaire($agent2);
        $manager->persist($bien2);

        // --- Bienes dinámicos (30 aleatorios) ---
        $ciudades = ['Bogotá', 'Medellín', 'Cartagena', 'Villavicencio'];
        $barrios = [
            'Bogotá' => ['Chapinero', 'Usaquén', 'Teusaquillo', 'Cedritos'],
            'Medellín' => ['El Poblado', 'Laureles', 'Envigado'],
            'Cartagena' => ['Bocagrande', 'Centro', 'Manga'],
            'Villavicencio' => ['La Esperanza', 'El Barzal', 'La Grama']
        ];
        $tipos = ['Appartement', 'Maison'];
        $etats = ['Neuf', 'Ancien', 'Rénové'];
        $tiposTransaccion = ['venta', 'arriendo', 'vacacional'];

        for ($i = 1; $i <= 30; $i++) {
            $bien = new Bien();

            $ciudad = $ciudades[array_rand($ciudades)];
            $barrio = $barrios[$ciudad][array_rand($barrios[$ciudad])];
            $tipo = $tipos[array_rand($tipos)];
            $etat = $etats[array_rand($etats)];
            $tipoTransaccion = $tiposTransaccion[array_rand($tiposTransaccion)];

            $bien->setTypeDeBien($tipo);
            $bien->setVille($ciudad);
            $bien->setAdresse("Calle ".rand(1,99)." #".rand(1,99)."-".rand(1,99).", $barrio");
            $bien->setPrix(rand(80000000, 900000000));
            $bien->setSurfaceM2(rand(40, 200));
            $bien->setEtatDuBien($etat);
            $bien->setTipoTransaccion($tipoTransaccion);

            if ($tipo === 'Maison') {
                $bien->setFoto('assets/img/casas/casa' . rand(1, 16) . '.jpg');
            } else {
                $bien->setFoto('assets/img/apartamentos/apto' . rand(1, 16) . '.jpg');
            }

            // Asigno propietario aleatorio (agente1 o agente2)
            $bien->setProprietaire(rand(0, 1) ? $agent1 : $agent2);
            $manager->persist($bien);
        }

        $manager->flush();
    }
}
