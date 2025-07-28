<?php
namespace App\DataFixtures;

use App\Entity\Agent;
use App\Entity\Client;
use App\Entity\Bien;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Creo agentes de prueba para tener variedad de roles en el sistema ---
        $agent1 = new Agent();
        $agent1->setNom('García');
        $agent1->setPrenom('Juan');
        $agent1->setEmail('juan.garcia@inmobiliaria.com');
        $agent1->setTelephone('3101234567');
        $agent1->setPassword('secret123');
        $agent1->setRoles(['ROLE_AGENT']);
        $agent1->setTypeDeContrat('Indefinido');
        $agent1->setDateEmbauche(new \DateTime('2022-01-15'));
        $manager->persist($agent1);

        $agent2 = new Agent();
        $agent2->setNom('Martínez');
        $agent2->setPrenom('Camila');
        $agent2->setEmail('camila.martinez@inmobiliaria.com');
        $agent2->setTelephone('3207654321');
        $agent2->setPassword('secret456');
        $agent2->setRoles(['ROLE_AGENT']);
        $agent2->setTypeDeContrat('Temporal');
        $agent2->setDateEmbauche(new \DateTime('2023-03-10'));
        $manager->persist($agent2);

        // --- Creo clientes de ejemplo para las funcionalidades de favoritos ---
        $client1 = new Client();
        $client1->setNom('Rodríguez');
        $client1->setPrenom('Sofía');
        $client1->setEmail('sofia.rodriguez@gmail.com');
        $client1->setTelephone('3119988776');
        $manager->persist($client1);

        $client2 = new Client();
        $client2->setNom('Pérez');
        $client2->setPrenom('Andrés');
        $client2->setEmail('andres.perez@gmail.com');
        $client2->setTelephone('3125566778');
        $manager->persist($client2);

        // --- Creo dos bienes fijos (uno apartamento y uno casa), asignando sus fotos ---
        $bien1 = new Bien();
        $bien1->setTypeDeBien('Appartement');
        $bien1->setVille('Bogotá');
        $bien1->setAdresse('Carrera 7 #85-24, Chapinero');
        $bien1->setPrix('520000000');
        $bien1->setSurfaceM2(90);
        $bien1->setEtatDuBien('Neuf');
        $bien1->setTipoTransaccion('venta');
        $bien1->setFoto('assets/img/apartamentos/apto1.jpg'); // Asigno la foto antes de persistir
        $manager->persist($bien1);

        $bien2 = new Bien();
        $bien2->setTypeDeBien('Maison');
        $bien2->setVille('Bogotá');
        $bien2->setAdresse('Calle 127 #45-19, Suba');
        $bien2->setPrix('780000000');
        $bien2->setSurfaceM2(180);
        $bien2->setEtatDuBien('Rénové');
        $bien2->setTipoTransaccion('arriendo');
        $bien2->setFoto('assets/img/casas/casa1.jpg'); // Asigno la foto antes de persistir
        $manager->persist($bien2);

        // --- Genero automáticamente 30 bienes con fotos variadas para pruebas y paginación ---
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

            // Elijo valores aleatorios para dar realismo a los datos de ejemplo
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

            // Asigno una foto acorde al tipo de bien, eligiendo aleatoriamente entre 16 imágenes de cada tipo
            if ($tipo === 'Maison') {
                $bien->setFoto('assets/img/casas/casa' . rand(1, 16) . '.jpg');
            } else {
                $bien->setFoto('assets/img/apartamentos/apto' . rand(1, 16) . '.jpg');
            }
            $manager->persist($bien);
        }

        // --- Guardo todos los cambios en la base de datos ---
        $manager->flush();
    }
}
