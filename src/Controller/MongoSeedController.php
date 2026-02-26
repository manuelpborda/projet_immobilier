<?php

namespace App\Controller;

use App\Document\ContactLog;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MongoSeedController extends AbstractController
{
    #[Route('/debug/odm/seed', name: 'debug_odm_seed')]
    public function seed(DocumentManager $dm): Response
    {
        $doc = (new ContactLog())
            ->setName('Desde ODM')
            ->setPhone('123')
            ->setEmail('odm@example.com')
            ->setMessage('Insert de prueba via Doctrine ODM');

        $dm->persist($doc);
        $dm->flush();

        return new Response('OK ODM seed');
    }
    #[Route('/debug/mongo/ping', name: 'debug_mongo_ping')]
public function debugMongoPing(DocumentManager $dm): Response
{
    $client = $dm->getClient();
    $dbName = $dm->getConfiguration()->getDefaultDB();
    $client->selectDatabase($dbName)->command(['ping' => 1]);

    return new Response('Ping OK. DB ODM: ' . $dbName);
}
}
